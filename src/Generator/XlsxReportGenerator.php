<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\ReportParameters;
use App\Exception\ReportGenerationException;
use App\Factory\FileFactory;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception as SpreadsheetWriterException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxReportGenerator implements ReportGeneratorInterface
{
    const FORMAT_XLSX = 'xlsx';
    const MIME_TYPE_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    private FileNameGenerator $fileNameGenerator;
    private FileFactory $fileFactory;

    public function __construct(
        FileNameGenerator $fileNameGenerator,
        FileFactory $fileFactory
    ) {
        $this->fileNameGenerator = $fileNameGenerator;
        $this->fileFactory = $fileFactory;
    }

    /**
     * @inheritDoc
     */
    public function generate(array $publicTasks, ReportParameters $reportParameters): File
    {
        $spreadsheet = new Spreadsheet();

        try {
            $worksheet = $spreadsheet->setActiveSheetIndex(0);
        } catch (Exception $exception) {
            throw new ReportGenerationException($exception->getMessage());
        }

        $worksheet
            ->setCellValueByColumnAndRow(1, 1, 'Name')
            ->setCellValueByColumnAndRow(2, 1, 'Time')
            ->setCellValueByColumnAndRow(3, 1, 'Duration')
        ;

        $row = 2;
        foreach ($publicTasks as $publicTask) {
            $worksheet
                ->setCellValueByColumnAndRow(1, $row, $publicTask->getTitle())
                ->setCellValueByColumnAndRow(
                    2,
                    $row,
                    $publicTask->getDate()->format('Y-m-d\TH:i:sP')
                )
                ->setCellValueByColumnAndRow(
                    3,
                    $row,
                    $publicTask->getDuration()->format('%rP%yY%mM%dDT%hH%iM%sS')
                )
            ;

            $row++;
        }

        $worksheet
            ->setCellValueByColumnAndRow(1, $row+2, 'Count')
            ->setCellValueByColumnAndRow(2, $row+2, count($publicTasks))
        ;

        $filePath = $this->fileNameGenerator->generateBackendFilepath($reportParameters);

        try {
            (new Xlsx($spreadsheet))
                ->save($filePath);
        } catch (SpreadsheetWriterException $exception) {
            throw new ReportGenerationException($exception->getMessage());
        }

        return $this->fileFactory->createFromReportParameters(
            $reportParameters,
            $filePath,
            self::MIME_TYPE_XLSX
        );
    }

    public function getSupportedFormat(): string
    {
        return self::FORMAT_XLSX;
    }
}
