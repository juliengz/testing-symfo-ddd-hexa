<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\WriteModel\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Throwable;

abstract class DoctrineRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $model
     */
    public function register($model): void
    {
        $this->entityManager->persist($model);
        $this->apply();
    }

    public function apply(): void
    {
        $this->entityManager->flush();
    }

    public function isHealthy(): bool
    {
        $connection = $this->entityManager->getConnection();

        try {
            $dummySelectSQL = $connection->getDatabasePlatform()->getDummySelectSQL();
            $connection->executeQuery($dummySelectSQL);

            return true;
        } catch (Throwable $exception) {
            $connection->close();

            return false;
        }
    }
}
