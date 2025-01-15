<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchArticlesJob;
use App\Services\ArticleProviders\NewsApi\NewsApiArticlesProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to fetch articles from News API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        new FetchArticlesJob(App::get(NewsApiArticlesProvider::class))->handle();
    }
}
