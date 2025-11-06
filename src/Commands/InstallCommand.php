<?php

namespace LaravelForminertia\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'forminertia:install')]
class InstallCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'forminertia:install {--stack=react : The frontend stack to install (react or vue)} {--force : Overwrite existing files}';

    protected $description = 'Install FormInertia resources';

    public function handle(): int
    {
        $stack = $this->option('stack');

        if (! in_array($stack, ['react', 'vue'])) {
            $this->error('Invalid stack. Please choose either "react" or "vue".');

            return self::FAILURE;
        }

        $this->info("Installing {$stack} resources...");

        $source = __DIR__."/../../stubs/{$stack}";
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

        $this->installTextareaComponent($stack);

        $this->components->info("✅ FormInertia {$stack} components installed successfully.");

        if ($stack === 'vue') {
            $this->displayVueUsageExample();
        } else {
            $this->displayReactUsageExample();
        }

        return self::SUCCESS;
    }

    private function installTextareaComponent(string $stack): void
    {
        $extension = $stack === 'vue' ? 'vue' : 'tsx';
        $textareaPath = resource_path("js/components/ui/textarea.{$extension}");

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
    }

    private function displayVueUsageExample(): void
    {
        $this->newLine();
        $this->line('<fg=green>Vue Usage Example:</>');
        $this->line('');
        $this->line('<fg=yellow>In your Vue component:</>');
        $this->line('');
        $this->line('<template>');
        $this->line('  <div class="max-w-3xl mx-auto">');
        $this->line('    <FormBuilder');
        $this->line('      :form-schema="form"');
        $this->line('      :form="formData"');
        $this->line('      submit-label="Create User"');
        $this->line('    />');
        $this->line('  </div>');
        $this->line('</template>');
        $this->line('');
        $this->line('<script setup>');
        $this->line('import { useForm } from "@inertiajs/vue3"');
        $this->line('import FormBuilder from "@/components/forminertia/FormBuilder.vue"');
        $this->line('');
        $this->line('const props = defineProps(["form"])');
        $this->line('const formData = useForm({})');
        $this->line('</script>');
    }

    private function displayReactUsageExample(): void
    {
        $this->newLine();
        $this->line('<fg=green>React Usage Example:</>');
        $this->line('');
        $this->line('<fg=yellow>In your React component:</>');
        $this->line('');
        $this->line('import FormBuilder from "@/components/forminertia/form-builder";');
        $this->line('');
        $this->line('export default function Create({ form }) {');
        $this->line('  return (');
        $this->line('    <div className="max-w-3xl mx-auto">');
        $this->line('      <FormBuilder');
        $this->line('        formSchema={form}');
        $this->line('        form={store.form()}');
        $this->line('        submitLabel="Create User"');
        $this->line('      />');
        $this->line('    </div>');
        $this->line('  );');
        $this->line('}');
    }

    private function copyWithoutOverwriting(string $source, string $destination): void
    {
        foreach (File::allFiles($source) as $file) {
            $target = $destination.'/'.$file->getRelativePathname();

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
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}
