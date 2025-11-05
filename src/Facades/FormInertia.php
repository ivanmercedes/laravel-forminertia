<?php

namespace LaravelForminertia\Facades;

use Illuminate\Support\Facades\Facade;

class FormInertia extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'forminertia';
    }
}
