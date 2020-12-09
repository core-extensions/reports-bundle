<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

final class ReportDefinition
{
    /**
     * @var string
     */
    private $type;
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
     * @param string $type
     * @param string $name
     * @param string $fetcher
     * @param string $renderer
     */
    public function __construct(string $type, string $name, string $fetcher, string $renderer)
    {
        $this->type = $type;
        $this->name = $name;
        $this->fetcher = $fetcher;
        $this->renderer = $renderer;
    }
}
