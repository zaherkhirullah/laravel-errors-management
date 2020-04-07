<?php

namespace Hayrullah\ErrorManagement;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

class ErrorsManagementProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHelpers();

        if ($this->app->runningInConsole()) {
            $this->registerResources();
            $this->registerCommands();
        }

        $this->mergeConfigFrom(__DIR__.'/../config/record_errors.php', 'record_errors');
        $this->mergeConfigFrom(__DIR__.'/../config/adminlte.php', 'adminlte');

        $this->registerBladeExtensions();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views/errors-management', 'ErrorManagement');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->registerModels();
    }

    protected function registerModels()
    {
        $config = $this->app->config['record_errors.models'];

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
     * Register resources.
     *
     * @return void
     */
    public function registerResources()
    {
        if ($this->isLumen() === false and function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen
            $this->publishes([__DIR__.'/../config/record_errors.php' => config_path('record_errors.php')], 'config');

            $timestamp = date('Y_m_d_His', time());
            if (!class_exists('CreateRecordErrorsTable')) {
                $this->publishes([__DIR__.'/../../database/migrations/2020_04_105050_create_record_errors_table.php' => database_path("migrations/{$timestamp}_create_record_errors_table.php")], 'migrations');
            }

            if (!class_exists('CreateVisitsTable')) {
                $this->publishes([__DIR__.'/../../database/migrations/2020_04_105051_create_visits_table.php' => database_path("migrations/{$timestamp}_create_visits_table.php")], 'migrations');
            }

            $this->publishes([
                __DIR__.'/../resources/views/errors-management' => $this->app->resourcePath('views/vendor/errors-management'),
                __DIR__.'/../resources/views/errors'            => $this->app->resourcePath('views/errors'),
            ], 'errors-management-views');

            $this->publishes([__DIR__.'/../providers/ErrorsManagementProvider.php' => app_path('/Providers/ErrorsManagementProvider.php')], 'providers');
        }
    }

    /**
     * Register commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            Commands\ClearCommand::class,
            Commands\CacheCommand::class,
        ]);
    }

    /**
     * Register helpers.
     *
     * @return void
     */
    public function registerHelpers()
    {
        $helpers = glob(__DIR__.'/../Helpers/*.php');
        foreach ($helpers as $helper) {
            if (file_exists($helper)) {
                require_once $helper;
            }
        }
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
