<?php

namespace ESadewater\LaravelMeilisearch\Commands;

use ESadewater\LaravelMeilisearch\Contracts\MeiliSearchable;
use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;

class MeiliImportModel extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meili:import {model : Model which gets imported to search index} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the given model into the search index';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $modelClass = $this->argument('model');

        if (! class_exists($modelClass)) {
            $this->error("Class [$modelClass] does not exist");

            return Command::FAILURE;
        }

        if (! is_subclass_of($modelClass, MeiliSearchable::class)) {
            $this->error("Class [$modelClass] is not a searchable model");

            return Command::FAILURE;
        }

        $this->info("Importing models of [$modelClass]");

        return Artisan::call('scout:import', [
            'model' => $modelClass,
        ]);
    }
}
