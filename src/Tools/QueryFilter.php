<?php

declare(strict_types=1);

namespace App\Tools;

use Symfony\Component\HttpFoundation\Request;

class QueryFilter implements QueryFilterInterface
{
    public function filter(Request $request, array $allowedFilters): array
    {
        $filters = [];
        foreach ($allowedFilters as $filter) {
            $value = $request->query->get($filter);
            if (null !== $value) {
                $filters[$filter] = $value;
            }
        }

        return $filters;
    }
}
