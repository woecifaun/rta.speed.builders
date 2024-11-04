<?php

namespace App\Entity\Speedbuilding;

use App\Entity\User\User;
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
    private ?string $videoUrl = null;

    // Used in case of anonymous post
    // If a user signs up with the same email address, all records will be linked to user
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originalEmailAddress = null;

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    // Speedbuilding time is stored as seconds.milliseconds
    #[ORM\Column(type: Types::FLOAT)]
    private ?float $time = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $postDate = null;

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

    #[ORM\Column(length: 255)]
    private ?string $videoPlatform = null;

    #[ORM\Column(length: 255)]
    private ?string $videoId = null;

    #[ORM\ManyToOne(inversedBy: 'records')]
    private ?User $speedbuilder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(string $videoUrl): static
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function getOriginalEmailAddress(): ?string
    {
        return $this->originalEmailAddress;
    }

    public function setOriginalEmailAddress(?string $originalEmailAddress): static
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
        return $this->postDate;
    }

    public function setPostDate(\DateTimeImmutable $postDate): static
    {
        $this->postDate = $postDate;

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

        $milliseconds = sscanf($this->time, '%d.%d')[1];

        return intval($milliseconds);
    }

    public function setMilliseconds(int $milliseconds)
    {
        $this->milliseconds = $milliseconds;

        return $this;
    }

    public function timeToHisv(): static
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
            $this->milliseconds = intval($milliseconds);

        }

        return $this;
    }

    public function HisvToTime(): static
    {
        $this->time =
            $this->hours * 3600 +
            $this->minutes * 60 +
            $this->seconds +
            floatval("." . $this->milliseconds);

        return $this;
    }

    public function formattedTime(): string
    {
        $this->timeToHisv();

        if ($this->hours) {
            return
                sprintf('%02d', $this->hours) . 'h ' .
                sprintf('%02d', $this->minutes) . 'm ' .
                sprintf('%02d', $this->seconds) . 's ' .
                sprintf('%03d', $this->milliseconds). 'ms';
        }

        if ($this->minutes) {
            return
                sprintf('%02d', $this->minutes) . 'm ' .
                sprintf('%02d', $this->seconds) . 's ' .
                sprintf('%03d', $this->milliseconds). 'ms';
        }

        return
            sprintf('%02d', $this->seconds) . 's ' .
            sprintf('%03d', $this->milliseconds). 'ms';
    }

    public function getVideoPlatform(): ?string
    {
        return $this->videoPlatform;
    }

    public function setVideoPlatform(string $videoPlatform): static
    {
        $this->videoPlatform = $videoPlatform;

        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId): static
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function getSpeedbuilder(): ?User
    {
        return $this->speedbuilder;
    }

    public function setSpeedbuilder(?User $speedbuilder): static
    {
        $this->speedbuilder = $speedbuilder;

        return $this;
    }
}
