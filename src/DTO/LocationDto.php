<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LocationDto
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your name cannot be longer than {{ limit }} characters',
    )]
    private ?string $name;
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: 'Your type must be at least {{ limit }} characters long',
        maxMessage: 'Your type cannot be longer than {{ limit }} characters',
    )]
    private ?string $type;
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 2,
        max: 200,
        minMessage: 'Your dimension must be at least {{ limit }} characters long',
        maxMessage: 'Your dimension cannot be longer than {{ limit }} characters',
    )]
    private ?string $dimension;

    public function __construct(?string $name, ?string $type, ?string $dimension)
    {
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(?string $dimension): void
    {
        $this->dimension = $dimension;
    }
}
