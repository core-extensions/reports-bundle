<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

interface ReportRepositoryInterface
{
    public function createNewReport(): ReportInterface;

    public function findOneById($reportId): ?ReportInterface;

    public function persist(ReportInterface $report): void;
}
