<?php namespace Modules\Translation\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Translation\Events\TranslationNotFoundInCache;
use Modules\Translation\Repositories\CacheTranslation;
use Modules\Translation\Repositories\TranslationRepository;

class Translator extends \Illuminate\Translation\Translator
{
    use DispatchesJobs;
    /**
     * Have we already triggered the Cache Rebuild on this page load?
     * This stops the cache rebuild being triggered hundreds of times on the same page load if multiple items can't be found.
     * @var bool
     */
    protected $cacheRebuildQueued = false;
    /**
     * @var array Keys to ignore, don't rebuild cache if these keys cant be found.
     */
    protected $ignore = [
        'core::sidebar.content'
    ];
    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @param  bool  $fallback
     * @return string
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        /** @var CacheTranslation $cacheTranslation */
        $cacheTranslation = app(CacheTranslation::class);
        if ($translation = $cacheTranslation->get($key, $locale)) {
            return $this->makeReplacements($translation, $replace);
        }
        if ($this->shouldRebuildCache($key)) {
            event(new TranslationNotFoundInCache($key));
            $this->cacheRebuildQueued = true;
        }
        return parent::get($key, $replace, $locale);
    }
    /**
     * Should we trigger a Translation cache rebuild?
     * @param string $key   Translation Key
     * @return bool
     */
    protected function shouldRebuildCache($key)
    {
        if (! app()->isBooted()) {
            return false;
        }
        if ($this->isTranslationCachedInEnglish($key)) {
            return false;
        }
        if (in_array($key, $this->ignore)) {
            return false;
        }
        if ($this->cacheRebuildQueued === false) {
            return true;
        }
        return false;
    }
    /**
     * Do we have an English version of the key? If we do, it usually means that
     * there hasn't been a translation entered in the CMS, so we don't need to rebuild the
     * translation cache just because it's not been entered in the CMS.
     * @param $key
     * @return bool
     */
    protected function isTranslationCachedInEnglish($key)
    {
        $translation = parent::get($key, [], 'en');
        return $key !== $translation;
    }
}
