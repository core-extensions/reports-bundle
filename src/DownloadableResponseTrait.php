<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

trait DownloadableResponseTrait
{
    public function buildDownloadableResponse(string $content, string $filename): Response
    {
        // X-Accel-Redirect is better ?
        // BinaryResponse ?

        $response = new Response($content);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}