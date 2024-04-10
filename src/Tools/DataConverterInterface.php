<?php

declare(strict_types=1);

namespace App\Tools;

interface DataConverterInterface
{
    public function convertToString(array &$data, array $fields): void;
}
