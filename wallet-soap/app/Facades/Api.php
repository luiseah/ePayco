<?php

namespace App\Facades;

use App\Services\Responses\ApiResponse;
use Illuminate\Support\Facades\Facade;

class Api extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return ApiResponse::class;
    }
}
