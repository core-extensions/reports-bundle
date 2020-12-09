<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle\Exception;

class RuntimeException extends \RuntimeException implements ReportExceptionInterface
{
    public const DATA_FETCHING_FAILED = 10;
    public const RENDERING_FAILED = 20;

    public function getLogMessage()
    {
        return sprintf(
            "%s. File: %s. Line: %d.\nTrace:\n%s\n...",
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
            $this->getShortTraceAsString(2)
        );
    }

    public function getShortTraceAsString($num = 1)
    {
        $trace = explode(PHP_EOL, $this->getTraceAsString());

        return implode(PHP_EOL, array_slice($trace, 0, $num ?: 1));
    }
}
