<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\User\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\DateTime;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Domain\ValueObject\Credentials;
use Ramsey\Uuid\UuidInterface;

class User
{
    private UuidInterface $uuid;

    private Email $email;

    private Credentials $credentials;

    private ?DateTime $createdAt;

    private ?DateTime $updatedAt;

    public function __construct(
        UuidInterface $uuid,
        Credentials $credentials,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $uuid,
        Credentials $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self {
        $uniqueEmailSpecification->isUnique($credentials->email);

        return new self(
            $uuid,
            $credentials,
            DateTime::now(),
            DateTime::now(),
        );
    }
}
