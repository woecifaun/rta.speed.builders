<?php

namespace App\Entity\User;

use App\Repository\User\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('username', message: 'username.duplicate')]
#[UniqueEntity('emailAddress', message: 'email.duplicate' )]
class User implements PasswordAuthenticatedUserInterface
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Regex(pattern: '/^[a-z0-9]{3,99}$/', message: 'username.invalid')]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $emailAddress = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 8, minMessage: 'Password must be at least 8 characters.')]
    #[Assert\Regex(pattern: '/(?=.*[A-Z])/', message: 'Password must contain at least one uppercase letter.')]
    #[Assert\Regex(pattern: '/(?=.*[a-z])/', message: 'Password must contain at least one lowercase letter.')]
    #[Assert\Regex(pattern: '/(?=.*\d)/', message: 'Password must contain at least one digit.')]
    #[Assert\Regex(pattern: '/(?=.*\W)/', message: 'Password must contain at least one special character.')]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registeredOn = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $displayName = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): static
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRegisteredOn(): ?\DateTimeImmutable
    {
        return $this->resisteredOn;
    }

    public function setRegisteredOn(\DateTimeImmutable $registeredOn): static
    {
        $this->registeredOn = $registeredOn;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
