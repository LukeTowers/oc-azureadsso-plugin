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
        AuthController::extend(function($controller) {
            $controller->bindEvent('page.beforeDisplay', function ($action, $params) {
                if ($action === 'params') {
                    $controller->addCss(CombineAssets::combine(['azureadsso.css'], plugins_path('luketowers/azureadsso/assets/css/')));
                }
            });
        });

        Event::listen('backend.auth.extendSigninView', function($controller) {
            return View::make("luketowers.azureadsso::login");
        });

        $this->bootPackages();
        $this->extendAzureAD();
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

    /**
     * Extend the base library used to make it compatible with OctoberCMS
     *
     * @return void
     */
    protected function extendAzureAD()
    {
        // Process the user object before saving it
        \Metrogistics\AzureSocialite\UserFactory::userCallback(function($newUser) {
            // Generate a random password for the user
            $pass = str_random(60);
            $newUser->password = $pass;
            $newUser->password_confirmation = $pass;

            // Ensure that the user has an email address
            if (empty($newUser->email) && !empty($newUser->alt_email)) {
                $newUser->email = $newUser->alt_email;
            }

            // @TODO: Enable assigning a default role to new users

            // Clean up
            unset($newUser->attributes['alt_email']);
        });
    }
}
