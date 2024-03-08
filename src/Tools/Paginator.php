<?php

declare(strict_types=1);

namespace App\Tools;

class Paginator implements PaginatorInterface
{
    public function paginate(array $data, array $options = []): array
    {
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? 10;

        $offset = ($page - 1) * $limit;

        return array_slice($data, $offset, $limit);
    }

    public function formatInfo(array $data, array $options = [], int $count): array
    {
        $page = $options['page'] ?? 1;
        $entityName = $options['entityName'] ?? '';
        $limit = $options['limit'] ?? 10;

        $pages = ceil($count / $limit);
        $next = ($page < $pages) ? $this->getUrl($page + 1, $entityName) : null;
        $prev = ($page > 1) ? $this->getUrl($page - 1, $entityName) : null;

        return [
            'count' => $count,
            'pages' => $pages,
            'next' => $next,
            'prev' => $prev,
        ];
    }

    private function getUrl(int $page, string $entityName): string
    {
        $url = $_ENV['API_ROUTE'];
        $url .= "$entityName/?page=" . $page;

        return $url;
    }
}
