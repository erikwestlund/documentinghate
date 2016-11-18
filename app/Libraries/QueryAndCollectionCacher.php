<?php

namespace App\Libraries;

use Cache;

trait QueryAndCollectionCacher
{
    /**
     * Send collection to cache
     * 
     * @param  string $cache_key    
     * @param  Collection $collection 
     * @param  string $cache_tags   (optionalc) 
     * @return void
     */
    public static function cacheCollection($cache_key, $collection, $cache_tags = null)
    {

        $cache_time = ($collection instanceof  \Illuminate\Support\Collection) ?
            config('site.cache.collection.query') : 
            config('site.cache.expiration.query');

        // if no cache tags, just cache it.
        if(! $cache_tags) { 
            return Cache::put($cache_key, $collection, config('site.cache.expiration.query'));
        }

        // if cache tags passed, turn to array if a string
        if(! is_array($cache_tags)) { 
            $cache_tags = [$cache_tags];
        }

        // cache it
        Cache::tags($cache_tags)
            ->put($cache_key, $collection, config('site.cache.expiration.query'));
    }

    /**
     * Get cache tags
     * @param  string $called_class
     * @param  array $cache_tags 
     * @return array             
     */
    public static function getCacheTags($object, $cache_tags)
    {
        if(is_string($cache_tags)) {
            $cache_tags = [$cache_tags];
        }

        // eloquent calls
        if($object == 'all') {
            $type = 'all';
        } else if ($object instanceof  \Illuminate\Support\Collection) {
            $type = 'collection';
        } else if ($object instanceof \Illuminate\Database\Eloquent\Builder) {
            $type = 'eloquent';
        } else if ($object instanceof \Illuminate\Database\Query\Builder) {
            $type = 'object';
        } else if ($object instanceof \Laravel\Scout\Builder) {
            $type = 'scout';
        }

        return array_unique(array_merge(['cached-object', $type], $cache_tags));
    }

    /**
     * Takes a cached query, processes it, and saves it with
     * a normalized cache key.
     * @param  mixed $query 'all' for all of a model or
     *                      Eloquent/DB Builder object
     * @return Collection
     */
    public static function getCachedQuery($query, $cache_tags = [])
    {
        $cache_tags = is_array($cache_tags) ? $cache_tags : [$cache_tags];

        $called_class = get_called_class();
        $cache_tags[] = $called_class;

        $cache_key = parent::getQueryCacheKey($query, $called_class);
        $cache_tags = parent::getCacheTags($query, $cache_tags);

        // if key exists return
        if(Cache::tags($cache_tags)->has($cache_key)) {
            return Cache::tags($cache_tags)->get($cache_key);
        }

        // otherwise, get query resulst and cache it
        $query_result = parent::getQueryResult($query, $called_class);

        // cache the result
        if(count($query_result) > 0) {
            parent::cacheCollection($cache_key, $query_result, $cache_tags);    
        }

        return $query_result;
    }

    /**
     * Helper function to get a cache key for a collection.
     * 
     * @param  Collection $collection 
     * @param  string     $identifier 
     * @return string
     */
    public static function getCollectionCacheKey($collection, $identifier)
    {
        if(! $collection instanceof  \Illuminate\Support\Collection) {
            $collection = collect($collection);
        }

        $called_class = get_called_class();
        return config('site.cache.prefixes.collection') . '-' . $called_class . '-' . $identifier . '-' . md5($collection);
    }

    /**
     * Get a cached query key.
     * @param  mixed $query 
     * @return string
     */
    protected static function getQueryCacheKey($query, $called_class)
    {
        // if asking for all or scout query, return specified strings
        if($query == 'all') {
            return config('site.cache.prefixes.query') . '-' . $called_class . '-all';
        } else if ($query instanceof \Laravel\Scout\Builder) {
            return config('site.cache.prefixes.query') . '-' . $called_class . '-search-' . str_slug($query->query);
        } else if ($query instanceof \Illuminate\Database\Eloquent\Builder) {
            return config('site.cache.prefixes.eloquent') . '-' . $called_class . '-' . md5($query->toSql() . '-' . serialize($query->getBindings()));
        }

        // otherwise, save a hash of the generated sql of the query
        return config('site.cache.prefixes.query') . '-' . $called_class . '-' . md5($query->toSql() . '-' . serialize($query->getBindings()));
    }

    /**
     * Query a result
     * @param  mixed $query        
     * @param  string $called_class     name of the calling class
     * @return Collection
     */
    protected function getQueryResult($query, $called_class)
    {
        // get all
        if(is_string($query) && $query == 'all') {
            return $called_class::all();
        }

        return $query->get();
    }

}
