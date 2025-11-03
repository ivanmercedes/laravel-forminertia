<?php

namespace LaravelForminertia\Commands;

use RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;

#[AsCommand(name: 'forminertia:install')]
class InstallCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'forminertia:install {--force : Overwrite existing files}';
    protected $description = 'Install FormInertia resources';

    public function handle(): int
    {
        $this->info('Installing resources...');

        $source = __DIR__ . '/../../stubs/react';
        $destination = resource_path('js/components');

        if (! File::exists($source)) {
            $this->error('Source folder not found.');
            return self::FAILURE;
        }

        File::ensureDirectoryExists($destination);

        if ($this->option('force')) {
            File::copyDirectory($source, $destination);
        } else {
            $this->copyWithoutOverwriting($source, $destination);
        }

        $textareaPath = resource_path('js/components/ui/textarea.tsx');

        if (! file_exists($textareaPath)) {
            $this->newLine();
            $this->warn('⚠️ The "Textarea" component from ShadCN/UI is required.');

            if ($this->confirm('Do you want to install it now?', true)) {
                $this->components->info('Installing ShadCN Textarea component...');

                if (file_exists(base_path('pnpm-lock.yaml'))) {
                    $this->runCommands(['pnpm dlx shadcn@latest add textarea']);
                } elseif (file_exists(base_path('yarn.lock'))) {
                    $this->runCommands(['yarn dlx shadcn@latest add textarea']);
                } elseif (file_exists(base_path('bun.lock')) || file_exists(base_path('bun.lockb'))) {
                    $this->runCommands(['bunx --bun shadcn@latest add textarea']);
                } else {
                    $this->runCommands(['npx shadcn@latest add textarea']);
                }

                $this->components->info('✅ Textarea component installed successfully.');
            } else {
                $this->newLine();
                $this->line('You can install it later with the following command:');
                $this->line('<fg=yellow>npx shadcn@latest add textarea</>');
            }
        } else {
            $this->components->info('✅ Textarea component already installed.');
        }

        return self::SUCCESS;
    }

    private function copyWithoutOverwriting(string $source, string $destination): void
    {
        foreach (File::allFiles($source) as $file) {
            $target = $destination . '/' . $file->getRelativePathname();

            File::ensureDirectoryExists(dirname($target));

            if (! File::exists($target)) {
                File::copy($file->getPathname(), $target);
            }
        }
    }


    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    ' . $line);
        });
    }
}
