<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Domain\Exception\DomainException;
use Domain\ValueObject\FullName;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{
    public function testValidFullName(): void
    {
        $fullName = new FullName('Iliyas', 'Akbergen');
        self::assertSame('Iliyas Akbergen', (string) $fullName);
    }

    public function testInvalidFullName(): void
    {
        self::expectException(DomainException::class);
        new FullName('Iliyas', '');
        self::expectException(DomainException::class);
        new FullName('', 'Akbergen');
    }
}
