<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    public function getPageAndLimit(Request $request): array
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        return [$page, $limit];
    }
}
