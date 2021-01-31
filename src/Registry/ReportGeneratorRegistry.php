<?php

declare(strict_types=1);

namespace App\Registry;

use App\Generator\CsvReportGenerator;
use App\Generator\ReportGeneratorInterface;
use InvalidArgumentException;

class ReportGeneratorRegistry
{
    /**
     * @var ReportGeneratorInterface[]
     */
    private array $registry = [];

    public function register(ReportGeneratorInterface $reportGenerator): self
    {
        if (isset($this->registry[$reportGenerator->getSupportedFormat()])) {
            throw new InvalidArgumentException(sprintf(
                'Cannot add ReportGeneratorInterface with supported format %s because it is already registered',
                $reportGenerator->getSupportedFormat()
            ));
        }

        $this->registry[$reportGenerator->getSupportedFormat()] = $reportGenerator;

        return $this;
    }

    /**
     * @param string $format
     * @return ReportGeneratorInterface
     * @throws InvalidArgumentException
     */
    public function getReportGenerator(string $format): ReportGeneratorInterface
    {
        if (!isset($this->registry[$format])) {
            throw new InvalidArgumentException(sprintf(
                'No report generator with format %s is registered',
                $format
            ));
        }

        return $this->registry[$format];
    }

    /**
     * @return string[]
     */
    public function getFormats(): array
    {
        return array_keys($this->registry);
    }
}
