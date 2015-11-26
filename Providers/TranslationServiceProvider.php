<?php namespace Modules\Translation\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Translation\Console\BuildTranslationsCacheCommand;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Repositories\Cache\CacheTranslationDecorator;
use Modules\Translation\Repositories\Cache\CacheTranslationRepository;
use Modules\Translation\Repositories\CacheTranslation;
use Modules\Translation\Repositories\DatabaseTranslationRepository;
use Modules\Translation\Repositories\Eloquent\EloquentTranslationRepository;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\Translator;

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

    public function boot()
    {
        if (true === config('asgard.translation.config.translations-gui')) {
            $this->registerCustomTranslator();
        }
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
        $this->app->bind(TranslationRepository::class, function () {
            $repository = new EloquentTranslationRepository(new Translation());

            return new CacheTranslationDecorator($repository);
        });
    }

    private function registerConsoleCommands()
    {
        $this->commands([
            BuildTranslationsCacheCommand::class,
        ]);
    }

    protected function registerCustomTranslator()
    {
        $this->app->offsetUnset('translator');

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
