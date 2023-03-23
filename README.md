# Package to conveniently handle search indices for MeiliSearch.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esadewater/laravel-meilisearch.svg?style=flat-square)](https://packagist.org/packages/esadewater/laravel-meilisearch)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/esadewater/laravel-meilisearch/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/esadewater/laravel-meilisearch/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/esadewater/laravel-meilisearch/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/esadewater/laravel-meilisearch/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/esadewater/laravel-meilisearch.svg?style=flat-square)](https://packagist.org/packages/esadewater/laravel-meilisearch)

Package to conveniently handle search index settings like sortable and filterable attributes for MeiliSearch through
Laravel Scout.

## Prerequisites

You need to have [Laravel Scout](https://laravel.com/docs/10.x/scout) installed and configured. If you don't have done
yet, you can skip
the "[Configuring Filterable Data & Index Settings (Meilisearch)](https://laravel.com/docs/10.x/scout#configuring-filterable-data-for-meilisearch)"
-part while setting everything up.

## Installation

You can install the package via composer:

```bash
composer require esadewater/laravel-meilisearch
```

## Usage

Instead of the `Searchable` trait of Laravel Scout, you need to use the `IsMeiliSearchable` trait and in addition
implement the `MeiliSearchable` interface. Replace the `Searchable` trait with the `IsMeiliSearchable` trait in your
models. Your models should look like this:

```php
class Food extends Model implements MeiliSearchable
{
    use IsMeiliSearchable;
    
    /**
     * @var string[] Mass-assignable attributes
     */
    protected $fillable = [
        'name',
    ];
    
    /**
     * Get attributes used for search
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
```

To make the handling of the index settings easier than with Laravel Scout, each model contains its own index settings
for searchable, sortable and filterable attributes:

```php
class Recipe extends Model implements MeiliSearchable
{
    use IsMeiliSearchable;
    
    /**
     * Define the searchable attributes for the model
     */
    public static function getSearchableAttributes(): array
    {
        return [
            'name',
            'difficulty',
            'ingredient_names',
        ];
    }

    /**
     * Define the search sortable attributes for the model
     */
    public static function getSortableAttributes(): array
    {
        return [
            'name',
            'difficulty',
        ];
    }

    /**
     * Define the search filter attributes for the model
     */
    public static function getFilterableAttributes(): array
    {
        return [
            'difficulty',
        ];
    }
}
```

By default, all attributes are searchable. If you want to omit some attributes from the search, you have to define all remaining attributes in the `getSearchableAttributes()` method.

### Indexing

To create the search indices for all models, setup their index settings and import all existing models into the index, you can use the `meili:setup` command:

```bash
php artisan meili:setup
```

If you want to execute one of the steps separately for one model, you can use the following commands:

To create the search index for one model, you can use the `meili:create {model}` command:

```bash
php artisan meili:create App\\Models\\Food
```

To sync the index setting, like searchable, sortable and filterable attributes, you can use the `meili:sync-settings {model}` command:

```bash
php artisan meili:sync-settings App\Models\Food
```

To import models into the search index, you can use the `meili:import {model}` command:

```bash
php artisan meili:import App\Models\Food
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Erik Sadewater](https://github.com/esadewater)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
