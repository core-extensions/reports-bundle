<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

class BaseFilenameResolver implements ReportFilenameResolverInterface
{
    public function resolveFilename(ReportInterface $report): string
    {
        $rendererConfiguration = $report->getRendererConfiguration();

        if ($rendererConfiguration['filename']) {
            return $rendererConfiguration['filename'];
        }

        /**
         * @var null|string
         */
        $format = $report->getRendererConfiguration()['format'] ?? null;

        return ($report->getName() ?? 'report').($format ? '.'.$format : '');
    }
}