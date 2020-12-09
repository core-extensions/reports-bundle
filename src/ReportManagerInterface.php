<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

use Symfony\Component\HttpFoundation\Response;

interface ReportManagerInterface
{
    /**
     * Creates report.
     *
     * @param string $reportType
     * @param mixed $reportId
     * @param string $reportName
     * @param array|null $dataFetcherConfiguration
     * @param array|null $rendererConfiguration
     * @return ReportInterface
     */
    public function create(
        string $reportType,
        $reportId,
        string $reportName,
        ?array $dataFetcherConfiguration,
        ?array $rendererConfiguration
    ): ReportInterface;

    /**
     * Executes report and return response. For using in sync mode.
     *
     * @param ReportInterface $report
     * @return Response
     */
    public function execute(ReportInterface $report): Response;

    /**
     * Adds report to the queue for async executing.
     *
     * TODO: if we use messenger it will increase the coupling with our system
     *
     * @param ReportInterface $report
     */
    public function queue(ReportInterface $report): void;

    /**
     * Fetches data for report using defined dataFetcher.
     *
     * @param ReportInterface $report
     * @return mixed
     */
    public function fetchData(ReportInterface $report);

    /**
     * Renders result.
     * The results can be different, for example xls file, json-response, word file.
     *
     * @param ReportInterface $report
     * @return Response
     */
    public function render(ReportInterface $report): Response;
}
