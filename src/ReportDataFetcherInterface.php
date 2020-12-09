<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

/**
 * Fetches result based on given report configuration.
 */
interface ReportDataFetcherInterface
{
    /**
     * Fetches result.
     *
     * @param ReportInterface $report
     * @return mixed
     */
    public function fetch(ReportInterface $report);
}
