<?php

namespace LaravelForminertia\Commands;

use Illuminate\Console\Command;

class LaravelForminertiaCommand extends Command
{
    public $signature = 'laravel-forminertia';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
