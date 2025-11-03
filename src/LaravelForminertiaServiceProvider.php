<?php

namespace LaravelForminertia;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use LaravelForminertia\Commands\LaravelForminertiaCommand;

class LaravelForminertiaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-forminertia')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_forminertia_table')
            ->hasCommand(LaravelForminertiaCommand::class);
    }
}
