<?php

namespace LaravelForminertia\LaravelForminertia\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelForminertia\LaravelForminertia\LaravelForminertia
 */
class LaravelForminertia extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelForminertia\LaravelForminertia\LaravelForminertia::class;
    }
}
