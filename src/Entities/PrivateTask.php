<?php

declare(strict_types=1);

namespace App\Entities;

use DateInterval;
use DateTimeInterface;

class PrivateTask
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var PrivateUser
     */
    private $user;
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
    private $timeSpent;

    public function __construct(
        string $id,
        PrivateUser $user,
        string $title,
        string $comment,
        DateTimeInterface $date,
        DateInterval $timeSpent
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->title = $title;
        $this->comment = $comment;
        $this->date = $date;
        $this->timeSpent = $timeSpent;

    }

    /**
     * @return PrivateUser
     */
    public function getUser(): PrivateUser
    {
        return $this->user;
    }

    /**
     * @param PrivateUser $user
     */
    public function setUser(PrivateUser $user): void
    {
        $this->user = $user;
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
    public function getTimeSpent(): DateInterval
    {
        return $this->timeSpent;
    }

    /**
     * @param DateInterval $timeSpent
     */
    public function setTimeSpent(DateInterval $timeSpent): void
    {
        $this->timeSpent = $timeSpent;
    }
}
