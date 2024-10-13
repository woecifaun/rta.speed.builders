<?php

namespace App\Entity\Speedbuilding;

use App\Repository\Speedbuilding\RecordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
#[ORM\Table(name: 'speedbuilding_record')]
class Record
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $originalEmailAddress = null;

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    // Speedbuilding time is stored as seconds.milliseconds
    #[ORM\Column(type: Types::FLOAT)]
    private ?float $time = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $PostDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $attempt = null;

    /* helpers for form */
    /* Cause I couldn’t make the form DataMapper works */

    private $hours;
    private $minutes;
    private $seconds;
    private $milliseconds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getOriginalEmailAddress(): ?string
    {
        return $this->originalEmailAddress;
    }

    public function setOriginalEmailAddress(string $originalEmailAddress): static
    {
        $this->originalEmailAddress = $originalEmailAddress;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function setTime(float $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getPostDate(): ?\DateTimeImmutable
    {
        return $this->PostDate;
    }

    public function setPostDate(\DateTimeImmutable $PostDate): static
    {
        $this->PostDate = $PostDate;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAttempt(): ?int
    {
        return $this->attempt;
    }

    public function setAttempt(?int $attempt): static
    {
        $this->attempt = $attempt;

        return $this;
    }

    public function getHours(): int
    {
        return $this->hours;
    }

    public function setHours(int $hours)
    {
        $this->hours = $hours;

        return $this;
    }

    public function getMinutes(): int
    {
        $total_minutes = intval($this->time / 60);
        return $total_minutes % 60;
    }

    public function setMinutes(int $minutes)
    {
        $this->minutes = $minutes;

        return $this;
    }

    public function getSeconds(): int
    {
        return intval($this->time % 60);
    }

    public function setSeconds(int $seconds)
    {
        $this->seconds = $seconds;

        return $this;
    }

    public function getMilliseconds(): int
    {
        if (is_null($this->time)) {
            return 0;
        }

        $decimal = sscanf($this->time, '%d.%d')[1];

        return intval($decimal);
    }

    public function setMilliseconds(int $milliseconds)
    {
        $this->milliseconds = $milliseconds;

        return $this;
    }

    public function timeToHisv()
    {
        // hours
        $this->hours = intval($this->time / 3600);

        // minutes
        $total_minutes = intval($this->time / 60);
        $this->minutes = $total_minutes % 60;

        // seconds
        $this->seconds = intval($this->time % 60);

        // milliseconds
        if (is_null($this->time)) {
            $this->milliseconds = 0;
        } else {
            $milliseconds = sscanf($this->time, '%d.%d')[1];
            $this->milliseconds = intval($decimal);

        }
    }

    public function HisvToTime()
    {
        $this->time =
            $this->hours * 3600 +
            $this->minutes * 60 +
            $this->seconds +
            floatval("." . $this->milliseconds);
    }
}
