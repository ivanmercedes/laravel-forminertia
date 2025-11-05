<?php

namespace LaravelForminertia;

class FormInertia
{
    public function form(string $formClass, array $data = []): array
    {
        if (! class_exists($formClass)) {
            throw new \InvalidArgumentException("Form class {$formClass} not found.");
        }

        /** @var \LaravelForminertia\Base\Form $form */
        $form = app($formClass);

        if (! empty($data)) {
            $form->fill($data);
        }

        return $form->make();
    }

    public function json(string $formClass, array $data = []): string
    {
        return json_encode($this->form($formClass, $data), JSON_PRETTY_PRINT);
    }
}
