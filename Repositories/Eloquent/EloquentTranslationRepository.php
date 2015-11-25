<?php namespace Modules\Translation\Repositories\Eloquent;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Translation\Repositories\DatabaseTranslationRepository;

class EloquentTranslationRepository extends EloquentBaseRepository implements DatabaseTranslationRepository
{
    public function all()
    {
        $allRows = $this->model->all();
        $allDatabaseTranslations = [];
        foreach ($allRows as $translation) {
            foreach (config('laravellocalization.supportedLocales') as $locale => $language) {
                if ($translation->hasTranslation($locale)) {
                    $allDatabaseTranslations[$locale][$translation->key] = $translation->translate($locale)->value;
                }
            }
        }

        return $allDatabaseTranslations;
    }

    private function getModuleNameFrom($fullKey)
    {
        return substr($fullKey, 0, strpos($fullKey, '::'));
    }
}
