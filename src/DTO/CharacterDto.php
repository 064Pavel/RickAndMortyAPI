<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Location;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CharacterDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Name cannot be blank')]
        #[Assert\Type(type: 'string', message: 'Name must be a string')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Name cannot be longer than {{ limit }} characters',
        )]
        private ?string $name,

        #[Assert\NotBlank(message: 'Status cannot be blank')]
        #[Assert\Type(type: 'string', message: 'Status must be a string')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Status cannot be longer than {{ limit }} characters',
        )]
        private ?string $status,

        #[Assert\NotBlank(message: 'Species cannot be blank')]
        #[Assert\Type(type: 'string', message: 'Species must be a string')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Species cannot be longer than {{ limit }} characters',
        )]
        private ?string $species,

        #[Assert\Type(type: 'string', message: 'Type must be a string')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Type cannot be longer than {{ limit }} characters',
        )]
        private ?string $type,

        #[Assert\NotBlank(message: 'Gender cannot be blank')]
        #[Assert\Type(type: 'string', message: 'Gender must be a string')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Gender cannot be longer than {{ limit }} characters',
        )]
        #[Assert\Choice(
            choices: ['Male', 'Female', 'Unknown', 'Other'],
            message: 'Choose a valid gender',
        )]
        private ?string $gender,

        #[Assert\NotBlank(message: 'Origin cannot be blank')]
        #[Assert\Type(Location::class)]
        #[SerializedName('origin')]
        private ?Location $origin,

        #[Assert\NotBlank(message: 'Location cannot be blank')]
        #[Assert\Type(Location::class)]
        #[SerializedName('location')]
        private ?Location $location,

        #[Assert\Type(type: '\DateTimeInterface', message: 'Created must be a valid date and time')]
        private ?DateTimeInterface $created,

        #[Assert\NotBlank(message: 'Image cannot be blank')]
        #[Assert\Type(type: 'string', message: 'Image must be a string')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Image cannot be longer than {{ limit }} characters',
        )]
        private ?string $image,

        #[Assert\Valid]
        #[SerializedName('episode')]
        private ?array $episodes,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getOrigin(): ?Location
    {
        return $this->origin;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getEpisodes(): ?array
    {
        return $this->episodes;
    }
}
