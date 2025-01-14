<?php

declare(strict_types=1);

namespace App\Services\ArticleProviders\NewsApi;

use Domain\Entity\Source;
use Domain\Exception\ExternalException;
use Domain\Repository\SourceRepositoryInterface;

readonly class SourceFinder
{
    public function __construct(
        private SourceRepositoryInterface $sourceRepository,
        private NewsApiArticlesProvider $articlesProvider,
    ) {
    }

    /**
     * @throws ExternalException
     */
    public function findSource(string $sourceCode): Source
    {
        $source = $this->sourceRepository->findByCode($sourceCode);

        if ($source instanceof Source) {
            return $source;
        }

        $sources = $this->articlesProvider->getSources();

        foreach ($sources as $source) {
            if ($source->code === $sourceCode) {
                $this->sourceRepository->save($source);

                return $source;
            }
        }

        throw new ExternalException(sprintf('Could not find source with code %s', $sourceCode));
    }
}
