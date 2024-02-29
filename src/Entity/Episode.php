<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $air_date = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $episode = null;

    #[ORM\Column(nullable: false)]
    private ?int $views = null;

    #[ORM\ManyToMany(targetEntity: Character::class)]
    #[ORM\JoinTable(name: 'episode_character')]
    #[ORM\JoinColumn(name: 'episode_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'character_id', referencedColumnName: 'id')]
    private ArrayCollection $characters;

    public function getCharacters(): ArrayCollection
    {
        return $this->characters;
    }

    public function setCharacters(ArrayCollection $characters): void
    {
        $this->characters = $characters;
    }

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $created = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAirDate(): ?string
    {
        return $this->air_date;
    }

    public function setAirDate(string $air_date): static
    {
        $this->air_date = $air_date;

        return $this;
    }

    public function getEpisode(): ?string
    {
        return $this->episode;
    }

    public function setEpisode(string $episode): static
    {
        $this->episode = $episode;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }
}
