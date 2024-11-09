<?php

namespace App\Entity\User;

use App\Entity\Speedbuilding\Category;
use App\Entity\Speedbuilding\Record;
use App\Repository\User\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('username', message: 'username.duplicate')]
#[UniqueEntity('emailAddress', message: 'email.duplicate' )]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

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

    /**
     * @var Collection<int, Record>
     */
    #[ORM\OneToMany(targetEntity: Record::class, mappedBy: 'speedbuilder')]
    private Collection $records;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $country = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: 'createdBy')]
    private Collection $categories;

    public function __construct()
    {
        $this->records = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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
        return $this->registeredOn;
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

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->emailAddress;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Record>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(Record $record): static
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setSpeedbuilder($this);
        }

        return $this;
    }

    public function removeRecord(Record $record): static
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getSpeedbuilder() === $this) {
                $record->setSpeedbuilder(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCreatedBy() === $this) {
                $category->setCreatedBy(null);
            }
        }

        return $this;
    }
}
