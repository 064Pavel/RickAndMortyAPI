<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;
use App\Entity\Location;
use DateTimeImmutable;

class LocationFactory extends EntityFactory
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
}
