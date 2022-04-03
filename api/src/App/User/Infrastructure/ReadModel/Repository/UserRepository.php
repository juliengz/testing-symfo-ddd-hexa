<?php

namespace App\User\Infrastructure\ReadModel\Repository;

use App\Shared\Infrastructure\Persistence\ReadModel\Repository\DoctrineRepository;
use App\User\Domain\Repository\CheckUserByEmailInterface;
use App\User\Domain\User;
use App\User\Domain\ValueObject\Email;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\UuidInterface;

class UserRepository extends DoctrineRepository implements CheckUserByEmailInterface
{
    const OBJECT_CLASS = User::class;

    public function save(User $user): void
    {
        $this->register($user);
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        $objectRepository = $this->entityManager->getRepository(User::class);
        return $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());
    }

    /**
     * @throws NonUniqueResultException
     */
    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->getUserByEmailQueryBuilder($email)
            ->select('user.uuid')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
        ;

        return $userId['uuid'] ?? null;
    }
}
