<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ReportParameters;
use App\Entity\User;
use App\Registry\ReportGeneratorRegistry;
use App\Repository\TaskRepository;
use App\Transformer\TaskTransformer;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class ReportController extends AbstractController
{
    const REPORT_QUERY_FORMAT = 'format';
    const REPORT_QUERY_DATE_FROM = 'date_from';
    const REPORT_QUERY_DATE_TO = 'date_to';

    private TaskTransformer $taskTransformer;
    private TaskRepository $taskRepository;
    private ReportGeneratorRegistry $reportGeneratorRegistry;
    private DateTimeNormalizer $dateTimeNormalizer;

    public function __construct(
        TaskTransformer $taskTransformer,
        TaskRepository $taskRepository,
        ReportGeneratorRegistry $reportGeneratorRegistry,
        DateTimeNormalizer $dateTimeNormalizer
    ) {
        $this->taskTransformer = $taskTransformer;
        $this->taskRepository = $taskRepository;
        $this->reportGeneratorRegistry = $reportGeneratorRegistry;
        $this->dateTimeNormalizer = $dateTimeNormalizer;
    }

    /**
     * @Route("/report", name="report")
     * @param Request $request
     * @param UserInterface|User $user
     * @return Response
     */
    public function getReport(Request $request, UserInterface $user): Response
    {
        try {
            $reportParameters = new ReportParameters(
                $request->query->get(self::REPORT_QUERY_DATE_FROM),
                $request->query->get(self::REPORT_QUERY_DATE_TO),
                $request->query->get(self::REPORT_QUERY_FORMAT),
                $user
            );
        } catch (ExceptionInterface $exception) {
            return new Response('failed building report parameters: ' . $exception->getMessage(), 400);
        }

        $tasks = $this->taskRepository->findByUserAndDateRange(
            $user,
            $reportParameters->getDateFrom(),
            $reportParameters->getDateTo()
        );

        foreach ($tasks as $task) {
            $publicTasks[] = $this->taskTransformer->intoPublicTask($task);
        }

        try {
            $file = $this->reportGeneratorRegistry
                ->getReportGenerator($reportParameters->getFormat())
                ->generate($publicTasks ?? [], $reportParameters)
            ;
        } catch (InvalidArgumentException $exception) {
            return new Response('failed generating report: ' . $exception->getMessage(), 400);
        }

        return new Response(
            file_get_contents($file->getPath()),
            200,
            [
                'Content-Encoding' => 'none',
                'Content-Type' => $file->getMimeType(),
                'Content-Disposition' => 'attachment; filename="' . $file->getName() . '"',
                'Content-Description' => 'File Transfer',
            ]
        );
    }
}
