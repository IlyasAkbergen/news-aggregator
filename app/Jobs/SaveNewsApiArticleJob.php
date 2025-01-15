<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ArticleProviders\NewsApi\CategoryFinder;
use App\Services\ArticleProviders\NewsApi\Dto\ArticleResponseDto;
use App\Services\ArticleProviders\NewsApi\SourceFinder;
use DateTimeImmutable;
use DateTimeInterface;
use Domain\Entity\Article;
use Domain\Entity\Author;
use Domain\Entity\Category;
use Domain\Entity\Source;
use Domain\Enum\ArticleProviderCode;
use Domain\Exception\DomainException;
use Domain\Exception\ExternalException;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Repository\AuthorRepositoryInterface;
use Domain\ValueObject\FullName;
use Domain\ValueObject\Url;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Ramsey\Uuid\Uuid;

class SaveNewsApiArticleJob implements ShouldQueue
{
    use Queueable;

    private AuthorRepositoryInterface $authorRepository;
    private SourceFinder $sourceFinder;
    private CategoryFinder $categoryFinder;

    public function __construct(
        public readonly ArticleResponseDto $articleResponseDto,
    ) {
    }

    /**
     * @throws DomainException
     * @throws ExternalException
     */
    public function handle(
        ArticleRepositoryInterface $articleRepository,
        AuthorRepositoryInterface $authorRepository,
        SourceFinder $sourceFinder,
        CategoryFinder $categoryFinder,
    ): void {
        $this->authorRepository = $authorRepository;
        $this->sourceFinder = $sourceFinder;
        $this->categoryFinder = $categoryFinder;

        $article = $articleRepository->findByUrl(new Url($this->articleResponseDto->url));
        if ($article !== null) {
            return;
        }

        $source = $this->getSource();
        $article = new Article(
            id: Uuid::uuid4(),
            title: $this->articleResponseDto->title,
            description: $this->articleResponseDto->description,
            content: $this->articleResponseDto->content,
            url: new Url($this->articleResponseDto->url),
            imageUrl: $this->articleResponseDto->urlToImage !== null
                ? new Url($this->articleResponseDto->urlToImage)
                : null,
            author: $this->getAuthor(),
            source: $source,
            category: $this->getCategory($source),
            publishedAt: DateTimeImmutable::createFromFormat(
                DateTimeInterface::RFC3339,
                $this->articleResponseDto->publishedAt,
            ),
            providerCode: ArticleProviderCode::NEWS_API,
        );

        $articleRepository->save($article);
    }

    /**
     * @throws DomainException
     */
    private function getAuthor(): ?Author
    {
        if ($this->articleResponseDto->author === null) {
            return null;
        }

        return $this->authorRepository->findOrCreateByFullName(
            FullName::fromString($this->articleResponseDto->author),
        );
    }

    /**
     * @throws ExternalException
     */
    private function getSource(): Source
    {
        return $this->sourceFinder->findSource($this->articleResponseDto->source->id);
    }

    /**
     * @throws ExternalException
     */
    private function getCategory(Source $source): Category
    {
        return $this->categoryFinder->findBySource($source);
    }
}
