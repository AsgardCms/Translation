<?php namespace Modules\Translation\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Modules\Translation\Console\BuildTranslationsCacheCommand;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Repositories\Cache\CacheTranslationDecorator;
use Modules\Translation\Repositories\Eloquent\EloquentTranslationRepository;
use Modules\Translation\Repositories\File\FileTranslationRepository as FileDiskTranslationRepository;
use Modules\Translation\Repositories\FileTranslationRepository;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\TranslationLoader;
use Modules\Translation\Services\Translator;
use Schema;

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
        $this->registerValidators();

        if ($this->shouldRegisterCustomTranslator()) {
            $this->registerCustomTranslator();
        }
    }

    /**
     * Should we register the Custom Translator?
     * @return bool
     */
    protected function shouldRegisterCustomTranslator()
    {
        if (false === config('app.translations-gui', true)) {
            return false;
        }

        if (false === app('asgard.isInstalled')) {
            return false;
        }

        if (false === Schema::hasTable((new Translation)->getTable())) {
            return false;
        }

        return true;
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

        $this->app->bind(FileTranslationRepository::class, function ($app) {
            return new FileDiskTranslationRepository($app['files'], $app['translation.loader']);
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
        $this->app->offsetUnset('translation.loader');
        $this->app->offsetUnset('translator');

        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    private function registerValidators()
    {
        Validator::extend('extensions', function ($attribute, $value, $parameters) {
            return in_array($value->getClientOriginalExtension(), $parameters);
        });

        Validator::replacer('extensions', function ($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':values'], [$attribute, implode(',', $parameters)], $message);
        });
    }
}
