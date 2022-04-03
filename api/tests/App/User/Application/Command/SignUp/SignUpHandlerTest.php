<?php

declare(strict_types=1);

namespace Tests\App\User\Application\Command\SignUp;

use App\User\Application\Command\SignUp\SignUpCommand;
use Assert\InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTest;

class SignUpHandlerTest extends ApplicationTest
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function should_throw_error_if_password_is_invalid(): void
    {
        $uuid = Uuid::uuid4();
        $email = 'asd@asd.asd';

        $this->expectException(InvalidArgumentException::class);
        $command = new SignUpCommand($uuid->toString(), $email, 'valid');
        $this->handle($command);

    }
}
