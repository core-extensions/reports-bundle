<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle\Controller;

use CoreExtensions\ReportsBundle\Exception\InvalidArgumentException;
use CoreExtensions\ReportsBundle\ReportManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Executes report in sync mode and returns result immediately.
 */
class ExecuteReportAction
{
    /**
     * @var ReportManagerInterface
     */
    private $reportManager;

    public function __construct(ReportManagerInterface $reportManager)
    {
        $this->reportManager = $reportManager;
    }

    public function __invoke(Request $request): Response
    {
        [$reportType, $reportId, $reportOptions, $format] = $this->readRequest($request);

        $report = $this->reportManager->create(
            $reportType,
            $reportId,
            $reportOptions,
            $format ? ['format' => $format] : null
        );

        // return result immediately
        return $this->reportManager->execute($report);
    }

    private function readRequest(Request $request): array
    {
        if (!$reportType = $request->get('reportType')) {
            throw new InvalidArgumentException('Param "reportType" not found');
        }

        if (!$reportId = $request->get('reportId')) {
            throw new InvalidArgumentException('Param "reportId" not found');
        }

        $dataFetchingOptions = json_decode($request->get('dataOptions'), true);
        $renderOptions = json_decode($request->get('renderOptions'), true);

        return [$reportType, $reportId, $dataFetchingOptions, $renderOptions];
    }
}
