<?php

namespace ESadewater\LaravelMeilisearch\Commands;

use ESadewater\LaravelMeilisearch\Contracts\MeiliSearchable;
use Illuminate\Console\Command as BaseCommand;
use Laravel\Scout\EngineManager;
use MeiliSearch\Client;
use Symfony\Component\Console\Command\Command;

class MeiliSyncIndexSettings extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meili:sync-settings {model : Model which index settings gets synced}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the index settings';

    /**
     * Execute the console command.
     */
    public function handle(EngineManager $manager): int
    {
        $engine = $manager->engine();
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
        $this->info("Updating filterable/sortable attributes for [$modelClass] [$indexName]");

        $client = $this->getMeiliClient();
        $index = $client->index($indexName);

        $index->updateFilterableAttributes($modelClass::getFilterableAttributes());
        $index->updateSortableAttributes($modelClass::getSortableAttributes());
        $index->updateSearchableAttributes($modelClass::getSearchableAttributes());

        return Command::SUCCESS;
    }

    /**
     * Create a new instance of the MeiliSearchClient with the database url
     */
    private function getMeiliClient(): Client
    {
        $url = config('scout.meilisearch.host');
        $key = config('scout.meilisearch.key');

        return new Client($url, $key);
    }
}
