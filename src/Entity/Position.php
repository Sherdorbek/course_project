<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Ignore]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, AttributeCv>
     */
    #[ORM\ManyToMany(targetEntity: AttributeCv::class)]
    private Collection $attributes;

    /**
     * @var Collection<int, Cv>
     */
    #[ORM\OneToMany(targetEntity: Cv::class, mappedBy: 'position', orphanRemoval: true)]
    #[Ignore]
    private Collection $cvs;

    #[ORM\Column]
    private ?int $projectNumber = null;

    #[ORM\Column(length: 255)]
    #[Ignore]
    private ?string $accessToken = null;


    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->cvs = new ArrayCollection();
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, AttributeCv>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(AttributeCv $atttribute): static
    {
        if (!$this->attributes->contains($atttribute)) {
            $this->attributes->add($atttribute);
        }

        return $this;
    }

    public function removeAttribute(AttributeCv $atttribute): static
    {
        $this->attributes->removeElement($atttribute);

        return $this;
    }

    /**
     * @return Collection<int, Cv>
     */
    public function getCvs(): Collection
    {
        return $this->cvs;
    }

    public function addCv(Cv $cv): static
    {
        if (!$this->cvs->contains($cv)) {
            $this->cvs->add($cv);
            $cv->setPosition($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): static
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getPosition() === $this) {
                $cv->setPosition(null);
            }
        }

        return $this;
    }

    public function getProjectNumber(): ?int
    {
        return $this->projectNumber;
    }

    public function setProjectNumber(int $projectNumber): static
    {
        $this->projectNumber = $projectNumber;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }


    #[Groups(['position:read'])]
    #[SerializedName('users')]
    public function getCvsUsersId(): ?array
    {
        $users= [];
        foreach ($this->cvs as $cv) {
            array_push($users,$cv->getUser()->getId());
        }
        return $users;
    }
}
