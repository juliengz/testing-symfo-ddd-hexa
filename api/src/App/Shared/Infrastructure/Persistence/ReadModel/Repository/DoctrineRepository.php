<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\ReadModel\Repository;

use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\EntityResult;
use Doctrine\ORM\QueryBuilder;
use Throwable;

abstract class DoctrineRepository
{
    const OBJECT_CLASS = null; 

    protected EntityRepository $repository;

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(static::OBJECT_CLASS);
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

    /**
     * @return mixed
     *
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    protected function oneOrException(QueryBuilder $queryBuilder, int $hydration = AbstractQuery::HYDRATE_OBJECT)
    {
        $model = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult($hydration);

        if (null === $model) {
            throw new NotFoundException();
        }

        return $model;
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
