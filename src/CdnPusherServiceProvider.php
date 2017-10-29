<?php

namespace Ty666\CdnPusher;

use App\Console\Commands\EmptyCommand;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Ty666\CdnPusher\Asset\ExcludeAsset;
use Ty666\CdnPusher\Asset\IncludeAsset;
use Ty666\CdnPusher\Console\PushCommand;

class CdnPusherServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
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
        $this->publishes([
            __DIR__.'/../config/cdn.php' => config_path('cdn.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cdn::class, function (){
            $config = config('cdn');
            $includeAsset = new IncludeAsset($config['include']);
            $excludeAsset = new ExcludeAsset($config['exclude']);
            return new Cdn(Finder::create(), $includeAsset, $excludeAsset);
        });
        $this->registerCommand();
    }

    public function registerCommand(){
        $this->app->singleton('cdn_pusher.push', function (){
            return new PushCommand($this->app->make(Cdn::class));
        });
        $this->commands('cdn_pusher.push');

        $this->app->singleton('cdn_pusher.empty', function (){
            return new EmptyCommand($this->app->make(Cdn::class));
        });
        $this->commands('cdn_pusher.empty');
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
