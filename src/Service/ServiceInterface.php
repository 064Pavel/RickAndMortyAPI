<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\DtoInterface;
use App\Entity\EntityInterface;

interface ServiceInterface
{
    public function getEntities(int $page, int $limit, array $queries = []): array;

    public function getEntitiesByIds(int $page, int $limit, string $ids): array;

    public function getEntity(int $id): ?array;

    public function createEntity(DtoInterface $dto): array;

    public function putUpdateEntity(int $id, DtoInterface $dto): ?array;

    public function patchUpdateEntity(int $id, DtoInterface $dto): array;

    public function deleteEntity(int $id): bool;
}
