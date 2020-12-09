<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle\Exception;

class ReportFetcherNotFoundException extends RuntimeException
{
    public static function withName(string $key): self
    {
        return new static(
            \sprintf(
                'Report fetcher with key "%s" not found.',
                $key
            )
        );
    }

    public static function withReportType(string $reportType): self
    {
        return new static(
            \sprintf(
                'Report fetcher for report "%s" not found.',
                $reportType
            )
        );
    }
}
