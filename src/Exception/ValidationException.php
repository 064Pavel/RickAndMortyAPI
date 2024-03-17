<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

class ValidationException extends Exception
{
    private array $validationErrors;

    public function __construct(array $validationErrors, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $this->validationErrors = $validationErrors;

        if (empty($message)) {
            $message = 'Validation failed';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }


}
