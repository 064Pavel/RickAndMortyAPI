<?php

declare(strict_types=1);

namespace App\Tools;

class UrlGenerator implements UrlGeneratorInterface
{
    public function generateUrls(array $items, string $entityUrl): array
    {
        return array_map(function ($item) use ($entityUrl) {
            return "https://rickandmortyapi.com/api/{$entityUrl}/{$item->getId()}";
        }, $items);
    }

    public function getCurrentUrl(int $id, string $entityUrl): string
    {
        return "https://rickandmortyapi.com/api/{$entityUrl}/{$id}";
    }
}
