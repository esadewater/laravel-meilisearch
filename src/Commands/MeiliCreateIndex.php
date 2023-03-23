<?php

namespace ESadewater\LaravelMeilisearch\Commands;

use ESadewater\LaravelMeilisearch\Contracts\MeiliSearchable;
use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;

class MeiliCreateIndex extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meili:create {model : Model for which the index should be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an search index';

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

        $indexName = app($modelClass)->searchableAs();

        $this->info("Creating search index for [$modelClass] [$indexName]");

        return Artisan::call('scout:index', [
            'name' => $indexName,
        ]);
    }
}
