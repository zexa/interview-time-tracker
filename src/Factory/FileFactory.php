<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\File;
use App\Entity\ReportParameters;
use App\Generator\FileNameGenerator;
use Doctrine\ORM\EntityManagerInterface;

class FileFactory
{
    private FileNameGenerator $fileNameGenerator;
    private EntityManagerInterface $entityManager;

    public function __construct(
        FileNameGenerator $fileNameGenerator,
        EntityManagerInterface $entityManager
    ) {
        $this->fileNameGenerator = $fileNameGenerator;
        $this->entityManager = $entityManager;
    }

    public function createFromReportParameters(
        ReportParameters $reportParameters,
        string $filePath,
        string $mimeType
    ): File {
        $this->fileNameGenerator->generateBackendFilepath();
        $file = (new File())
            ->setName($this->fileNameGenerator->generateFrontendFilename($reportParameters))
            ->setPath($filePath)
            ->setOwner($reportParameters->getUser())
            ->setSize((string)(int)filesize($filePath))
            ->setMimeType($mimeType)
        ;
        $this->entityManager->persist($file);
        $this->entityManager->flush();

        return $file;
    }
}
