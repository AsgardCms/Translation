<?php namespace Modules\Translation\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Translation\Console\BuildTranslationsCacheCommand;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Repositories\Cache\CacheTranslationRepository;
use Modules\Translation\Repositories\CacheTranslation;
use Modules\Translation\Repositories\DatabaseTranslationRepository;
use Modules\Translation\Repositories\Eloquent\EloquentTranslationRepository;
use Modules\Translation\Repositories\File\FileTranslationRepository;
use Modules\Translation\Repositories\FileTranslationRepository as FileTranslationRepositoryInterface;
use Modules\Translation\Repositories\TranslationRepository;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerConsoleCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(DatabaseTranslationRepository::class, function () {
            return new EloquentTranslationRepository(new Translation());
        });
        $this->app->bind(FileTranslationRepositoryInterface::class, function ($app) {
            return new FileTranslationRepository($app['files']);
        });
        $this->app->bind(CacheTranslation::class, function () {
            return new CacheTranslationRepository();
        });
    }

    private function registerConsoleCommands()
    {
        $this->app->bind('command.asgard.build.translations.cache', BuildTranslationsCacheCommand::class);

        $this->commands([
            'command.asgard.build.translations.cache',
        ]);
    }
}
