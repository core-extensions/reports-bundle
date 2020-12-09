<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

interface ReportSimpleTableResultInterface
{
    /**
     * @return array
     */
    public function getHeaderColumns(): array;

    /**
     * @return array
     */
    public function getRows(): array;
}
