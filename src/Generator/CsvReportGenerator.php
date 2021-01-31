<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\ReportParameters;
use App\Factory\FileFactory;
use App\Serializer\PublicTaskSerializer;
use Doctrine\ORM\EntityManagerInterface;

class CsvReportGenerator implements ReportGeneratorInterface
{
    const FORMAT_CSV = 'csv';
    const MIME_TYPE_CSV = 'text/csv';

    private PublicTaskSerializer $publicTaskSerializer;
    private EntityManagerInterface $entityManager;
    private FileNameGenerator $fileNameGenerator;
    private FileFactory $fileFactory;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer,
        EntityManagerInterface $entityManager,
        FileNameGenerator $fileNameGenerator,
        FileFactory $fileFactory
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
        $this->entityManager = $entityManager;
        $this->fileNameGenerator = $fileNameGenerator;
        $this->fileFactory = $fileFactory;
    }

    public function getSupportedFormat(): string
    {
        return self::FORMAT_CSV;
    }

    /**
     * @inheritDoc
     */
    public function generate(array $publicTasks, ReportParameters $reportParameters): File
    {
        $filePath = $this->fileNameGenerator->generateBackendFilepath($reportParameters);
        $fileResource = fopen($filePath, 'w');
        fputs(
           $fileResource,
           $this->publicTaskSerializer->serializePublicTasks($publicTasks, PublicTaskSerializer::FORMAT_CSV)
        );
        fputs($fileResource, "\n" . sizeof($publicTasks));
        fclose($fileResource);

        return $this->fileFactory->createFromReportParameters(
            $reportParameters,
            $filePath,
            self::MIME_TYPE_CSV
        );
    }
}
