<?php

declare(strict_types=1);

namespace App\Entity;

use DateInterval;
use DateTimeInterface;

class PublicTask
{
    private string $title;
    /**
     * @var string[]
     */
    private array $comments;
    private DateTimeInterface $date;
    private DateInterval $duration;
    private ?string $hash;

    public function __construct(
        string $title,
        array $comments,
        DateTimeInterface $date,
        DateInterval $duration,
        ?string $hash
    ) {
        $this->title = $title;
        $this->comments = $comments;
        $this->date = $date;
        $this->duration = $duration;
        $this->hash = $hash;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param string[] $comments
     * @return PublicTask
     */
    public function setComments(array $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): DateInterval
    {
        return $this->duration;
    }

    public function setDuration(DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
