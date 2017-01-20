<?php namespace Modules\Translation\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Composers\CurrentUserViewComposer;
use Modules\Translation\Console\BuildTranslationsCacheCommand;
use Modules\Translation\Entities\Translation;
use Modules\Translation\Repositories\Cache\CacheTranslationDecorator;
use Modules\Translation\Repositories\Eloquent\EloquentTranslationRepository;
use Modules\Translation\Repositories\File\FileTranslationRepository as FileDiskTranslationRepository;
use Modules\Translation\Repositories\FileTranslationRepository;
use Modules\Translation\Repositories\TranslationRepository;
use Modules\Translation\Services\TranslationLoader;
use Modules\Translation\Services\Translator;
use Illuminate\Validation\Factory;
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

        view()->composer('translation::admin.translations.index', CurrentUserViewComposer::class);
    }

    public function boot()
    {
        $this->registerValidators();

        if ($this->shouldRegisterCustomTranslator()) {
            $this->registerCustomTranslator();
            $this->registerCustomValidator();
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

            if (! config('app.cache')) {
                return $repository;
            }

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

    /**
     * We have to override Laravel's Validator Factory so it uses our custom Translator instance.
     * Unfortunately we can't just change the Translator on the Factory as the API doesn't
     * expose the property or a setter.
     */
    protected function registerCustomValidator()
    {
        $this->app->offsetUnset('validator');

        $this->app->singleton('validator', function ($app) {
            $validator = new Factory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
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
