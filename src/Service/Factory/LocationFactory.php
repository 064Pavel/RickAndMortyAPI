<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;
use App\Entity\Location;
use DateTimeImmutable;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $reflectionClass = new ReflectionClass($dto);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $value = $propertyAccessor->getValue($dto, $propertyName);

            if (null !== $value) {
                $propertyAccessor->setValue($entity, $propertyName, $value);
            }
        }

        return $entity;
    }
}
