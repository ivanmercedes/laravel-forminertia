# Laravel Forminertia

A plug-and-play form builder for Laravel’s Inertia Starter Kit — powered by ShadCN UI.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanmercedes/laravel-forminertia.svg?style=flat-square)](https://packagist.org/packages/ivanmercedes/laravel-forminertia)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ivanmercedes/laravel-forminertia/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanmercedes/laravel-forminertia/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ivanmercedes/laravel-forminertia/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ivanmercedes/laravel-forminertia/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanmercedes/laravel-forminertia.svg?style=flat-square)](https://packagist.org/packages/ivanmercedes/laravel-forminertia)

## Installation

You can install the package via composer:

```bash
composer require ivanmercedes/laravel-forminertia
```

Then run the installation command to publish the default resources:

```bash
php artisan forminertia:install
```

## Component Compatibility

FormInertia uses the same ShadCN UI components that come with the Laravel + Inertia Starter Kit, including Input, Select, and Checkbox.

However, since the Starter Kit doesn’t include a Textarea component by default, FormInertia automatically installs it during setup to ensure full compatibility out of the box.

## Usage Example

### 1. Define your schema

Each form is a PHP class that defines its structure (sections, grids, and fields).

```php
<?php

namespace App\Forms;

use LaravelForminertia\Base\Form;
use LaravelForminertia\Base\Grid;
use LaravelForminertia\Base\Section;
use LaravelForminertia\Fields\{TextField, SelectField, CheckboxField, TextareaField};

class UserForm extends Form
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
                                ->placeholder('John Doe')
                                ->required(),
                            TextField::make('email')
                                ->label('Email Address')
                                ->type('email')
                                ->placeholder('user@example.com')
                                ->required(),
                            SelectField::make('role')
                                ->label('User Role')
                                ->required()
                                ->options([
                                    'admin' => 'Administrator',
                                    'editor' => 'Editor',
                                    'user' => 'User',
                                ]),
                        ])
                ]),
            Section::make('Preferences')
                ->schema([
                    CheckboxField::make('is_active')
                        ->label('Active Account'),
                    TextareaField::make('bio')
                        ->label('Biography')
                        ->rows(6)
                        ->placeholder('Tell us a bit about this user...'),
                ]),
        ];
    }
}

```

### 2. Create a Controller

The controller injects the schema into your Inertia view.

```php
<?php

namespace App\Http\Controllers;

use App\Forms\UserForm;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function create()
    {
        // Get the form schema
        $form = UserForm::make();

        // Pass it to the Inertia page
        return Inertia::render('user/create', [
            'form' => $form,
        ]);
    }
}
```

### 3. Then render it inside your page:

```tsx
import { store } from "@/routes/users";
import FormBuilder, {
    FormBuilderProps,
} from "@/components/forminertia/form-builder";

export default function create({
    form,
}: {
    form: FormBuilderProps["formSchema"];
}) {
    return (
        <div className="max-w-3xl mx-auto">
            <FormBuilder
                formSchema={form}
                form={store.form()}
                submitLabel="Create User"
            />
        </div>
    );
}
```

## Features

-   Plug-and-play form builder for Laravel + Inertia (React)
-   Define forms using PHP schemas (Sections, Grids, Fields)
-   Automatically renders dynamic forms in your Inertia React app
-   Type-safe integration between PHP and TypeScript
-   Includes an installer command (php artisan forminertia:install) to set up resources
-   Vue 3 + Inertia support coming soon

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
