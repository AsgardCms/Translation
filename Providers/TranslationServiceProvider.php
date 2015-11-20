<?php namespace Modules\Translation\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(
            'Modules\Translation\Repositories\TranslationRepository',
            function () {
                $repository = new \Modules\Translation\Repositories\Eloquent\EloquentTranslationRepository(new \Modules\Translation\Entities\Translation());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Translation\Repositories\Cache\CacheTranslationDecorator($repository);
            }
        );
// add bindings
    }
}
