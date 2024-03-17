<?php

namespace App\Repository;

interface EntityRepositoryInterface
{
    public function findByFilters(array $filters): array;
    public function getTotalEntityCount(): int;
    public function getTotalEntityCountWithFilters(array $filters): int;
}