<?php

declare(strict_types=1);

use App\Console\Commands\FetchArticlesCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(FetchArticlesCommand::class)->everyMinute();
