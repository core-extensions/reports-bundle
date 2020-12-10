<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

/**
 * Default resolver
 */
class BaseFilenameResolver implements ReportFilenameResolverInterface
{
    public function resolveFilename(ReportInterface $report): string
    {
        $rendererConfiguration = $report->getRendererConfiguration();

        $filename = $rendererConfiguration['filename'] ?? null;

        if (null === $filename) {
            $filename = $report->getName() ?? 'report';
        }

        /**
         * @var null|string
         */
        $format = $report->getRendererConfiguration()['format'] ?? null;

        return $filename.($format ? '.'.$format : '');
    }
}