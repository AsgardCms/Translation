<?php namespace Modules\Translation\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface TranslationRepository extends BaseRepository
{
    /**
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function findByKeyAndLocale($key, $locale = null);
}
