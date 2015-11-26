<?php namespace Modules\Translation\Services;

use Modules\Translation\Repositories\FileTranslationRepository;
use Modules\Translation\Repositories\TranslationRepository;

class TranslationsService
{
    /**
     * @var FileTranslationRepository
     */
    private $fileTranslations;
    /**
     * @var TranslationRepository
     */
    private $databaseTranslations;

    public function __construct()
    {
        $this->fileTranslations = app(FileTranslationRepository::class);
        $this->databaseTranslations = app(TranslationRepository::class);
    }

    /**
     * Get the file translations & the database translations, overwrite the file translations by db translations
     * @return array
     */
    public function getFileAndDatabaseMergedTranslations()
    {
        $allFileTranslations = $this->fileTranslations->all();
        $allDatabaseTranslations = $this->databaseTranslations->allFormatted();
        foreach ($allFileTranslations as $locale => $fileTranslation) {
            foreach ($fileTranslation as $key => $translation) {
                if (isset($allDatabaseTranslations[$locale][$key])) {
                    $allFileTranslations[$locale][$key] = $allDatabaseTranslations[$locale][$key];
                }
            }
        }

        $activeLocales = $this->getActiveLocales();
        foreach ($allFileTranslations as $locale => $value) {
            if (! in_array($locale, $activeLocales)) {
                unset($allFileTranslations[$locale]);
            }
        }
        return $allFileTranslations;
    }

    private function getActiveLocales()
    {
        $locales = [];

        foreach (config('laravellocalization.supportedLocales') as $locale => $translation) {
            $locales[] = $locale;
        }

        return $locales;
    }
}
