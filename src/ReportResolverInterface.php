<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

/**
 * Resolves fetcher and renderer classes by reportKey.
 */
interface ReportResolverInterface
{
    public function resolveReport(
        string $reportType,
        ?array $fetcherConfiguration,
        ?array $rendererConfiguration
    ): ReportDefinition;
}
