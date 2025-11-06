<?php

use LaravelForminertia\Base\Form;
use LaravelForminertia\Base\Grid;
use LaravelForminertia\Base\Section;
use LaravelForminertia\Fields\TextField;
use LaravelForminertia\Fields\SelectField;
use LaravelForminertia\Fields\CheckboxField;

class TestForm extends Form
{
    public function schema(): array
    {
        return [
            Section::make('User Information')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextField::make('name')
                                ->label('Full Name')
                                ->required(),
                            TextField::make('email')
                                ->label('Email Address')
                                ->type('email')
                                ->required(),
                            SelectField::make('role')
                                ->label('User Role')
                                ->options([
                                    'admin' => 'Administrator',
                                    'user' => 'User',
                                ]),
                        ])
                ]),
            Section::make('Preferences')
                ->schema([
                    CheckboxField::make('is_active')
                        ->label('Active Account'),
                ]),
        ];
    }
}

it('can fill form with array data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'admin',
        'is_active' => true,
    ];

    $form = TestForm::make($data);

    expect($form['data'])->toBe($data);

    $schemas = $form['schemas'];

    $nameField = null;
    $emailField = null;
    $roleField = null;
    $isActiveField = null;

    foreach ($schemas as $section) {
        if ($section['type'] === 'section') {
            foreach ($section['schema'] as $item) {
                if ($item['type'] === 'grid') {
                    foreach ($item['schema'] as $field) {
                        if ($field['name'] === 'name') {
                            $nameField = $field;
                        }
                        if ($field['name'] === 'email') {
                            $emailField = $field;
                        }
                        if ($field['name'] === 'role') {
                            $roleField = $field;
                        }
                    }
                } elseif ($item['name'] === 'is_active') {
                    $isActiveField = $item;
                }
            }
        }
    }

    expect($nameField['value'])->toBe('John Doe');
    expect($emailField['value'])->toBe('john@example.com');
    expect($roleField['value'])->toBe('admin');
    expect($isActiveField['value'])->toBe(true);
});

it('can fill form using fill method', function () {
    $form = new TestForm();
    $data = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ];

    $result = $form->fill($data)->build();

    expect($result['data'])->toBe($data);
});

it('can fill form from model-like object', function () {
    $model = (object) [
        'name' => 'Model User',
        'email' => 'model@example.com',
        'role' => 'user',
        'is_active' => false,
    ];

    $form = new TestForm();
    $result = $form->fillFromModel($model)->build();

    expect($result['data'])->toBe([
        'name' => 'Model User',
        'email' => 'model@example.com',
        'role' => 'user',
        'is_active' => false,
    ]);
});

it('can fill form with eloquent model directly without toArray', function () {
    $model = new class {
        public function toArray(): array
        {
            return [
                'name' => 'Eloquent User',
                'email' => 'eloquent@example.com',
                'role' => 'admin',
                'is_active' => true,
            ];
        }
    };

    $form = TestForm::make($model);

    expect($form['data'])->toBe([
        'name' => 'Eloquent User',
        'email' => 'eloquent@example.com',
        'role' => 'admin',
        'is_active' => true,
    ]);

    $schemas = $form['schemas'];
    $nameField = null;
    $emailField = null;

    foreach ($schemas as $section) {
        if ($section['type'] === 'section') {
            foreach ($section['schema'] as $item) {
                if ($item['type'] === 'grid') {
                    foreach ($item['schema'] as $field) {
                        if ($field['name'] === 'name') {
                            $nameField = $field;
                        }
                        if ($field['name'] === 'email') {
                            $emailField = $field;
                        }
                    }
                }
            }
        }
    }

    expect($nameField['value'])->toBe('Eloquent User');
    expect($emailField['value'])->toBe('eloquent@example.com');
});

it('handles empty data gracefully', function () {
    $form = TestForm::make([]);

    expect($form['data'])->toBe([]);

    // Los campos no deben tener valores
    $schemas = $form['schemas'];
    foreach ($schemas as $section) {
        if ($section['type'] === 'section') {
            foreach ($section['schema'] as $item) {
                if ($item['type'] === 'grid') {
                    foreach ($item['schema'] as $field) {
                        expect($field)->not->toHaveKey('value');
                    }
                }
            }
        }
    }
});

it('handles partial data correctly', function () {
    $data = [
        'name' => 'Partial User',
    ];

    $form = TestForm::make($data);

    expect($form['data'])->toBe($data);

    $schemas = $form['schemas'];
    $nameField = null;
    $emailField = null;

    foreach ($schemas as $section) {
        if ($section['type'] === 'section') {
            foreach ($section['schema'] as $item) {
                if ($item['type'] === 'grid') {
                    foreach ($item['schema'] as $field) {
                        if ($field['name'] === 'name') {
                            $nameField = $field;
                        }
                        if ($field['name'] === 'email') {
                            $emailField = $field;
                        }
                    }
                }
            }
        }
    }

    expect($nameField['value'])->toBe('Partial User');
    expect($emailField)->not->toHaveKey('value');
});
