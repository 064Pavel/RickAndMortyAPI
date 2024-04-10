<?php

declare(strict_types=1);

namespace App\Service\Factory;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;

interface EntityFactoryInterface
{
    public function createEntityFromDto(DtoInterface $dto): EntityInterface;

    public function putUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface;

    public function patchUpdateEntityFromDto(EntityInterface $entity, DtoInterface $dto): EntityInterface;
}
