<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: '1.0.0',
        title: 'News Aggregator API',
    )
]
class BaseApiController extends Controller
{

}
