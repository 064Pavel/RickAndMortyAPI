<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class LocationDto implements DtoInterface
{
    public function __construct(
        #[SerializedName('name')]
        #[Assert\NotBlank]
        #[Assert\Type(['type' => 'string', 'message' => 'The value {{ value }} is not a valid string.'])]
        #[Assert\Length(
            min: 2,
            max: 200,
            minMessage: 'Your type must be at least {{ limit }} characters long',
            maxMessage: 'Your type cannot be longer than {{ limit }} characters',
        )]
        private ?string $name,
        #[SerializedName('type')]
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(
            min: 2,
            max: 200,
            minMessage: 'Your type must be at least {{ limit }} characters long',
            maxMessage: 'Your type cannot be longer than {{ limit }} characters',
        )]
        private ?string $type,
        #[SerializedName('dimension')]
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(
            min: 2,
            max: 200,
            minMessage: 'Your dimension must be at least {{ limit }} characters long',
            maxMessage: 'Your dimension cannot be longer than {{ limit }} characters',
        )]
        private ?string $dimension,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }
}
