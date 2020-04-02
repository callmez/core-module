<?php

namespace Modules\Core\Providers;

use Modules\Core\Auth\Guards\AdminGuard;
use Modules\Core\Captcha\Captcha;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Config\Repository as ConfigRepository;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Core';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'core';

    /**
     * @var string
     */
    protected $modulePath = __DIR__ . '/../..';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
//        if (!$this->app->runningInConsole()) {
//            /** @var Kernel $kernel */
//            $kernel = $this->app->make(Kernel::class);
//            $kernel->prependMiddleware();
//        }

        $this->registerCaptcha();
        $this->registerValidators();
        $this->registerTranslations();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
    }

    protected function registerCaptcha()
    {
        // Bind captcha
        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }

    protected function registerValidators()
    {
        /** @var ValidationFactory $validator */
        $validator = $this->app['validator'];

        $validator->extend('captcha', function ($attribute, $value, $parameters) {
            return \Captcha::check($value);
        });

        $validator->extend('captcha_api', function ($attribute, $value, $parameters) {
            return \Captcha::check_api($value, $parameters[0]);
        });
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if ( ! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path($this->moduleName, 'database/factories'));
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerHelpers();

        $this->app->register(RouteServiceProvider::class); // TODO 加载顺序调整
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // 覆盖使用新的config设置
        $this->app->extend('config', function($config) {
            $newConfig = new ConfigRepository($config->all());
            $newConfig->loadSettingsFromCachedFile();

            return $newConfig;
        });

//        $this->publishes([
//            $this->modulePath . '/config/core.php' => config_path($this->moduleNameLower . '.php'),
//        ], 'config');
//        $this->mergeConfigFrom(
//            $this->modulePath . '/config/core.php', $this->moduleNameLower
//        );

        $this->publishes([
            $this->modulePath . '/config/captcha.php' => config_path('captcha.php'),
        ], 'config');
        $this->mergeConfigFrom(
            $this->modulePath . '/config/captcha.php', 'captcha'
        );

        if ( ! $this->app->configurationIsCached()) {
            config([
                'auth.guards' => array_merge([
                    'admin_web' => [
                        'driver'   => 'session',
                        'provider' => 'admin_users',
                    ],

                    'admin' => [
                        'driver'   => 'sanctum',
                        'provider' => 'admin_users',
                    ],
                ], config('auth.guards', [])),
            ]);

            config([
                'auth.providers' => array_merge([
                    'admin_users' => [
                        'driver' => 'eloquent',
                        'model'  => \Module\Core\Models\Admin\AdminUser::class,
                    ],
                ], config('auth.providers', [])),
            ]);
        }

        $this->configureGuard();
    }

    /**
     * Configure the admin authentication guard.
     *
     * @return void
     */
    protected function configureGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->viaRequest('admin', new AdminGuard($auth, config('core::admin.guard_expiration')));
        });
    }

    public function registerHelpers()
    {
        require $this->modulePath . '/src/helpers.php';
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
