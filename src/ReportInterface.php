<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

interface ReportInterface
{
    public function getReportId();

    public function setReportId($reportId): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getRenderer(): string;

    public function setRenderer(string $renderer): void;

    public function getRendererConfiguration(): ?array;

    public function setRendererConfiguration(?array $rendererConfiguration): void;

    public function getDataFetcher(): string;

    public function setDataFetcher(string $dataFetcher): void;

    public function getDataFetcherConfiguration(): ?array;

    public function setDataFetcherConfiguration(?array $dataFetcherConfiguration): void;

    public function getData(): ?array;

    public function setData(?array $data): void;

    public function getStatus(): int;

    public function setStatus(int $status): void;
}
