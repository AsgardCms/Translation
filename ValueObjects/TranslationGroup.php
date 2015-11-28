<?php namespace Modules\Translation\ValueObjects;

use Illuminate\Support\Collection;

class TranslationGroup
{
    /**
     * @var Collection
     */
    private $translations;

    public function __construct(array $translations)
    {
        $this->translations = $this->reArrangeTranslations($translations);
    }

    /**
     * @param array $translationsRaw
     * @return Collection
     */
    private function reArrangeTranslations(array $translationsRaw)
    {
        $translations = [];

        foreach ($translationsRaw as $locale => $translationGroup) {
            foreach ($translationGroup as $key => $translation) {
                $translations[$key][$locale] = $translation;
            }
        }

        return new Collection($translations);
    }

    /**
     * Return the translations
     * @return Collection
     */
    public function all()
    {
        return $this->translations;
    }
}
