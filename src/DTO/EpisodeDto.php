<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EpisodeDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'Your name must be at least {{ limit }} characters long',
            maxMessage: 'Your name cannot be longer than {{ limit }} characters',
        )]
        private ?string $name,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Your air date must be at least {{ limit }} characters long',
            maxMessage: 'Your air date cannot be longer than {{ limit }} characters',
        )]
        private ?string $air_date,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Your episode must be at least {{ limit }} characters long',
            maxMessage: 'Your episode cannot be longer than {{ limit }} characters',
        )]
        private ?string $episode,

        #[Assert\NotBlank]
        #[Assert\Type('int')]
        #[Assert\Range(
            min: 0,
            max: 99999999,
            notInRangeMessage: 'Views must be between {{ min }} and {{ max }}',
        )]
        private ?int $views,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getAirDate(): ?string
    {
        return $this->air_date;
    }

    public function getEpisode(): ?string
    {
        return $this->episode;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }
}
