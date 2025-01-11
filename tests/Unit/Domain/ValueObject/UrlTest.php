<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject;

use Domain\ValueObject\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testValidUrl(): void
    {
        $url = new Url('https://example.com');
        self::assertSame('https://example.com', $url->value);
    }

    public function testInvalidUrl(): void
    {
        self::expectException(\InvalidArgumentException::class);
        new Url('invalid-url');
    }
}
