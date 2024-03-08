<?php

declare(strict_types=1);

namespace App\Tools;

interface PaginatorInterface
{
    public function paginate(array $data, array $options = []): array;
    public function formatInfo(array $data, array $options = [], int $count): array;
}
