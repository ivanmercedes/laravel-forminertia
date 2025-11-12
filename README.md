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

## Auto-Generate Forms from Database Tables

FormInertia can automatically generate form classes based on your database table structure, similar to Filament's generate command:

```bash
# Generate form from model/table
php artisan forminertia:generate User

# Generate with custom table name
php artisan forminertia:generate User --table=custom_users

# Exclude specific columns
php artisan forminertia:generate User --exclude=password,remember_token

# Force overwrite existing form
php artisan forminertia:generate User --force

# Custom output path
php artisan forminertia:generate User --path=app/Forms/Custom/UserForm.php
```

The command will:

-   Analyze your table structure
-   Map column types to appropriate form fields
-   Generate validation rules based on nullable columns
-   Exclude common system columns (id, timestamps, etc.)
-   Create a ready-to-use form class

**Example generated form:**

```php
<?php

namespace App\Forms;

use LaravelForminertia\Base\Form;
use LaravelForminertia\Fields\TextField;
use LaravelForminertia\Fields\TextareaField;
use LaravelForminertia\Fields\CheckboxField;

class UserForm extends Form
{
    public function schema(): array
    {
        return [
            TextField::make('name')
                ->label('Name')
                ->placeholder('Enter name')
                ->required(),

            TextField::make('email')
                ->label('Email')
                ->placeholder('Enter email')
                ->required(),

            TextareaField::make('bio')
                ->label('Bio')
                ->placeholder('Enter bio')
                ->rows(4),

            CheckboxField::make('is_active')
                ->label('Is Active')
                ->default(false),
        ];
    }
}
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
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function create()
    {
        // Create form without data (for new records)
        $form = UserForm::make();

        return Inertia::render('user/create', [
            'form' => $form,
        ]);
    }

    public function edit(User $user)
    {
        // Fill form with existing data - now works directly with models!
        $form = UserForm::make($user);

        return Inertia::render('user/edit', [
            'form' => $form,
        ]);
    }

    public function editWithDefaults()
    {
        // Fill form with custom default values
        $form = UserForm::make([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
            'is_active' => true,
        ]);

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

## Fill Data - Clean & Simple

Forminertia makes it incredibly easy to populate forms with existing data.Here are the different ways you can do it:

### Method 1: Direct with Eloquent Models

```php
// Fill form directly with Eloquent models
$user = User::find(1);
$form = UserForm::make($user);
```

### Method 2: Direct Array Data

```php
// Fill form with array data
$form = UserForm::make([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'role' => 'admin',
    'is_active' => true,
]);
```

### Method 3: With Collections

```php
// Works with Collections too
$userData = collect([
    'name' => 'Jane Doe',
    'email' => 'jane@example.com',
    'role' => 'editor',
]);
$form = UserForm::make($userData);
```

### Method 4: Chain Fill Method

```php
// Create form first, then fill
$form = UserForm::make()
    ->fill(['name' => 'Jane Doe', 'email' => 'jane@example.com'])
    ->build();
```

### Method 5: Using fillFromModel Method

```php
// Alternative method for any object with toArray()
$user = User::find(1);
$form = UserForm::make()->fillFromModel($user)->build();
```

**Smart Data Handling**: The form automatically detects the data type and converts it appropriately:

-   **Eloquent Models**: Automatically calls `->toArray()`
-   **Collections**: Automatically calls `->toArray()`
-   **Arrays**: Used directly
-   **Objects with `toArray()`**: Automatically converted
-   **Other objects**: Cast to array

Fields will automatically populate with matching data. Fields without matching data will use their default values or remain empty.

## Features

-   Plug-and-play form builder for Laravel + Inertia (React)
-   Clean and simple data filling system
-   Define forms using PHP schemas (Sections, Grids, Fields)
-   Automatically renders dynamic forms in your Inertia React app
-   Type-safe integration between PHP and TypeScript
-   Includes an installer command (php artisan forminertia:install) to set up resources
-   Vue 3 + Inertia support coming soon

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
