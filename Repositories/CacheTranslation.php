<?php namespace Modules\Translation\Repositories;

interface CacheTranslation
{
    /**
     * Get a translation by key and locale from the cache
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function get($key, $locale);
}
