<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle\Exception;

class ReportRendererNotFoundException extends RuntimeException
{
    public static function withName(string $key): self
    {
        return new static(
            \sprintf(
                'Report renderer with key "%s" not found.',
                $key
            )
        );

    }

    public static function withReportType(string $reportType): self
    {
        return new static(
            \sprintf(
                'Report renderer for report "%s" not found.',
                $reportType
            )
        );
    }
}
