<?php

declare(strict_types=1);

namespace CoreExtensions\ReportsBundle\Bridge\Doctrine;

use CoreExtensions\ReportsBundle\Exception\RuntimeException;
use CoreExtensions\ReportsBundle\ReportInterface;
use CoreExtensions\ReportsBundle\ReportRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

class ReportRepository implements ReportRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $reportClass;

    /**
     * @param string $reportClass
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        string $reportClass,
        ManagerRegistry $managerRegistry
    ) {
        $this->reportClass = $reportClass;

        $this->entityManager = $managerRegistry->getManagerForClass($reportClass);
        if (null === $this->entityManager) {
            throw new RuntimeException(
                sprintf('EntityManager for "%s" is not found. Did you register your report entity?', $reportClass)
            );
        }
    }

    public function createNewReport(): ReportInterface
    {
        return new $this->reportClass;
    }

    /**
     * @param mixed $reportId
     * @return ReportInterface|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function findOneById($reportId): ?ReportInterface
    {
        /**
         * @var ReportInterface $res
         */
        $res = $this->entityManager->find($this->reportClass, $reportId);

        return $res;
    }

    /**
     * @param ReportInterface $report
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persist(ReportInterface $report): void
    {
        $this->entityManager->persist($report);
        $this->entityManager->flush($report);
    }
}
