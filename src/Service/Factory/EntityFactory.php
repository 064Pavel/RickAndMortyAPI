<?php

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;
use ReflectionClass;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class EntityFactory
{
    abstract public function createEntityFromDto(DtoInterface $dto): EntityInterface;
    abstract public function putUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface;

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