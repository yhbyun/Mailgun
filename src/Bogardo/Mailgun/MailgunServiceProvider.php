<?php

namespace Bogardo\Mailgun;

use Illuminate\Support\ServiceProvider;

class MailgunServiceProvider extends ServiceProvider
{
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
        $this->publishes([
            __DIR__.'/../../config/config.php' => $this->getConfigPath(),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['mailgun'] = $this->app->share(function ($app) {
            return new Mailgun($app['view']);
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Mailgun', 'Bogardo\Mailgun\Facades\Mailgun');
        });

        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'mailgun'
        );
    }

    private function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }

    private function getConfigPath()
    {
        if ($this->isLumen()) {
            return base_path('config/mailgun.php');
        } else {
            return config_path('mailgun.php');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mailgun'];
    }
}
