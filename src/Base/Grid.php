<?php

namespace LaravelForminertia\Base;

class Grid
{
    protected array $schema = [];
    protected int $columns = 2;

    public static function make(int $columns = 2): static
    {
        $instance = new static();
        $instance->columns = $columns;
        return $instance;
    }

    public function schema(array $schema): static
    {
        $this->schema = $schema;
        return $this;
    }

    public function getSchema(): array
    {
        return $this->schema;
    }

    public function toArray(): array
    {
        return [
            'type' => 'grid',
            'columns' => $this->columns,
            'schema' => array_map(fn($field) => $field->toArray(), $this->schema),
        ];
    }
}
