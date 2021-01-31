<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\ReportParameters;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class FileNameGenerator
{
    const FILE_NAME_FORMAT = 'report_%s_%s_%s.%s';

    private string $savePath;
    private UuidGenerator $uuidGenerator;

    public function __construct(
        string $savePath,
        UuidGenerator $uuidGenerator
    ) {
        $this->savePath = $savePath;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param ReportParameters $reportParameters
     * @return string
     */
    public function generateFrontendFilename(ReportParameters $reportParameters): string
    {
        return sprintf(
            self::FILE_NAME_FORMAT,
            $reportParameters->getUser()->getEmail(),
            $reportParameters->getDateFrom(),
            $reportParameters->getDateTo(),
            $reportParameters->getFormat()
        );
    }

    public function generateBackendFilename(ReportParameters $reportParameters): string
    {
        return $this->uuidGenerator->generateString();
    }

    /**
     * @param ReportParameters $reportParameters
     * @return string
     */
    public function generateBackendFilepath(ReportParameters $reportParameters): string
    {
        return $this->savePath . '/' . $this->generateBackendFilename($reportParameters);
    }
}
