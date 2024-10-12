<?php

namespace App\Service\Speedbuilding;

/**
 * Record time handle a speedbuilding time
 * - stored as float (seconds.milliseconds) in DB
 * - Seen as a HH:ii:ss.u in the form
 */
class Time
{
    // As it is save in DB
    private ?float $timestamp;

    private ?int $hours;

    private ?int $minutes;

    private ?int $seconds;

    private ?int $milliseconds;

    public function __construct(float $timestamp = null)
    {
        if ($timestamp < 0) {
            throw new TimeException('Invalid time! A Speedbuilding time cannot be less than zero.');
        }

        // dump($timestamp);
        // $seconds = intval($timestamp%60);dump($seconds);
        // $total_minutes = intval($timestamp/60);dump($total_minutes);
        // $minutes = $total_minutes%60;dump($minutes);
        // $hours = intval($total_minutes/60);dump($hours);
        // $whole = floor($timestamp);

        // $this->milliseconds
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(?int $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getMinutes(): ?int
    {
        return $this->minutes;
    }

    public function setMinutes(int $minutes): static
    {
        $this->minutes = $minutes;

        return $this;
    }

    public function getSeconds(): ?int
    {
        return $this->seconds;
    }

    public function setSeconds(int $seconds): static
    {
        $this->seconds = $seconds;

        return $this;
    }

    public function getMilliseconds(): ?int
    {
        return $this->milliseconds;
    }

    public function setMilliseconds(int $milliseconds): static
    {
        $this->milliseconds = $milliseconds;

        return $this;
    }

    public function getTimestamp()
    {
        $this->timestamp =
            $this->hours * 3600 +
            $this->minutes * 60 +
            $this->seconds +
            floatval("." . $this->milliseconds);

        return $this->timestamp;
    }
}
