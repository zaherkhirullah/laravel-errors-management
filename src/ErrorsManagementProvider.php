<?php

namespace Hayrullah\ErrorsManagement;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

class ErrorsManagementProvider extends ServiceProvider
{
    protected $CONFIG_PATH = 'config/';
    protected $ROUTES_PATH = 'routes/';
    protected $RESOURCE_PATH = 'resources/';
    protected $DATABASE_PATH = '../database/';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHelpers();

        $this->registerBladeExtensions();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Filesystem $filesystem)
    {
        $this->loadRoutes();

        $this->loadAssets();

        $this->loadViews();

        $this->loadTranslations();

        $this->loadConfig();

        $this->loadMigrations();

        $this->registerCommands();

        $this->registerResources($filesystem);

//        $this->registerModels();
    }

    private function loadRoutes()
    {
        $routesPath = $this->packagePath($this->ROUTES_PATH.'web.php');
        $this->loadRoutesFrom($routesPath);
    }

    private function loadAssets()
    {
        $viewsPath = $this->packagePath($this->RESOURCE_PATH.'/assets/errors-management');
        $this->loadViewsFrom($viewsPath, 'errors_management');
    }

    private function loadViews()
    {
        $viewsPath = $this->packagePath($this->RESOURCE_PATH.'/views/errors-management');
        $this->loadViewsFrom($viewsPath, 'errors_management');
    }

    private function loadTranslations()
    {
        $translationsPath = $this->packagePath($this->RESOURCE_PATH.'lang');
        $this->loadTranslationsFrom($translationsPath, 'errors_management');
    }

    private function loadConfig()
    {
        $configPath = $this->packagePath($this->CONFIG_PATH.'errors_management.php');
        $this->mergeConfigFrom($configPath, 'errors_management');
        $configPath = $this->packagePath($this->CONFIG_PATH.'adminlte.php');
        $this->mergeConfigFrom($configPath, 'adminlte');
    }

    private function loadMigrations()
    {
        $migrationsPath = $this->packagePath($this->DATABASE_PATH.'migrations');
        $this->loadMigrationsFrom($migrationsPath);
    }

    protected function registerModels()
    {
        $config = $this->app->config['errors_management.models'];

        if (!$config) {
            return;
        }

        $this->app->bind(Models\RecordError::class, $config['record_error']);
        $this->app->bind(Models\Visit::class, $config['visits']);
    }

    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('recorded', function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                /*                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";*/

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('elserecorded', function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('endrecorded', function () {
                return '<?php endif; ?>';
            });
        });
    }

    /**
     * Register commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands(Commands\ClearCommand::class);
        $this->commands(Commands\CacheCommand::class);
    }

    /**
     * Register helpers.
     *
     * @return void
     */
    public function registerHelpers()
    {
        $helpers = glob($this->packagePath('/Helpers/*.php'));
        foreach ($helpers as $helper) {
            if (file_exists($helper)) {
                require_once $helper;
            }
        }
    }

    /**
     * Register resources.
     *
     * @return void
     */
    public function registerResources($filesystem)
    {
        if ($this->isLumen() === false and function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen

            $this->publishes(
                [
                    $this->packagePath($this->CONFIG_PATH.'errors_management.php') => config_path('record_errors.php'),
                    $this->packagePath($this->CONFIG_PATH.'adminlte.php')          => config_path('adminlte.php'),
                ],
                'lem-config'
            );

            $this->publishes(
                [
                    $this->packagePath($this->DATABASE_PATH.'migrations/create_record_errors_table.php.stub') => $this->getMigrationFileName($filesystem, 'create_record_errors_table.php'),
                    $this->packagePath($this->DATABASE_PATH.'migrations/create_visits_table.php.stub')        => $this->getMigrationFileName($filesystem, 'create_visits_table.php'),
                ],
                'lem-migrations'
            );

            // publish js and css files - vue-file-manager module
            $this->publishes([
                $this->packagePath($this->RESOURCE_PATH.'assets/') => public_path('vendor/errors-management'),
            ], 'lem-assets');

            $this->publishes(
                [
                    $this->packagePath($this->RESOURCE_PATH.'views/errors-management') => resource_path('views/vendor/errors-management'),
                    $this->packagePath($this->RESOURCE_PATH.'views/errors')            => resource_path('views/errors'),
                ],
                'lem-views'
            );

//            $this->publishes(
//                [__DIR__.'/ErrorsManagementProvider.php' => app_path('/Providers/ErrorsManagementProvider.php')],
//                'providers'
//            );
        }
    }

    private function packagePath($path)
    {
        return __DIR__."/$path";
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     *
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem, $fileName): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $fileName) {
                return $filesystem->glob("{$path}*_{$fileName}");
            })->push($this->app->databasePath()."/migrations/{$timestamp}_{$fileName}")
            ->first();
    }

    /**
     * Check if package is running under Lumen app.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen') === true;
    }
}
