<?php namespace Modules\Translation\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\Translation\Repositories\TranslationRepository;

class CacheTranslationDecorator extends BaseCacheDecorator implements TranslationRepository
{
    public function __construct(TranslationRepository $translation)
    {
        parent::__construct();
        $this->entityName = 'translation.translations';
        $this->repository = $translation;
    }
}
