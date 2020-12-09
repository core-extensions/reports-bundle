<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

/**
 * Resolves fetcher and renderer classes by reportKey.
 */
interface ReportFilenameResolverInterface
{
    public function resolveFilename(ReportInterface $report): string;
}
