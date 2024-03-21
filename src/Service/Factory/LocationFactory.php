<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;
use App\Entity\Location;
use DateTimeImmutable;

class LocationFactory implements EntityFactoryInterface
{
    public function createEntityFromDto(DtoInterface $dto): EntityInterface
    {
        $entity = new Location();
        $entity->setName($dto->getName());
        $entity->setType($dto->getType());
        $entity->setDimension($dto->getDimension());
        $entity->setCreated(new DateTimeImmutable());

        return $entity;
    }

    public function putUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface
    {
        $entity->setName($dto->getName());
        $entity->setType($dto->getType());
        $entity->setDimension($dto->getDimension());

        return $entity;
    }

    public function patchUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface
    {
        if (null !== $dto->getName()) {
            $entity->setName($dto->getName());
        }

        if (null !== $dto->getType()) {
            $entity->setType($dto->getType());
        }

        if (null !== $dto->getDimension()) {
            $entity->setDimension($dto->getDimension());
        }

        return $entity;
    }
}
