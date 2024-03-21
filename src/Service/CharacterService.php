<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\DtoInterface;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Service\Factory\CharacterFactory;
use App\Tools\PaginatorInterface;
use App\Tools\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;

class CharacterService implements ServiceInterface
{
    public function __construct(private CharacterRepository $characterRepository,
        private UrlGeneratorInterface $urlGenerator,
        private PaginatorInterface $paginator,
        private CharacterFactory $characterFactory,
        private EntityManagerInterface $entityManager, )
    {
    }

    public function getEntities(int $page, int $limit, array $queries = []): array
    {
        if (empty($queries)) {
            $characters = $this->characterRepository->findAll();
            $count = $this->characterRepository->getTotalEntityCount();
        } else {
            $characters = $this->characterRepository->findByFilters($queries);
            $count = $this->characterRepository->getTotalEntityCountWithFilters($queries);
        }

        $data = [];

        foreach ($characters as $character) {
            $data[] = $this->formatCharacterData($character);
        }

        $options = [
            'page' => $page,
            'entityName' => 'character',
            'limit' => $limit,
            'query' => $queries,
        ];

        $data = $this->paginator->paginate($data, $options);
        $info = $this->paginator->formatInfo($data, $count, $options);

        return [
            'info' => $info,
            'results' => $data,
        ];
    }

    public function getEntitiesByIds(int $page, int $limit, string $ids): array
    {
        $characterIds = explode(',', $ids);

        $data = [];

        foreach ($characterIds as $id) {
            $character = $this->characterRepository->find($id);

            if (!$character) {
                continue;
            }

            $data[] = $this->formatCharacterData($character);
        }

        return $data;
    }

    public function getEntity(int $id): ?array
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return null;
        }

        return $this->formatCharacterData($character);
    }

    public function createEntity(DtoInterface $dto): array
    {
        $character = $this->characterFactory->createEntityFromDto($dto);

        $this->entityManager->persist($character);
        $this->entityManager->flush();

        return $this->formatCharacterData($character);
    }

    public function putUpdateEntity(int $id, DtoInterface $dto): ?array
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return null;
        }

        $character = $this->characterFactory->putUpdateEntityFromDto($character, $dto);

        $this->entityManager->persist($character);
        $this->entityManager->flush();

        return $this->formatCharacterData($character);
    }

    public function patchUpdateEntity(int $id, DtoInterface $dto): ?array
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return null;
        }

        $character = $this->characterFactory->patchUpdateEntityFromDto($character, $dto);

        $this->entityManager->persist($character);
        $this->entityManager->flush();

        return $this->formatCharacterData($character);
    }

    public function deleteEntity(int $id): bool
    {
        $character = $this->characterRepository->find($id);

        if (!$character) {
            return false;
        }

        $this->entityManager->remove($character);
        $this->entityManager->flush();

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
}
