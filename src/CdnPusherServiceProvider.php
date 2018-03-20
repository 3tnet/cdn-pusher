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
        $this->app->bind(Cdn::class, function ($app, $params = null) {
            $config = config('cdn');

            if (is_null($params['rule'])) {
                $rule = $config['default_rule'];
            } else {
                $rule = $params['rule'];
            }

            if (!isset($config['rules'][$rule])) {
                throw new \Exception($rule . ' cdn rule 不存在');
            }
            $includeAsset = new IncludeAsset($config['rules'][$rule]['include']);
            $excludeAsset = new ExcludeAsset($config['rules'][$rule]['exclude']);
            return new Cdn(Finder::create(), $includeAsset, $excludeAsset);
        });
        $this->registerCommand();
    }

    public function registerCommand()
    {
        $this->app->singleton('cdn_pusher.push', function () {
            return new PushCommand();
        });
        $this->commands('cdn_pusher.push');

        $this->app->singleton('cdn_pusher.clear', function () {
            return new ClearCommand();
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
