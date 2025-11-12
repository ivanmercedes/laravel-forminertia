<?php

namespace LaravelForminertia\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'forminertia:generate')]
class GenerateFormCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'forminertia:generate
                            {model : The model name or table name}
                            {--table= : Specify table name if different from model}
                            {--path= : Custom path for the generated form}
                            {--force : Overwrite existing form}
                            {--exclude= : Comma-separated list of columns to exclude}';

    protected $description = 'Generate a FormInertia form based on database table structure';

    protected array $excludedColumns = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'email_verified_at',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected array $fieldTypeMapping = [
        'string' => 'TextField',
        'text' => 'TextareaField',
        'integer' => 'TextField',
        'bigint' => 'TextField',
        'decimal' => 'TextField',
        'float' => 'TextField',
        'double' => 'TextField',
        'boolean' => 'CheckboxField',
        'date' => 'TextField',
        'datetime' => 'TextField',
        'timestamp' => 'TextField',
        'time' => 'TextField',
        'json' => 'TextareaField',
    ];

    public function handle(): int
    {
        $modelName = $this->argument('model');
        $tableName = $this->option('table') ?? Str::snake(Str::pluralStudly($modelName));

        if (! $this->tableExists($tableName)) {
            $this->error("Table '{$tableName}' does not exist.");

            return self::FAILURE;
        }

        $this->info("Analyzing table: {$tableName}");

        $columns = $this->getTableColumns($tableName);

        if (empty($columns)) {
            $this->error("No columns found in table '{$tableName}'.");

            return self::FAILURE;
        }

        $excludedColumns = $this->excludedColumns;
        if ($this->option('exclude')) {
            $additionalExclusions = explode(',', $this->option('exclude'));
            $excludedColumns = array_merge($excludedColumns, array_map('trim', $additionalExclusions));
        }

        $filteredColumns = array_filter($columns, function ($column) use ($excludedColumns) {
            return ! in_array($column['name'], $excludedColumns);
        });

        if (empty($filteredColumns)) {
            $this->error('No columns to generate after applying exclusions.');

            return self::FAILURE;
        }

        $formClass = Str::studly($modelName).'Form';
        $formPath = $this->getFormPath($formClass);

        if (File::exists($formPath) && ! $this->option('force')) {
            if (! $this->confirm("Form '{$formClass}' already exists. Do you want to overwrite it?")) {
                $this->info('Form generation cancelled.');

                return self::SUCCESS;
            }
        }

        $this->generateForm($formClass, $formPath, $filteredColumns, $modelName);

        $this->info("Form '{$formClass}' generated successfully");

        return self::SUCCESS;
    }

    protected function tableExists(string $tableName): bool
    {
        return DB::getSchemaBuilder()->hasTable($tableName);
    }

    protected function getTableColumns(string $tableName): array
    {
        $schema = DB::getSchemaBuilder();
        $columns = [];

        foreach ($schema->getColumnListing($tableName) as $columnName) {
            $columnType = $schema->getColumnType($tableName, $columnName);
            $columns[] = [
                'name' => $columnName,
                'type' => $columnType,
                'nullable' => ! $this->isColumnRequired($tableName, $columnName),
            ];
        }

        return $columns;
    }

    protected function isColumnRequired(string $tableName, string $columnName): bool
    {
        try {
            $connection = DB::connection();
            $database = $connection->getDatabaseName();

            $result = $connection->select('
                SELECT IS_NULLABLE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ?
                AND TABLE_NAME = ?
                AND COLUMN_NAME = ?
            ', [$database, $tableName, $columnName]);

            return ! empty($result) && $result[0]->IS_NULLABLE === 'NO';
        } catch (\Exception $e) {
            // Fallback: is required
            return true;
        }
    }

    protected function getFormPath(string $formClass): string
    {
        if ($this->option('path')) {
            return $this->option('path');
        }

        $formsDir = app_path('Forms');
        File::ensureDirectoryExists($formsDir);

        return $formsDir.'/'.$formClass.'.php';
    }

    protected function generateForm(string $formClass, string $formPath, array $columns, string $modelName): void
    {
        $fields = $this->generateFields($columns);
        $uses = $this->generateUses($columns);

        $stub = $this->getFormStub();
        $content = str_replace([
            '{{ namespace }}',
            '{{ uses }}',
            '{{ class }}',
            '{{ fields }}',
            '{{ model }}',
        ], [
            'App\\Forms',
            $uses,
            $formClass,
            $fields,
            $modelName,
        ], $stub);

        File::put($formPath, $content);
    }

    protected function generateFields(array $columns): string
    {
        $fields = [];

        foreach ($columns as $column) {
            $fieldType = $this->getFieldType($column['type']);
            $fieldName = $column['name'];
            $label = Str::title(str_replace('_', ' ', $fieldName));

            $field = "            {$fieldType}::make('{$fieldName}')";
            $field .= "\n                ->label('{$label}')";

            if (! $column['nullable']) {
                $field .= "\n                ->required()";
            }

            $field .= $this->getFieldSpecificConfig($column);

            $field .= ',';
            $fields[] = $field;
        }

        return implode("\n\n", $fields);
    }

    protected function getFieldSpecificConfig(array $column): string
    {
        $config = '';

        switch ($column['type']) {
            case 'string':
                $config .= "\n                ->placeholder('Enter {$column['name']}')";
                break;
            case 'text':
                $config .= "\n                ->placeholder('Enter {$column['name']}')";
                $config .= "\n                ->rows(4)";
                break;
            case 'boolean':
                $config .= "\n                ->default(false)";
                break;
            case 'integer':
            case 'bigint':
                $config .= "\n                ->type('number')";
                break;
            case 'decimal':
            case 'float':
            case 'double':
                $config .= "\n                ->type('number')";
                $config .= "\n                ->step('0.01')";
                break;
            case 'date':
                $config .= "\n                ->type('date')";
                break;
            case 'datetime':
            case 'timestamp':
                $config .= "\n                ->type('datetime-local')";
                break;
            case 'time':
                $config .= "\n                ->type('time')";
                break;
        }

        return $config;
    }

    protected function getFieldType(string $columnType): string
    {
        return $this->fieldTypeMapping[$columnType] ?? 'TextField';
    }

    protected function generateUses(array $columns): string
    {
        $fieldTypes = [];

        foreach ($columns as $column) {
            $fieldType = $this->getFieldType($column['type']);
            $fieldTypes[] = $fieldType;
        }

        $uniqueFieldTypes = array_unique($fieldTypes);
        $uses = [];

        foreach ($uniqueFieldTypes as $fieldType) {
            $uses[] = "use LaravelForminertia\\Fields\\{$fieldType};";
        }

        return implode("\n", $uses);
    }

    protected function getFormStub(): string
    {
        return <<<'STUB'
<?php

namespace {{ namespace }};

use LaravelForminertia\Base\Form;
{{ uses }}

class {{ class }} extends Form
{
    /**
     * Auto-generated form for {{ model }} model
     *
     * @return array
     */
    public function schema(): array
    {
        return [
{{ fields }}
        ];
    }
}
STUB;
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'model' => 'What is the model name?',
        ];
    }
}
