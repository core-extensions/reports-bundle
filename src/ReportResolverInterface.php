<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

/**
 * Resolves fetcher and renderer classes by reportKey.
 */
interface ReportResolverInterface
{
    public function resolveName(string $reportType, ?array $fetcherConfiguration, ?array $rendererConfiguration): string;

    public function resolveRender(string $reportType, ?array $rendererConfiguration): string;

    public function resolveFetcher(string $reportType, ?array $fetcherConfiguration): string;
}
