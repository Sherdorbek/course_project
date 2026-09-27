<?php

namespace App\Entity;

use App\Enum\UserRoleEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', enumType: UserRoleEnum::class)]
    private UserRoleEnum $role;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookId = null;

    /**
     * @var Collection<int, Cv>
     */
    #[ORM\OneToMany(targetEntity: Cv::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $cvs;

    /**
     * @var Collection<int, UserAttribute>
     */
    #[ORM\OneToMany(targetEntity: UserAttribute::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userAttributes;

    #[ORM\Column]
    private ?bool $profileSetUp = null;

    public function __construct()
    {
        $this->cvs = new ArrayCollection();
        $this->userAttributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRole(): UserRoleEnum
    {

        return $this->role;
    }
    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function setRole(UserRoleEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): static
    {
        $this->facebookId = $facebookId;

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
            $cv->setUser($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): static
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getUser() === $this) {
                $cv->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAttribute>
     */
    public function getUserAttributes(): Collection
    {
        return $this->userAttributes;
    }

    public function addUserAttribute(UserAttribute $userAttribute): static
    {
        if (!$this->userAttributes->contains($userAttribute)) {
            $this->userAttributes->add($userAttribute);
            $userAttribute->setUser($this);
        }

        return $this;
    }

    public function removeUserAttribute(UserAttribute $userAttribute): static
    {
        if ($this->userAttributes->removeElement($userAttribute)) {
            // set the owning side to null (unless already changed)
            if ($userAttribute->getUser() === $this) {
                $userAttribute->setUser(null);
            }
        }

        return $this;
    }

    public function isProfileSetUp(): ?bool
    {
        return $this->profileSetUp;
    }

    public function setProfileSetUp(bool $profileSetUp): static
    {
        $this->profileSetUp = $profileSetUp;

        return $this;
    }
}
