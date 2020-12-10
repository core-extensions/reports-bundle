<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

final class ReportDefinition
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $fetcher;
    /**
     * @var string
     */
    private $renderer;

    /**
     * ReportInfo constructor.
     * @param string $name
     * @param string $fetcher
     * @param string $renderer
     */
    public function __construct(string $name, string $fetcher, string $renderer)
    {
        $this->name = $name;
        $this->fetcher = $fetcher;
        $this->renderer = $renderer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFetcher(): string
    {
        return $this->fetcher;
    }

    /**
     * @return string
     */
    public function getRenderer(): string
    {
        return $this->renderer;
    }
}
