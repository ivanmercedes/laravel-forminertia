<?php

namespace LaravelForminertia;

use LaravelForminertia\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasCommand(InstallCommand::class);
    }

    public function registeringPackage(): void
    {
        $this->app->singleton('forminertia', function () {
            return new FormInertia;
        });
    }
}
