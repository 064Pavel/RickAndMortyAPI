<?php

namespace App\Entity;

use App\Repository\EpisodeCharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeCharacterRepository::class)]
class EpisodeCharacter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Episode::class)]
    private ?int $episode = null;
    #[ORM\ManyToOne(targetEntity: Character::class)]
    private ?int $character = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisode(): ?int
    {
        return $this->episode;
    }

    public function setEpisode(?int $episode): void
    {
        $this->episode = $episode;
    }

    public function getCharacter(): ?int
    {
        return $this->character;
    }

    public function setCharacter(?int $character): void
    {
        $this->character = $character;
    }
}
