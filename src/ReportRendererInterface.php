<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

use Symfony\Component\HttpFoundation\Response;

/**
 * Generates response based on given report and result.
 */
interface ReportRendererInterface
{
    /**
     * Method returns Response that will be returned by controller.
     *
     * @param ReportInterface $report
     * @return Response
     */
    public function render(ReportInterface $report): Response;
}
