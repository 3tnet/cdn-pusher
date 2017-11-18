<?php

namespace Ty666\CdnPusher;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Ty666\CdnPusher\Asset\ExcludeAsset;
use Ty666\CdnPusher\Asset\IncludeAsset;
use Ty666\CdnPusher\Console\ClearCommand;
use Ty666\CdnPusher\Console\PushCommand;

class CdnPusherServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferared.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        $this->setupConfig();
    }

    public function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/cdn.php');

        $this->publishes([
            $source => config_path('cdn.php'),
        ]);

        $this->mergeConfigFrom($source, 'cdn');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cdn::class, function () {
            $config = config('cdn');
            $includeAsset = new IncludeAsset($config['include']);
            $excludeAsset = new ExcludeAsset($config['exclude']);
            return new Cdn(Finder::create(), $includeAsset, $excludeAsset);
        });
        $this->registerCommand();
    }

    public function registerCommand()
    {
        $this->app->singleton('cdn_pusher.push', function () {
            return new PushCommand($this->app->make(Cdn::class));
        });
        $this->commands('cdn_pusher.push');

        $this->app->singleton('cdn_pusher.clear', function () {
            return new ClearCommand($this->app->make(Cdn::class));
        });
        $this->commands('cdn_pusher.clear');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Cdn::class];
    }
}
