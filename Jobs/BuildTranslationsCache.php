<?php namespace Modules\Translation\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Modules\Translation\Repositories\DatabaseTranslationRepository;
use Modules\Translation\Repositories\FileTranslationRepository;
use Modules\Translation\ValueObjects\TranslationGroup;

class BuildTranslationsCache extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var FileTranslationRepository
     */
    private $fileTranslations;
    /**
     * @var DatabaseTranslationRepository
     */
    private $databaseTranslations;

    public function __construct()
    {
        $this->fileTranslations = app(FileTranslationRepository::class);
        $this->databaseTranslations = app(DatabaseTranslationRepository::class);
    }

    public function handle()
    {
        $this->cacheAll(new TranslationGroup($this->getFileAndDatabaseMergedTranslations()));

        logger()->info('All translations have been cached.');

        $this->delete();
    }

    /**
     * Cache the given TranslationGroup
     * @param TranslationGroup $group
     */
    public function cacheAll(TranslationGroup $group)
    {
        Cache::rememberForever('allTranslations', function () use ($group) {
           return $group;
        });
    }

    /**
     * Get the file translations & the database translations, overwrite the file translations by db translations
     * @return array
     */
    private function getFileAndDatabaseMergedTranslations()
    {
        $allFileTranslations = $this->fileTranslations->all();
        $allDatabaseTranslations = $this->databaseTranslations->all();

        foreach ($allFileTranslations as $locale => $fileTranslation) {
            foreach ($fileTranslation as $key => $translation) {
                if (isset($allDatabaseTranslations[$locale][$key])) {
                    $allFileTranslations[$locale][$key] = $allDatabaseTranslations[$locale][$key];
                }
            }
        }
        return $allFileTranslations;
    }
}
