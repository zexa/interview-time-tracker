<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\ReportParameters;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class FileNameGenerator
{
    const FILE_NAME_FORMAT = '%s_%s_%s_%s.%s';

    /**
     * @var DateTimeNormalizer
     */
    private $dateTimeNormalizer;

    public function __construct(
        DateTimeNormalizer $dateTimeNormalizer
    ) {
        $this->dateTimeNormalizer = $dateTimeNormalizer;
    }

    /**
     * @param ReportParameters $reportParameters
     * @param int $offset
     * @return string
     */
    public function generate(ReportParameters $reportParameters, int $offset): string
    {
        return sprintf(
            self::FILE_NAME_FORMAT,
            $reportParameters->getUser()->getEmail(),
            $reportParameters->getDateFrom(),
            $reportParameters->getDateTo(),
            $offset,
            $reportParameters->getFormat()
        );
    }

    /**
     * @param string $path
     * @param ReportParameters $reportParameters
     * @param int $offset
     * @return string
     */
    public function generateWithPath(string $path, ReportParameters $reportParameters, int $offset): string
    {
        return $path . '/' . $this->generate($reportParameters, $offset);
    }
}
