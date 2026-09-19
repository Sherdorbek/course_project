<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $public = null;

    /**
     * @var Collection<int, PositionAttr>
     */
    #[ORM\OneToMany(targetEntity: PositionAttr::class, mappedBy: 'position', orphanRemoval: true)]
    private Collection $positionAttrs;

    public function __construct()
    {
        $this->positionAttrs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @return Collection<int, PositionAttr>
     */
    public function getPositionAttrs(): Collection
    {
        return $this->positionAttrs;
    }

    public function addPositionAttr(PositionAttr $positionAttr): static
    {
        if (!$this->positionAttrs->contains($positionAttr)) {
            $this->positionAttrs->add($positionAttr);
            $positionAttr->setPosition($this);
        }

        return $this;
    }

    public function removePositionAttr(PositionAttr $positionAttr): static
    {
        if ($this->positionAttrs->removeElement($positionAttr)) {
            // set the owning side to null (unless already changed)
            if ($positionAttr->getPosition() === $this) {
                $positionAttr->setPosition(null);
            }
        }

        return $this;
    }
}
