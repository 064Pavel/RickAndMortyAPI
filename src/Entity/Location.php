<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $dimension = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'origin')]
    private Collection $charactersOrigin;

    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'location')]
    private Collection $charactersLocation;

    public function __construct()
    {
        $this->charactersOrigin = new ArrayCollection();
        $this->charactersLocation = new ArrayCollection();
    }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(string $dimension): static
    {
        $this->dimension = $dimension;

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

    /**
     * @return Collection<int, Character>
     */
    public function getCharactersOrigin(): Collection
    {
        return $this->charactersOrigin;
    }

    public function addCharactersOrigin(Character $charactersOrigin): static
    {
        if (!$this->charactersOrigin->contains($charactersOrigin)) {
            $this->charactersOrigin->add($charactersOrigin);
            $charactersOrigin->setOrigin($this);
        }

        return $this;
    }

    public function removeCharactersOrigin(Character $charactersOrigin): static
    {
        if ($this->charactersOrigin->removeElement($charactersOrigin)) {
            // set the owning side to null (unless already changed)
            if ($charactersOrigin->getOrigin() === $this) {
                $charactersOrigin->setOrigin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharactersLocation(): Collection
    {
        return $this->charactersLocation;
    }

    public function addCharactersLocation(Character $charactersLocation): static
    {
        if (!$this->charactersLocation->contains($charactersLocation)) {
            $this->charactersLocation->add($charactersLocation);
            $charactersLocation->setLocation($this);
        }

        return $this;
    }

    public function removeCharactersLocation(Character $charactersLocation): static
    {
        if ($this->charactersLocation->removeElement($charactersLocation)) {
            // set the owning side to null (unless already changed)
            if ($charactersLocation->getLocation() === $this) {
                $charactersLocation->setLocation(null);
            }
        }

        return $this;
    }
}
