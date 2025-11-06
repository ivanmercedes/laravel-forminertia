<?php

namespace LaravelForminertia;

class FormInertia
{

    public function form(string $formClass, array $data = []): array
    {
        if (! class_exists($formClass)) {
            throw new \InvalidArgumentException("Form class {$formClass} not found.");
        }

        return $formClass::make($data);
    }

    public function formWithModel(string $formClass, $model): array
    {
        if (! class_exists($formClass)) {
            throw new \InvalidArgumentException("Form class {$formClass} not found.");
        }

        /** @var \LaravelForminertia\Base\Form $form */
        $form = app($formClass);

        return $form->fillFromModel($model)->build();
    }

    public function json(string $formClass, array $data = []): string
    {
        return json_encode($this->form($formClass, $data), JSON_PRETTY_PRINT);
    }
}
