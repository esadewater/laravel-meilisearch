<?php

namespace ESadewater\LaravelMeilisearch\Commands;

use ESadewater\LaravelMeilisearch\Contracts\MeiliSearchable;
use ESadewater\LaravelMeilisearch\Facades\LaravelMeilisearch;
use Exception;
use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;

class MeiliSetup extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meili:setup {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MeiliSetup indices and their settings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $force = $this->option('force');

        if (app()->environment('production') && ! $force) {
            $this->error('This command should not be run in production!');

            return Command::FAILURE;
        }

        $searchableModels = LaravelMeilisearch::getAllModelClasses()->filter(function ($className) {
            return is_subclass_of($className, MeiliSearchable::class);
        });

        if (Artisan::call('scout:delete-all-indexes') != Command::SUCCESS) {
            $this->error('Failed to delete all indices.');

            return Command::FAILURE;
        }

        try {
            $this->withProgressBar($searchableModels, function ($modelClass) {
                if ($this->setupIndex($modelClass) != Command::SUCCESS) {
                    throw new Exception("Failed to setup index for $modelClass");
                }
            });
        } catch (Exception $exception) {
            $this->newLine(2);
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        $this->newLine(2);
        $this->info('Indices and their settings have been setup successfully');

        return Command::SUCCESS;
    }

    private function setupIndex(string $searchableModel): int
    {
        $result = Artisan::call('meili:create', [
            'model' => $searchableModel,
        ]);

        if ($result != Command::SUCCESS) {
            return $result;
        }

        $result = Artisan::call('meili:sync-settings', [
            'model' => $searchableModel,
        ]);

        if ($result != Command::SUCCESS) {
            return $result;
        }

        return Artisan::call('meili:import', [
            'model' => $searchableModel,
        ]);
    }
}
