<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\PublicTask;
use App\Entity\ReportParameters;
use App\Exception\ReportGenerationException;

interface ReportGeneratorInterface
{
    /**
     * @param PublicTask[] $publicTasks
     * @param ReportParameters $reportParameters
     * @return File
     * @throws ReportGenerationException
     */
    public function generate(array $publicTasks, ReportParameters $reportParameters): File;

    public function getSupportedFormat(): string;
}
