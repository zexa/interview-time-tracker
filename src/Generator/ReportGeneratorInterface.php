<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\PublicTask;
use App\Entity\ReportParameters;

interface ReportGeneratorInterface
{
    /**
     * @param PublicTask[] $tasks
     * @param ReportParameters $reportParameters
     * @return File
     */
    public function generate(array $tasks, ReportParameters $reportParameters): File;
}
