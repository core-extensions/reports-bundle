<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

/**
 * Resolves fetcher and renderer classes by reportKey.
 */
interface ReportResolverInterface
{
    public function resolveRender(string $reportType): string;

    public function resolveFetcher(string $reportType): string;
}
