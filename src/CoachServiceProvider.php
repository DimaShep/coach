<?php namespace Shep\Coach;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Shep\Coach\Console\Commands\CoachCommand;
use Illuminate\Database\Seeder;

/**
 * The CoachServiceProvider class
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */
class CoachServiceProvider extends ServiceProvider
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
        // Bootstrap handles
//        $this->publishConfig();
//        $this->configHandle();
        $this->langHandle();
        $this->viewHandle();
//        $this->assetHandle();
//        $this->routeHandle();

        app('router')->aliasMiddleware('coach', \Shep\Coach\Http\Middleware\CoachMiddleware::class);
        //$this->registerSeedsFrom('TDatabaseSeeder');
//        include __DIR__.'/routes/routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $this->mergeConfig();
        $this->app->singleton('coach', function ($app) {
            return new Coach;
        });

//        $this->app->singleton('command.coach', function ($app) {
//            return new CoachCommand;
//        });

        if ($this->app->runningInConsole()) {
            $this->configHandle();
            $this->migrationHandle();
            $this->registerConsoleCommands();
            $this->registerPublishableResources();
        }

//        $this->commands('command.coach');
    }

//    private function mergeConfig()
//    {
//        $path = __DIR__.'/config/coach.php';
////        $path     = config_path('coach.php');
//        $this->mergeConfigFrom($path, 'coach');
//    }
//
//    private function publishConfig()
//    {
//        $path = __DIR__.'/config/coach.php';
////        $path     = config_path('coach.php');
//        $this->publishes([$path => config_path('coach.php')], 'config');
//    }

    private function registerConsoleCommands()
    {
        $this->commands(Console\Commands\InstallCommand::class);
        $this->commands(Console\Commands\RestartPositionCommand::class);
        $this->commands(Console\Commands\EraseMediaResultCommand::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'coach',
            'command.coach',
        ];
    }

    /**
     * Loading package routes
     *
     * @return void
     */
    protected function routeHandle()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/routes_site.php');
    }


    private function registerPublishableResources()
    {
        $publishablePath = dirname(__DIR__).'/src';

        $publishable = [
            'public' => [
                "{$publishablePath}/resources/public/" => storage_path('app/public/coach'),
            ],
            'assets' => [
                "{$publishablePath}/resources/assets/" => public_path(config('coach.assets_path')),
            ],
            'seeds' => [
                "{$publishablePath}/database/seeds/" => database_path('seeds'),
            ]

        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    /**
     * Loading and publishing package's config
     *
     * @return void
     */
    protected function configHandle()
    {
        $packageConfigPath = __DIR__.'/config/coach.php';
        $appConfigPath     = config_path('coach.php');

        $this->mergeConfigFrom($packageConfigPath, 'coach');

        $this->publishes([
            $packageConfigPath => $appConfigPath,
        ], 'config');
    }

    /**
     * Loading and publishing package's translations
     *
     * @return void
     */
    protected function langHandle()
    {
        $packageTranslationsPath = __DIR__.'/resources/lang';

        $this->loadTranslationsFrom($packageTranslationsPath, 'coach');

        $this->publishes([
            $packageTranslationsPath => resource_path('lang/vendor/coach'),
        ], 'coach');
    }

    /**
     * Loading and publishing package's views
     *
     * @return void
     */
    protected function viewHandle()
    {
        $packageViewsPath = __DIR__.'/resources/views';

        $this->loadViewsFrom($packageViewsPath, 'coach');

        $this->publishes([
            $packageViewsPath => resource_path('views/vendor/coach'),
        ], 'views');
    }

    /**
     * Publishing package's assets (JavaScript, CSS, images...)
     *
     * @return void
     */
    protected function assetHandle()
    {
        $packageAssetsPath = __DIR__.'/resources/assets';

        $this->publishes([
            $packageAssetsPath => public_path('vendor/coach'),
        ], 'public');

        $packageAssetsPath = __DIR__.'/resources/public';

        $this->publishes([
            $packageAssetsPath => storage_path('app/public/coach'),
        ], 'public');
    }


    /**
     * Publishing package's migrations
     *
     * @return void
     */
    protected function migrationHandle()
    {
        $packageMigrationsPath = __DIR__.'/database/migrations';

        $this->loadMigrationsFrom($packageMigrationsPath);

        $this->publishes([
            $packageMigrationsPath => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register seeds.
     *
     * @return void
     */
    protected function registerSeedsFrom($class)
    {
        $packageSeedsPath = __DIR__.'/database/seeds/';
        if (!class_exists($class)) {
            require_once $packageSeedsPath.$this->seedersPath.$class.'.php';
        }

        with(new $class())->run();
    }

//        $packageSeedsPath = __DIR__.'/database/seeds';
//
//        foreach (glob("$packageSeedsPath/*.php") as $filename)
//        {
//            include $filename;
//            $classes = get_declared_classes();
//            $class = end($classes);
//            if(!strstr($filename, $class))
//                $class = prev($classes);//end($classes);
//
//            $command = Request::server('argv', null);
//            if (is_array($command)) {
//                $command = implode(' ', $command);
//                if ($command == "artisan db:seed") {
//                    Artisan::call('db:seed', ['--class' => $class]);
//                }
//            }
//
//        }
//    }
}
