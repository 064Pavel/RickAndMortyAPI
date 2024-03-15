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

    public function formatInfo(array $data, int $count, array $options = []): array
    {
        $page = $options['page'] ?? 1;
        $entityName = $options['entityName'] ?? '';
        $limit = $options['limit'] ?? 10;
        $queryParameters = $options['query'] ?? [];

        $pages = ceil($count / $limit);
        $next = ($page < $pages) ? $this->getUrl($page + 1, $entityName, $queryParameters) : null;
        $prev = ($page > 1) ? $this->getUrl($page - 1, $entityName, $queryParameters) : null;

        return [
            'count' => $count,
            'pages' => $pages,
            'next' => $next,
            'prev' => $prev,
        ];
    }


    private function getUrl(int $page, string $entityName, array $queryParameters = []): string
    {
        $url = $_ENV['API_ROUTE'];
        $url .= "$entityName/?page=" . $page;

        if (!empty($queryParameters)) {
            $url .= '&' . http_build_query($queryParameters);
        }

        return $url;
    }

}
