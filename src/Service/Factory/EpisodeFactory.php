<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;
use App\Entity\Episode;
use DateTimeImmutable;

class EpisodeFactory implements EntityFactoryInterface
{
    public function createEntityFromDto(DtoInterface $dto): EntityInterface
    {
        $episode = new Episode();
        $episode->setName($dto->getName());
        $episode->setEpisode($dto->getEpisode());
        $episode->setAirDate($dto->getAirDate());
        $episode->setViews($dto->getViews());
        $episode->setCreated(new DateTimeImmutable());

        return $episode;
    }

    public function putUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface
    {
        $entity->setName($dto->getName());
        $entity->setEpisode($dto->getEpisode());
        $entity->setAirDate($dto->getAirDate());
        $entity->setViews($dto->getViews());

        return $entity;
    }

    public function patchUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface
    {
        if (null !== $dto->getName()) {
            $entity->setName($dto->getName());
        }

        if (null !== $dto->getEpisode()) {
            $entity->setEpisode($dto->getEpisode());
        }

        if (null !== $dto->getAirDate()) {
            $entity->setAirDate($dto->getAirDate());
        }

        if (null !== $dto->getViews()) {
            $entity->setViews($dto->getViews());
        }

        return $entity;
    }
}
