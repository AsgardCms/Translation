<?php namespace Modules\Translation\Repositories\Cache;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Modules\Translation\Repositories\CacheTranslation;

class CacheTranslationRepository implements CacheTranslation
{
    /**
     * @var Repository
     */
    private $cache;

    public function __construct()
    {
        $this->cache = app(Repository::class);
    }

    /**
     * Get a translation by key and locale from the cache
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function get($key, $locale)
    {
        if ($allTranslations = $this->cache->get('allTranslations')) {
            return $allTranslations->getTranslation($key, $locale);
        }

        return null;
    }
}
