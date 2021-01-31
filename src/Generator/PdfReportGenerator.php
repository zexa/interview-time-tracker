<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\File;
use App\Entity\ReportParameters;
use App\Exception\ReportGenerationException;
use App\Factory\FileFactory;
use Dompdf\Dompdf;
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

    public function __construct(
        FileNameGenerator $fileNameGenerator,
        FileFactory $fileFactory,
        Environment $twigEnvironment,
        string $pdfTemplate,
        Dompdf $dompdf
    ) {
        $this->fileNameGenerator = $fileNameGenerator;
        $this->fileFactory = $fileFactory;
        $this->twigEnvironment = $twigEnvironment;
        $this->pdfTemplate = $pdfTemplate;
        $this->dompdf = $dompdf;
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
                    'publicTasks' => $publicTasks
                ]
            );
        } catch (LoaderError | RuntimeError | SyntaxError $exception) {
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
