<?php

namespace ESadewater\LaravelMeilisearch\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ESadewater\LaravelMeilisearch\LaravelMeilisearch
 */
class LaravelMeilisearch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ESadewater\LaravelMeilisearch\LaravelMeilisearch::class;
    }
}
