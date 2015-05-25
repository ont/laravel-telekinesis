<?php namespace Ont\\Telekinesis;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {

        $this->handleConfigs();
        // $this->handleMigrations();
        // $this->handleViews();
        // $this->handleTranslations();
        $this->handleRoutes();
        $this->handleAssets();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        // Bind any implementations.
        $this->app->make('Ont\Telekinesis\MainController');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {

        return [];
    }

    private function handleConfigs() {

        $configPath = __DIR__ . '/../config/telekinesis.php';

        $this->publishes([
            $configPath => config_path('telekinesis.php')
        ]);

        $this->mergeConfigFrom($configPath, 'telekinesis');
    }

    private function handleTranslations() {

        $this->loadTranslationsFrom('telekinesis', __DIR__.'/../lang');
    }

    private function handleViews() {

        $this->loadViewsFrom('telekinesis', __DIR__.'/../views');

        $this->publishes([__DIR__.'/../views' => base_path('resources/views/ont/telekinesis')]);
    }

    private function handleMigrations() {

        $this->publishes([__DIR__ . '/../migrations' => base_path('database/migrations')]);
    }

    private function handleRoutes() {

        include __DIR__.'/../routes.php';
    }

    private function handleAssets()
    {
        $this->publishes([
            __DIR__ . '/../assets/js' => public_path('ont/js'),
        ], 'public');
    }

}
