<?php namespace Modules\Translation\ValueObjects;

class TranslationGroup
{
    /**
     * @var array
     */
    private $translations;

    public function __construct(array $translations)
    {
        $this->translations = $translations;
    }

    /**
     * Get a translation by key and optionally a locale
     * @param string $key
     * @param string|null $locale
     * @return string
     */
    public function getTranslation($key, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        if (isset($this->translations[$locale][$key])) {
            return $this->translations[$locale][$key];
        }

        return '';
    }
}
