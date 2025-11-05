<?php

namespace LaravelForminertia\Fields;

use LaravelForminertia\Base\Field;

class SelectField extends Field
{
    protected array $options = [];

    public function options(array $options): static
    {
        $this->options = $options;
        return $this;
    }

    public function getType(): string
    {
        return 'select';
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'options' => $this->options,
        ]);
    }
}
