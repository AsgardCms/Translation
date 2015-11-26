<?php namespace Modules\Translation\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Translation\Repositories\TranslationRepository;

class CacheTranslationDecorator extends BaseCacheDecorator implements TranslationRepository
{
    public function __construct(TranslationRepository $recipe)
    {
        parent::__construct();
        $this->entityName = 'translation.translations';
        $this->repository = $recipe;
    }

    /**
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function findByKeyAndLocale($key, $locale = null)
    {
        return $this->cache
            ->tags($this->entityName, 'global')
            ->rememberForever("{$this->locale}.{$this->entityName}.findByKeyAndLocale.{$key}.{$locale}",
                function () use ($key, $locale) {
                    return $this->repository->findByKeyAndLocale($key, $locale);
                }
            );
    }
}
