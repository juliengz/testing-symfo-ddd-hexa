<?php

declare(strict_types=1);

namespace Tests\App\Shared\Application;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

abstract class ApplicationTest extends KernelTestCase
{
    private ?CommandBusInterface $commandBus;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->commandBus = $this->service(CommandBusInterface::class);
    }

    /**
     * @throws Throwable
     */
    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }

    /**
     * @return object|null
     */
    protected function service(string $serviceId)
    {
        return self::getContainer()->get($serviceId);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->commandBus = null;
        $this->queryBus = null;
    }
}
