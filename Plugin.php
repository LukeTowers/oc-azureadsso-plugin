<?php namespace LukeTowers\AzureADSSO;

use App;
use View;
use Event;
use Config;
use System\Classes\PluginBase;
use System\Classes\CombineAssets;
use Illuminate\Foundation\AliasLoader;
use Backend\Controllers\Auth as AuthController;

/**
 * AzureADSSO Plugin Information File
 */
class Plugin extends PluginBase
{
    public $elevated = true;

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'AzureAD SSO',
            'description' => 'Adds support for logging into the backend with Azure AD SSO OAuth',
            'author'      => 'LukeTowers',
            'icon'        => 'icon-lock'
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        App::register(\SocialiteProviders\Manager\ServiceProvider::class);
        App::register(ServiceProvider::class);

        $this->app->bind('Illuminate\Contracts\Auth\Factory', function () {
            return \Backend\Classes\AuthManager::instance();
        });

        AuthController::extend(function($controller) {
            $controller->bindEvent('page.beforeDisplay', function ($action, $params) use ($controller) {
                if ($action === 'signin') {
                    $controller->addCss(CombineAssets::combine(['azureadsso.css'], plugins_path('luketowers/azureadsso/assets/css/')));
                }
            });
        });

        Event::listen('backend.auth.extendSigninView', function ($controller) {
            return View::make("luketowers.azureadsso::login");
        });

        $this->bootPackages();
    }

    /**
     * Boots (configures and registers) any packages found within this plugin's packages.load configuration value
     *
     * @see https://luketowers.ca/blog/how-to-use-laravel-packages-in-october-plugins
     * @author Luke Towers <octobercms@luketowers.ca>
     */
    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }

            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    App::register($provider);
                }
            }

            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }
}
