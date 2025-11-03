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

## ShadCN UI Components

FormInertia uses **ShadCN UI** components such as `Input`, `Select`, `Checkbox`, and `Textarea`.

If you don’t have the `Textarea` component installed, you’ll see a warning during installation.
You can easily add it by running:

```bash
npx shadcn@latest add textarea
```

## Usage Example

### 1. Define your schema

Each field or group of fields is defined using a simple, type-safe schema.

```tsx
import { FormSchemaItem } from "@/components/forminertia/form-builder";

const userFormSchema: FormSchemaItem[] = [
    {
        type: "section",
        heading: "User Information",
        schema: [
            {
                type: "text",
                name: "name",
                label: "Full Name",
                placeholder: "John Doe",
                required: true,
            },
            {
                type: "text",
                name: "email",
                label: "Email Address",
                inputType: "email",
                placeholder: "user@example.com",
                required: true,
            },
            {
                type: "select",
                name: "role",
                label: "User Role",
                required: true,
                options: {
                    admin: "Administrator",
                    editor: "Editor",
                    user: "User",
                },
            },
        ],
    },
    {
        type: "section",
        heading: "Preferences",
        schema: [
            {
                type: "checkbox",
                name: "is_active",
                label: "Active Account",
            },
            {
                type: "textarea",
                name: "bio",
                label: "Biography",
                rows: 4,
                placeholder: "Tell us a bit about this user...",
            },
        ],
    },
];
```

Then render it inside your page:

```tsx
import { store } from "@/routes/login";
import FormBuilder from "@/components/forminertia/form-builder";

<FormBuilder
    schema={userFormSchema}
    form={store.form()}
    submitLabel="Create User"
/>;
```

## Supported Field Types

| Type       | Description           |
| ---------- | --------------------- |
| `text`     | Standard input fields |
| `textarea` | Multiline text areas  |
| `select`   | Dropdown lists        |
| `checkbox` | Checkbox inputs       |
| `date`     | Date picker inputs    |

---

## Features

-   Designed for the **Laravel Inertia React Starter Kit**
-   Uses **ShadCN UI** for consistent and elegant styling
-   Auto-installer for base form components
-   Type-safe form schemas with **TypeScript**
-   Extendable and customizable for any use case
-   **Vue support coming soon**

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
