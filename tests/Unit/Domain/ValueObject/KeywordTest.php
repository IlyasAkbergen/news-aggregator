<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Domain\Exception\DomainException;
use Domain\ValueObject\Keyword;
use PHPUnit\Framework\TestCase;

class KeywordTest extends TestCase
{
    public function testValidKeyword(): void
    {
        $keyword = new Keyword('keyword');
        self::assertSame('keyword', $keyword->value);
    }

    public function testInvalidKeyword(): void
    {
        self::expectException(DomainException::class);
        new Keyword('');
    }
}
