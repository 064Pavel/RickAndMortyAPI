<?php

namespace App\Tools;

use Symfony\Component\HttpFoundation\Request;

interface QueryFilterInterface
{
    public function filter(Request $request, array $allowedFilters): array;
}