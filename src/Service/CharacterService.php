<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CharacterDto;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Repository\EpisodeRepository;
use App\Repository\LocationRepository;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use DateTime;
use Symfony\Component\Serializer\SerializerInterface;

class CharacterService
{
    private CharacterRepository $characterRepository;
    private LocationRepository $locationRepository;
    private EpisodeRepository $episodeRepository;
    private UrlGeneratorInterface $urlGenerator;
    private SerializerInterface $serializer;
    private PaginatorInterface $paginator;

    public function __construct(CharacterRepository $characterRepository,
        UrlGeneratorInterface $urlGenerator,
        LocationRepository $locationRepository,
        EpisodeRepository $episodeRepository,
        SerializerInterface $serializer, PaginatorInterface $paginator)
    {
        $this->characterRepository = $characterRepository;
        $this->urlGenerator = $urlGenerator;
        $this->locationRepository = $locationRepository;
        $this->episodeRepository = $episodeRepository;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
    }

    public function getCharacters(int $page, int $limit, array $queries): array
    {
        if (empty($queries)) {
            $characters = $this->characterRepository->findAll();
        } else {
            $characters = $this->characterRepository->findByFilters($queries);
        }

        $data = [];

        foreach ($characters as $character) {
            $data[] = $this->formatCharacterData($character);
        }


        $count = $this->episodeRepository->getTotalEntityCount();

        $options = [
            'page' => $page,
            'entityName' => 'location',
            'limit' => $limit,
        ];

        $data = $this->paginator->paginate($data, $options);
        $info = $this->paginator->formatInfo($data, $count, $options);

        return [
            'info' => $info,
            'results' => $data,
        ];
    }

    public function getCharacter(int $id): ?array
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return null;
        }

        return $this->formatCharacterData($character);
    }

    public function createCharacter(CharacterDto $characterDto, array $serializationContext = []): ?array
    {
        return $this->processCharacterData($characterDto, null, $serializationContext);
    }

    public function updateCharacter(int $id, CharacterDto $characterDto, array $serializationContext = []): ?array
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return null;
        }

        return $this->processCharacterData($characterDto, $character, $serializationContext);
    }

    public function deleteCharacter(int $id): bool
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return false;
        }

        $this->characterRepository->remove($character);

        return true;
    }

    private function formatCharacterData(Character $character): array
    {
        $episodes = $character->getEpisodes()->toArray();
        $episodesUrls = $this->urlGenerator->generateUrls($episodes, 'episode');

        $origin = $character->getOrigin();
        $location = $character->getLocation();

        $originData = [
            'name' => $origin?->getName(),
            'url' => $origin ? $this->urlGenerator->getCurrentUrl($origin->getId(), 'location') : null,
        ];
        $locationData = [
            'name' => $location?->getName(),
            'url' => $location ? $this->urlGenerator->getCurrentUrl($location->getId(), 'location') : null,
        ];

        return [
            'id' => $character->getId(),
            'name' => $character->getName(),
            'status' => $character->getStatus(),
            'species' => $character->getSpecies(),
            'type' => $character->getType(),
            'gender' => $character->getGender(),
            'origin' => $originData,
            'location' => $locationData,
            'image' => $character->getImage(),
            'episode' => $episodesUrls,
            'url' => $this->urlGenerator->getCurrentUrl($character->getId(), 'character'),
            'created' => $character->getCreated(),
        ];
    }

    private function processCharacterData(CharacterDto $characterDto, ?Character $character = null, array $serializationContext = []): ?array
    {
        if (!$character) {
            $character = new Character();
        }

        $character->setName($characterDto->getName());
        $character->setStatus($characterDto->getStatus());
        $character->setSpecies($characterDto->getSpecies());
        $character->setType($characterDto->getType());
        $character->setGender($characterDto->getGender());
        $character->setImage($characterDto->getImage());

        $originId = $characterDto->getOrigin()->getId();
        $locationId = $characterDto->getLocation()->getId();

        $origin = $this->locationRepository->find($originId);
        $location = $this->locationRepository->find($locationId);

        $character->setOrigin($origin);
        $character->setLocation($location);

        if (!empty($characterDto->getEpisodes())) {
            foreach ($characterDto->getEpisodes() as $episodeId) {
                $episode = $this->episodeRepository->find($episodeId);
                if ($episode) {
                    $character->addEpisode($episode);
                }
            }
        }

        $character->setCreated(new DateTime());

        $this->characterRepository->save($character);

        return $this->serializeCharacter($character, $serializationContext);
    }

    private function serializeCharacter(Character $character, array $serializationContext = []): array
    {
        $serializedCharacter = $this->serializer->serialize($character, 'json', $serializationContext);

        return json_decode($serializedCharacter, true);
    }
}
