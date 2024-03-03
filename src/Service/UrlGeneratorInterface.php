<?php

namespace App\Service;

interface UrlGeneratorInterface
{
    public function generateUrls(array $items, string $entityUrl): array;
    public function getCurrentUrl(int $id, string $entityUrl): string;
}