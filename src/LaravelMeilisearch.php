<?php

namespace ESadewater\LaravelMeilisearch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class LaravelMeilisearch
{
    /**
     * Get the classes of all models
     */
    private function getAllModelClasses(): Collection
    {
        // Get all the model files
        return collect(File::allFiles(app_path('Models')))
            ->map(function ($item) {
                // Convert the file path to a class name
                $path = $item->getRelativePathName();

                return sprintf('\%sModels\%s',
                    app()->getNamespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
            })
            ->filter(function ($class) {
                // Filter out any classes that don't exist or aren't models
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        ! $reflection->isAbstract();
                }

                return $valid;
            });
    }
}
