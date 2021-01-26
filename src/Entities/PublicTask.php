<?php

declare(strict_types=1);

namespace App\Entities;

use DateInterval;
use DateTimeInterface;

class PublicTask
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var DateTimeInterface
     */
    private $date;
    /**
     * @var DateInterval
     */
    private $duration;

    public function __construct(
        string $title,
        string $comment,
        DateTimeInterface $date,
        DateInterval $duration
    ) {
        $this->title = $title;
        $this->comment = $comment;
        $this->date = $date;
        $this->duration = $duration;
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
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param DateTimeInterface $date
     */
    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
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
     */
    public function setDuration(DateInterval $duration): void
    {
        $this->duration = $duration;
    }

}
