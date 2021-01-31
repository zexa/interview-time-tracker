<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\ReportParameters;
use App\Serializer\PublicTaskSerializer;
use Doctrine\ORM\EntityManagerInterface;

class CsvReportGenerator implements ReportGeneratorInterface
{
    const FORMAT_CSV = 'csv';
    const MIME_TYPE_CSV = 'text/csv';

    private PublicTaskSerializer $publicTaskSerializer;
    private EntityManagerInterface $entityManager;
    private FileNameGenerator $fileNameGenerator;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer,
        EntityManagerInterface $entityManager,
        FileNameGenerator $fileNameGenerator
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
        $this->entityManager = $entityManager;
        $this->fileNameGenerator = $fileNameGenerator;
    }

    public function getSupportedFormat(): string
    {
        return self::FORMAT_CSV;
    }

    /**
     * @inheritDoc
     */
    public function generate(array $tasks, ReportParameters $reportParameters): File
    {
        $filePath = $this->fileNameGenerator->generateBackendFilepath($reportParameters);
        $fileResource = fopen($filePath, 'w');
        $file = (new File())
            ->setName($this->fileNameGenerator->generateFrontendFilename($reportParameters))
            ->setPath($filePath)
            ->setOwner($reportParameters->getUser())
            ->setSize((string)(int)filesize($filePath))
            ->setMimeType(self::MIME_TYPE_CSV)
        ;

        $this->entityManager->persist($file);
        $this->entityManager->flush();

        fputs(
           $fileResource,
           $this->publicTaskSerializer->serializePublicTasks($tasks, PublicTaskSerializer::FORMAT_CSV)
        );
        fputs($fileResource, "\n" . sizeof($tasks));
        fclose($fileResource);

        return $file;
    }
}
