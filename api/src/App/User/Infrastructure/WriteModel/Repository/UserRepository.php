<?php

namespace App\User\Infrastructure\WriteModel\Repository;

use App\Shared\Infrastructure\Persistence\WriteModel\Repository\DoctrineRepository;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;

class UserRepository extends DoctrineRepository implements UserRepositoryInterface
{
    public function save(User $user): void
    {
        $this->register($user);
    }
}
