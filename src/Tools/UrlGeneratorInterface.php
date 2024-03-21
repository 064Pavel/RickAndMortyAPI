<?php

declare(strict_types=1);

namespace App\Tools;

interface UrlGeneratorInterface
{
    public function generateUrls(array $items, string $entityUrl): array;

    public function getCurrentUrl(int $id, string $entityUrl): string;
}
