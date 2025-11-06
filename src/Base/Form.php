<?php

namespace LaravelForminertia\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

abstract class Form
{
    protected array $schema = [];

    protected array $data = [];

    protected ?string $model = null;

    abstract public function schema(): array;

    public static function make(mixed $data = []): array
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
            'data' => $this->getFormattedData(),
        ];
    }

    public function fill(mixed $data): self
    {
        if ($data instanceof Model) {
            $this->data = $data->toArray();
        } elseif ($data instanceof Collection) {
            $this->data = $data->toArray();
        } elseif (is_array($data)) {
            $this->data = $data;
        } else {
            // Object with toArray method
            $this->data = method_exists($data, 'toArray') ? $data->toArray() : (array) $data;
        }

        return $this;
    }

    public function fillFromModel($model): self
    {
        $this->data = is_object($model) && method_exists($model, 'toArray')
            ? $model->toArray()
            : (array) $model;

        return $this;
    }

    protected function transformSchema(array $schema): array
    {
        return array_map(function ($item) {
            $array = $item->toArray();

            if (isset($array['name']) && array_key_exists($array['name'], $this->data)) {
                $array['value'] = $this->data[$array['name']];
            }

            if (isset($array['schema']) && is_array($array['schema'])) {
                $array['schema'] = $this->processNestedSchema($array['schema']);
            }

            return $array;
        }, $schema);
    }

    protected function processNestedSchema(array $schema): array
    {
        return array_map(function ($item) {
            if (isset($item['name']) && array_key_exists($item['name'], $this->data)) {
                $item['value'] = $this->data[$item['name']];
            }

            if (isset($item['schema']) && is_array($item['schema'])) {
                $item['schema'] = $this->processNestedSchema($item['schema']);
            }

            return $item;
        }, $schema);
    }

    protected function getFormattedData(): array
    {
        return is_array($this->data) ? $this->data : [];
    }
}
