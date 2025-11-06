<?php

namespace LaravelForminertia\Fields;

use LaravelForminertia\Base\Field;

class TextField extends Field
{
    protected ?string $inputType = null;

    public function type(string $type): static
    {
        $this->inputType = $type;

        return $this;
    }

    public function getType(): string
    {
        return 'text';
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'inputType' => $this->inputType,
        ]);
    }
}
