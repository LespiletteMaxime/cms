<?php namespace Devise;

/**
 * This class loads all the providers in other folders for
 * all of devise. We don't want the developer to have to load
 * 10 different service providers to work with devise, so
 * this class is just a wrapper to load all of the service
 * providers instead of having to load 10 individual ones in
 * the app/config/app.php file
 *
 * This service provider also includes the blade extensions
 */
class DeviseServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * List of config files we need to publish/merge
     *
     * @var array
     */
    protected $configFiles = [
        'devise.languages',
        'devise.media-manager',
        'devise.model-mapping',
        'devise.permissions',
        'devise.templates',
        'devise.zencoder',
    ];

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
    public function boot()
    {
        $this->registerLaravelFormAndHtmlProvider();
        $this->registerConfigPublisher();
        $this->registerDeviseViews();

        // support must be booted first since many
        // things might depend on support
        $this->registerSupport();
        $this->registerDeviseUniversalSearch();
        $this->registerPages();
        $this->registerEncoding();
        $this->registerSidebar();
        $this->registerUsers();

        require __DIR__ . '/../macros/macros.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigOverrideWrapper();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return void
     */
    public function provides()
    {
        return array();
    }

    /**
     * Overrides the config stuff
     * @return [type]
     */
    private function registerConfigOverrideWrapper()
    {
        $overrideFile = $this->app['path.storage'] . '/app/config.overrides.php';

        $items = $this->app['config']->all();

        $overrides = file_exists($overrideFile) ? include $overrideFile : [];

        $this->app['config.overrides.file'] = $overrideFile;

        $this->app['config'] = new Support\Config\Overrides($items, $overrides);
    }

    /**
     * Registers the views with devise
     *
     * @return void
     */
    private function registerDeviseViews()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'devise');
    }

    /**
     * Handles publishing all the config files for devise
     * package
     *
     * @return void
     */
    private function registerConfigPublisher()
    {
        $publishes = [];

        foreach ($this->configFiles as $key)
        {
            $configFile = str_replace('.', DIRECTORY_SEPARATOR, $key);

            $publishes[__DIR__."/../config/{$configFile}.php"] = config_path("{$configFile}.php");
            $this->mergeConfigFrom(__DIR__."/../config/{$configFile}.php", "{$key}");
        }

        // taking out the publishes section below because we are going
        // to publish devise configs from the command php artisan devise:configs
        // $this->publishes($publishes);
    }

    /**
     * The Form and Html Facades are no longer available in L5
     * so we register them here since we need them
     *
     * @return void
     */
    private function registerLaravelFormAndHtmlProvider()
    {
        $provider = new \Illuminate\Html\HtmlServiceProvider($this->app);
        $this->app->register($provider);
        $provider->boot();
    }

    /**
     * Register support service provider
     *
     * @return void
     */
    private function registerSupport()
    {
        $provider = new Support\SupportServiceProvider($this->app);
        $this->app->register($provider);
        $provider->boot();
    }

    /**
     * Registers the pages service provider
     *
     * @return void
     */
    private function registerPages()
    {
        $provider = new Pages\PagesServiceProvider($this->app);
        $this->app->register($provider);
        $provider->boot();
    }

    /**
     * Register encoding service provider
     *
     * @return void
     */
    public function registerEncoding()
    {
        $EncodingProvider = new Media\Encoding\EncodingServiceProvider($this->app);
        $this->app->register($EncodingProvider);
        $EncodingProvider->boot();
    }

    /**
     * Register the sidebar provider
     * @return void
     */
    public function registerSidebar()
    {
        $SidebarProvider = new Sidebar\SidebarServiceProvider($this->app);
        $this->app->register($SidebarProvider);
        $SidebarProvider->boot();
    }
    /**
     * Register universal search service provider
     *
     * @return void
     */
    public function registerDeviseUniversalSearch()
    {
        $DeviseUniversalSearchProvider = new Search\UniversalSearchProvider($this->app);
        $this->app->register($DeviseUniversalSearchProvider);
        $DeviseUniversalSearchProvider->boot();
    }

    /**
     * Register users service provider
     *
     * @return void
     */
    public function registerUsers()
    {
        $UsersProvider = new Users\UserServiceProvider($this->app);
        $this->app->register($UsersProvider);
        $UsersProvider->boot();
    }
}