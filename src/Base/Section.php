<?php

namespace LaravelForminertia\Base;

class Section
{
    protected string $heading;

    protected ?string $description = null;

    protected array $schema = [];

    public static function make(string $heading): static
    {
        $instance = new static;
        $instance->heading = $heading;

        return $instance;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
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
            'type' => 'section',
            'heading' => $this->heading,
            'description' => $this->description,
            'schema' => array_map(fn ($field) => $field->toArray(), $this->schema),
        ];
    }
}
