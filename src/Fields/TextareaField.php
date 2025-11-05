<?php

namespace LaravelForminertia\Fields;

use LaravelForminertia\Base\Field;

class TextareaField extends Field
{
    protected ?int $rows = null;

    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function getType(): string
    {
        return 'textarea';
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'rows' => $this->rows,
        ]);
    }
}
