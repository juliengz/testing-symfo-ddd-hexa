<?php

declare(strict_types=1);

namespace App\Shared\Domain\Specification;

interface SpecificationInterface
{
    /**
     * @param mixed $value
     */
    public function isSatisfiedBy(mixed $value): bool;
}
