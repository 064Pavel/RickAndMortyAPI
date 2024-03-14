<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Location;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CharacterDto
{
    #[Assert\NotBlank(message: 'Name cannot be blank')]
    #[Assert\Type(type: 'string', message: 'Name must be a string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Name cannot be longer than {{ limit }} characters',
    )]
    private ?string $name;

    #[Assert\NotBlank(message: 'Status cannot be blank')]
    #[Assert\Type(type: 'string', message: 'Status must be a string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Status cannot be longer than {{ limit }} characters',
    )]
    private ?string $status;

    #[Assert\NotBlank(message: 'Species cannot be blank')]
    #[Assert\Type(type: 'string', message: 'Species must be a string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Species cannot be longer than {{ limit }} characters',
    )]
    private ?string $species;

    #[Assert\Type(type: 'string', message: 'Type must be a string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Type cannot be longer than {{ limit }} characters',
    )]
    private ?string $type;

    #[Assert\NotBlank(message: 'Gender cannot be blank')]
    #[Assert\Type(type: 'string', message: 'Gender must be a string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Gender cannot be longer than {{ limit }} characters',
    )]
    private ?string $gender;

    #[Assert\NotBlank(message: 'Origin cannot be blank')]
    #[Assert\Type(Location::class)]
    #[SerializedName('origin')]
    private ?Location $origin;

    #[Assert\NotBlank(message: 'Location cannot be blank')]
    #[Assert\Type(Location::class)]
    #[SerializedName('location')]
    private ?Location $location;

    #[Assert\Type(type: '\DateTimeInterface', message: 'Created must be a valid date and time')]
    private ?DateTimeInterface $created;

    #[Assert\NotBlank(message: 'Image cannot be blank')]
    #[Assert\Type(type: 'string', message: 'Image must be a string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Image cannot be longer than {{ limit }} characters',
    )]
    private ?string $image;

    #[Assert\Valid]
    private ?array $episodes;

    public function __construct(
        ?string $name,
        ?string $status,
        ?string $species,
        ?string $type,
        ?string $gender,
        ?Location $origin,
        ?Location $location,
        ?DateTimeInterface $created,
        ?string $image,
        ?array $episodes,
    ) {
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->type = $type;
        $this->gender = $gender;
        $this->origin = $origin;
        $this->location = $location;
        $this->created = $created;
        $this->image = $image;
        $this->episodes = $episodes;
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

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(?string $species): void
    {
        $this->species = $species;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getOrigin(): ?Location
    {
        return $this->origin;
    }

    public function setOrigin(?Location $origin): void
    {
        $this->origin = $origin;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(?DateTimeInterface $created): void
    {
        $this->created = $created;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getEpisodes(): ?array
    {
        return $this->episodes;
    }

    public function setEpisodes(?array $episodes): void
    {
        $this->episodes = $episodes;
    }
}
