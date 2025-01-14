<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repository;

use App\Repository\CategoryRepositoryEloquent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class CategoryRepositoryEloquentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testFindOrCreateByCode(): void
    {
        /** @var CategoryRepositoryEloquent $repo */
        $repo = $this->app->get(CategoryRepositoryEloquent::class);
        $category = $repo->findOrCreateByCode('test');
        self::assertEquals('test', $category->code);
        self::assertDatabaseHas('categories', ['code' => 'test']);
    }
}
