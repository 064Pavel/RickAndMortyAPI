<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\Character;
use App\Entity\EntityInterface;
use App\Repository\EpisodeRepository;
use App\Repository\LocationRepository;
use DateTimeImmutable;

class CharacterFactory implements EntityFactoryInterface
{
    public function __construct(private LocationRepository $locationRepository,
        private EpisodeRepository $episodeRepository, )
    {
    }

    public function createEntityFromDto(DtoInterface $dto): EntityInterface
    {
        $character = new Character();
        $character->setName($dto->getName());
        $character->setStatus($dto->getStatus());
        $character->setSpecies($dto->getSpecies());
        $character->setType($dto->getType());
        $character->setGender($dto->getGender());
        $character->setImage($dto->getImage());

        $originId = $dto->getOrigin()->getId();
        $locationId = $dto->getLocation()->getId();

        $origin = $this->locationRepository->find($originId);
        $location = $this->locationRepository->find($locationId);

        $character->setOrigin($origin);
        $character->setLocation($location);
        if (!empty($dto->getEpisodes())) {
            foreach ($dto->getEpisodes() as $episodeId) {
                $episode = $this->episodeRepository->find($episodeId);
                if ($episode) {
                    $character->addEpisode($episode);
                }
            }
        }

        $character->setCreated(new DateTimeImmutable());

        return $character;
    }

    public function putUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface
    {
        $entity->setName($dto->getName());
        $entity->setStatus($dto->getStatus());
        $entity->setSpecies($dto->getSpecies());
        $entity->setType($dto->getType());
        $entity->setGender($dto->getGender());
        $entity->setImage($dto->getImage());

        $originId = $dto->getOrigin()->getId();
        $locationId = $dto->getLocation()->getId();

        $origin = $this->locationRepository->find($originId);
        $location = $this->locationRepository->find($locationId);

        $entity->setOrigin($origin);
        $entity->setLocation($location);

        if (!empty($dto->getEpisodes())) {
            $entity->removeAllEpisodes();
            foreach ($dto->getEpisodes() as $episodeId) {
                $episode = $this->episodeRepository->find($episodeId);
                if ($episode) {
                    $entity->addEpisode($episode);
                }
            }
        }

        return $entity;
    }

    public function patchUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface
    {
        if (null !== $dto->getName()) {
            $entity->setName($dto->getName());
        }

        if (null !== $dto->getStatus()) {
            $entity->setStatus($dto->getStatus());
        }

        if (null !== $dto->getSpecies()) {
            $entity->setSpecies($dto->getSpecies());
        }

        if (null !== $dto->getType()) {
            $entity->setType($dto->getType());
        }

        if (null !== $dto->getGender()) {
            $entity->setGender($dto->getGender());
        }

        if (null !== $dto->getImage()) {
            $entity->setImage($dto->getImage());
        }

        if (null !== $dto->getOrigin()) {
            $originId = $dto->getOrigin()->getId();
            $origin = $this->locationRepository->find($originId);
            $entity->setOrigin($origin);
        }

        if (null !== $dto->getLocation()) {
            $locationId = $dto->getLocation()->getId();
            $location = $this->locationRepository->find($locationId);
            $entity->setLocation($location);
        }

        if (!empty($dto->getEpisodes())) {
            $entity->removeAllEpisodes();
            foreach ($dto->getEpisodes() as $episodeId) {
                $episode = $this->episodeRepository->find($episodeId);
                if ($episode) {
                    $entity->addEpisode($episode);
                }
            }
        }

        return $entity;
    }
}
