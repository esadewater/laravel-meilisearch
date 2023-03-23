<?php

namespace ESadewater\LaravelMeilisearch\Contracts;

use Laravel\Scout\Searchable;

trait IsMeiliSearchable
{
    use Searchable;

    /**
     * Get default filterable attributes: none
     */
    public static function getFilterableAttributes(): array
    {
        return [];
    }

    /**
     * Get default sortable attributes: none
     */
    public static function getSortableAttributes(): array
    {
        return [];
    }

    /**
     * Get attributes that should not be searchable while still included in the index for e.g. filtering
     */
    public static function getSearchableAttributes(): array
    {
        return ['*'];
    }
}
