<?php

namespace LaravelForminertia\Base;

abstract class Field
{
    protected string $name;
    protected ?string $label = null;
    protected mixed $default = null;
    protected bool $required = false;
    protected ?string $placeholder = null;
    protected ?string $helperText = null;
    protected bool $disabled = false;
    protected ?int $columnSpan = null;

    public static function make(string $name): static
    {
        $instance = new static();
        $instance->name = $name;
        return $instance;
    }

    public function label(string $label): static { $this->label = $label; return $this; }
    public function default(mixed $value): static { $this->default = $value; return $this; }
    public function required(bool $required = true): static {
        $this->required = $required;
        return $this;
    }
    public function placeholder(string $p): static { $this->placeholder = $p; return $this; }
    public function helperText(string $t): static { $this->helperText = $t; return $this; }
    public function disabled(bool $v = true): static { $this->disabled = $v; return $this; }
    public function columnSpan(int $span): static { $this->columnSpan = $span; return $this; }

    public function getName(): string { return $this->name; }

    abstract public function getType(): string;

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->name,
            'label' => $this->label ?? ucfirst(str_replace('_', ' ', $this->name)),
            'default' => $this->default,
            'required' => $this->required,
            'placeholder' => $this->placeholder,
            'helperText' => $this->helperText,
            'disabled' => $this->disabled,
            'columnSpan' => $this->columnSpan,
        ];
    }
}
