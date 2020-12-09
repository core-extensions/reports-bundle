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
        if (!$reportType = $request->get('type')) {
            throw new InvalidArgumentException('Param "type" not found');
        }

        if (!$reportId = $request->get('id')) {
            throw new InvalidArgumentException('Param "id" not found');
        }

        $reportOptions = json_decode($request->get('options'), true);

        $format = $request->get('format');

        return [$reportType, $reportId, $reportOptions, $format];
    }
}
