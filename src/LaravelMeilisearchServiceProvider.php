<?php

namespace ESadewater\LaravelMeilisearch;

use ESadewater\LaravelMeilisearch\Commands\MeiliCreateIndex;
use ESadewater\LaravelMeilisearch\Commands\MeiliImportModel;
use ESadewater\LaravelMeilisearch\Commands\MeiliSetup;
use ESadewater\LaravelMeilisearch\Commands\MeiliSyncIndexSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelMeilisearchServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-meilisearch')
            ->hasCommands(
                MeiliCreateIndex::class,
                MeiliImportModel::class,
                MeiliSetup::class,
                MeiliSyncIndexSettings::class);
    }
}
