<?php

declare(strict_types=1);

namespace App\Generator;

use App\Calculator\DateIntervalCalculator;
use App\Entity\File;
use App\Entity\ReportParameters;
use App\Exception\ReportGenerationException;
use App\Factory\FileFactory;
use Dompdf\Dompdf;
use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class PdfReportGenerator implements ReportGeneratorInterface
{
    const FORMAT_PDF = 'pdf';
    const MIME_TYPE_PDF = 'application/pdf';

    private FileNameGenerator $fileNameGenerator;
    private FileFactory $fileFactory;
    private Environment $twigEnvironment;
    private string $pdfTemplate;
    private Dompdf $dompdf;
    private DateIntervalCalculator $dateIntervalCalculator;

    public function __construct(
        FileNameGenerator $fileNameGenerator,
        FileFactory $fileFactory,
        Environment $twigEnvironment,
        string $pdfTemplate,
        Dompdf $dompdf,
        DateIntervalCalculator $dateIntervalCalculator
    ) {
        $this->fileNameGenerator = $fileNameGenerator;
        $this->fileFactory = $fileFactory;
        $this->twigEnvironment = $twigEnvironment;
        $this->pdfTemplate = $pdfTemplate;
        $this->dompdf = $dompdf;
        $this->dateIntervalCalculator = $dateIntervalCalculator;
    }

    /**
     * @inheritDoc
     */
    public function generate(array $publicTasks, ReportParameters $reportParameters): File
    {
        try {
            $content = $this->twigEnvironment->render(
                $this->pdfTemplate,
                [
                    'publicTasks' => $publicTasks,
                    'totalTimeSpent' => $this->dateIntervalCalculator->sumFromPublicTasks($publicTasks),
                ]
            );
        } catch (LoaderError | RuntimeError | SyntaxError | Exception $exception) {
            throw new ReportGenerationException($exception->getMessage());
        }

        $this->dompdf->loadHtml($content);
        $this->dompdf->render();
        $fileName = $this->fileNameGenerator->generateBackendFilepath($reportParameters);
        file_put_contents(
            $fileName,
            $this->dompdf->output()
        );

        return $this->fileFactory->createFromReportParameters(
            $reportParameters,
            $fileName,
            self::MIME_TYPE_PDF
        );
    }

    public function getSupportedFormat(): string
    {
        return self::FORMAT_PDF;
    }
}
