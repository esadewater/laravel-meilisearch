<?php

namespace ESadewater\LaravelMeilisearch\Contracts;

interface MeiliSearchable
{
    /**
     * Get the name of the models search index
     *
     * @return string
     */
    public function searchableAs();

    /**
     * Get attributes used for search
     */
    public function toSearchableArray(): array;

    /**
     * Define the search sortable attributes for the model
     */
    public static function getFilterableAttributes(): array;

    /**
     * Define the search filter attributes for the model
     */
    public static function getSortableAttributes(): array;

    /**
     * Define the search filter attributes for the model
     */
    public static function getSearchableAttributes(): array;
}
