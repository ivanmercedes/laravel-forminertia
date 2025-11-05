<?php

namespace LaravelForminertia\Fields;

use LaravelForminertia\Base\Field;

class CheckboxField extends Field
{
    public function getType(): string
    {
        return 'checkbox';
    }
}
