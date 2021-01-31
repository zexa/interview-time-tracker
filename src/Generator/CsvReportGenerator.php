<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\PublicTask;
use App\Entity\ReportParameters;
use App\Serializer\PublicTaskSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class CsvReportGenerator implements ReportGeneratorInterface
{
    const FORMAT_CSV = 'csv';
    const MIME_TYPE_CSV = 'text/csv';

    private PublicTaskSerializer $publicTaskSerializer;
    private EntityManagerInterface $entityManager;
    private string $savePath;
    private FileNameGenerator $fileNameGenerator;

    public function __construct(
        PublicTaskSerializer $publicTaskSerializer,
        EntityManagerInterface $entityManager,
        string $savePath,
        FileNameGenerator $fileNameGenerator
    ) {
        $this->publicTaskSerializer = $publicTaskSerializer;
        $this->entityManager = $entityManager;
        $this->savePath = $savePath;
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
        $offset = 0;
        $filePath = $this->fileNameGenerator->generateWithPath($this->savePath, $reportParameters, $offset);
        while (file_exists($filePath)) {
            $offset++;
            $filePath = $this->fileNameGenerator->generateWithPath($this->savePath, $reportParameters, $offset);
        }

        // TODO: Maybe I should think of a way to make sure this would be forwards compatible?
        // That is, if I were to add more fields in the future, would the csv fields be in the same order?
        $fileResource = fopen($filePath, 'w');

        $file = (new File())
            ->setName($this->fileNameGenerator->generate($reportParameters, $offset))
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
