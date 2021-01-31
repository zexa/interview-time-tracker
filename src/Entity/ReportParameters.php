<?php

declare(strict_types=1);

namespace App\Entity;

class ReportParameters
{
    /**
     * @var string
     */
    private $dateFrom;
    /**
     * @var string
     */
    private $dateTo;
    /**
     * @var string
     */
    private $format;
    /**
     * @var User
     */
    private $user;

    public function __construct(
        string $dateFrom,
        string $dateTo,
        string $format,
        User $user
    ) {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->format = $format;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    /**
     * @param string $dateFrom
     * @return ReportParameters
     */
    public function setDateFrom(string $dateFrom): self
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    /**
     * @param string $dateTo
     * @return ReportParameters
     */
    public function setDateTo(string $dateTo): self
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return ReportParameters
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ReportParameters
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
