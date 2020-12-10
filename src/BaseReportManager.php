<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle;

use CoreExtensions\ReportsBundle\Exception\ReportDataFetchingException;
use CoreExtensions\ReportsBundle\Exception\ReportFetcherNotFoundException;
use CoreExtensions\ReportsBundle\Exception\ReportRendererNotFoundException;
use CoreExtensions\ReportsBundle\Exception\ReportRenderingException;
use CoreExtensions\ReportsBundle\Exception\RuntimeException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceProviderInterface;

class BaseReportManager implements ReportManagerInterface
{
    /**
     * @var ReportResolverInterface
     */
    private $reportResolver;

    /**
     * @var ServiceProviderInterface
     */
    private $serviceLocator;

    /**
     * @var ReportRepositoryInterface
     */
    private $reportRepository;

    /**
     * @param ReportResolverInterface $reportResolver
     * @param ContainerInterface $reportLocator
     * @param ReportRepositoryInterface $reportRepository
     */
    public function __construct(
        ReportResolverInterface $reportResolver,
        ContainerInterface $reportLocator,
        ReportRepositoryInterface $reportRepository
    ) {
        $this->reportResolver = $reportResolver;
        $this->serviceLocator = $reportLocator;
        $this->reportRepository = $reportRepository;
    }

    public function create(
        string $reportType,
        $reportId,
        ?array $dataFetcherConfiguration,
        ?array $rendererConfiguration
    ): ReportInterface {
        // security layer
        $definition = $this->reportResolver->resolveReport(
            $reportType,
            $dataFetcherConfiguration,
            $rendererConfiguration
        );

        $dataFetcher = $definition->getFetcher();
        $renderer = $definition->getRenderer();
        $reportName = $definition->getName();

        $report = $this->reportRepository->createNewReport();
        $report->setReportId($reportId);
        $report->setName($reportName);
        $report->setDataFetcher($dataFetcher);
        $report->setDataFetcherConfiguration($dataFetcherConfiguration);
        $report->setRenderer($renderer);
        $report->setRendererConfiguration($rendererConfiguration);
        $report->setStatus(ReportStatus::PENDING);

        return $report;
    }

    /**
     * @param ReportInterface $report
     * @return Response
     */
    public function execute(ReportInterface $report): Response
    {
        try {
            $data = $this->fetchData($report);

            $report->setData($data);
            $report->setStatus(ReportStatus::READY);

            $this->reportRepository->persist($report);

            return $this->render($report);
        } catch (RuntimeException $e) {
            $report->setData(['error' => $e->getLogMessage()]);
            $report->setStatus(ReportStatus::ERROR);
            $this->reportRepository->persist($report);

            throw $e;
        }
    }

    public function queue(ReportInterface $report): void
    {
        // todo: run via messenger
    }

    /**
     * @param ReportInterface $report
     * @return mixed|void
     * @throws ReportFetcherNotFoundException
     * @throws ReportDataFetchingException
     */
    public function fetchData(ReportInterface $report)
    {
        $fetcherName = $report->getDataFetcher();

        if (!$this->serviceLocator->has($fetcherName)) {
            throw ReportFetcherNotFoundException::withName($fetcherName);
        }

        /**
         * @var ReportDataFetcherInterface $fetcher
         */
        $fetcher = $this->serviceLocator->get($fetcherName);

        try {
            return $fetcher->fetch($report);
        } catch (\Exception $e) {
            throw new ReportDataFetchingException(
                'Data fetching failed',
                RuntimeException::DATA_FETCHING_FAILED,
                $e
            );
        }
    }

    /**
     * @param ReportInterface $report
     * @return Response
     * @throws ReportRendererNotFoundException
     */
    public function render(ReportInterface $report): Response
    {
        $rendererName = $report->getRenderer();

        if (!$this->serviceLocator->has($rendererName)) {
            throw ReportRendererNotFoundException::withName($rendererName);
        }

        /**
         * @var ReportRendererInterface $renderer
         */
        $renderer = $this->serviceLocator->get($rendererName);

        try {
            return $renderer->render($report);
        } catch (\Exception $e) {
            throw new ReportRenderingException(
                'Rendering failed',
                RuntimeException::RENDERING_FAILED,
                $e
            );
        }
    }
}
