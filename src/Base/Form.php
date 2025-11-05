<?php

namespace LaravelForminertia\Base;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

abstract class Form
{
    protected array $schema = [];

    protected array|Collection $data = [];

    protected ?string $model = null;

    abstract public function schema(): array;

    public static function make(array|Collection $data = []): array
    {
        $instance = App::make(static::class);

        if (! empty($data)) {
            $instance->fill($data);
        }

        return $instance->build();
    }

    public function build(): array
    {
        $this->schema = $this->schema();

        return [
            'schemas' => $this->transformSchema($this->schema),
            'data' => $this->data,
        ];
    }

    public function fill(array|Collection $data): self
    {
        $this->data = $data;

        return $this;
    }

    protected function transformSchema(array $schema): array
    {
        return array_map(fn($field) => $field->toArray(), $schema);
    }
}
