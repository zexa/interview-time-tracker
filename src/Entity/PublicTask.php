<?php

declare(strict_types=1);

namespace App\Entity;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;

class PublicTask
{
    private string $title;
    /**
     * @var string[]
     */
    private array $comments;
    /**
     * @var DateTimeImmutable
     */
    private $date;
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return PublicTask
     */
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
     * @param array $comments
     */
    public function setComments(array $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return PublicTask
     */
    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return DateInterval
     */
    public function getDuration(): DateInterval
    {
        return $this->duration;
    }

    /**
     * @param DateInterval $duration
     * @return PublicTask
     */
    public function setDuration(DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string|null $hash
     * @return PublicTask
     */
    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }
}
