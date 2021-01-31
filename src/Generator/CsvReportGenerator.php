<?php

declare(strict_types=1);

namespace App\Generator;

use App\Calculator\DateIntervalCalculator;
use App\Entity\File;
use App\Entity\ReportParameters;
use App\Exception\ReportGenerationException;
use App\Factory\FileFactory;
use App\Serializer\PublicTaskSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CsvReportGenerator implements ReportGeneratorInterface
{
    const FORMAT_CSV = 'csv';
    const MIME_TYPE_CSV = 'text/csv';

    private PublicTaskSerializer $publicTaskSerializer;
    private EntityManagerInterface $entityManager;
    private FileNameGenerator $fileNameGenerator;
    private FileFactory $fileFactory;
    private DateIntervalCalculator $dateIntervalCalculator;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer,
        EntityManagerInterface $entityManager,
        FileNameGenerator $fileNameGenerator,
        FileFactory $fileFactory,
        DateIntervalCalculator $dateIntervalCalculator
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
        $this->entityManager = $entityManager;
        $this->fileNameGenerator = $fileNameGenerator;
        $this->fileFactory = $fileFactory;
        $this->dateIntervalCalculator = $dateIntervalCalculator;
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
        fputs($fileResource, "\nCount: " . sizeof($publicTasks));
        try {
            fputs(
                $fileResource,
                "\nTotal time spent: "
                . $this->dateIntervalCalculator
                    ->sumFromPublicTasks($publicTasks)
                    ->format('%rP%yY%mM%dDT%hH%iM%sS')
                . "\n"
            );
        } catch (Exception $exception) {
            throw new ReportGenerationException($exception->getMessage());
        }
        fclose($fileResource);

        return $this->fileFactory->createFromReportParameters(
            $reportParameters,
            $filePath,
            self::MIME_TYPE_CSV
        );
    }
}
