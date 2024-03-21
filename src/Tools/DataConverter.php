<?php

declare(strict_types=1);

namespace App\Tools;

class DataConverter implements DataConverterInterface
{
    public function convertToString(array &$data, array $fields): void
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = strval($data[$field]);
            }
        }
    }
}
