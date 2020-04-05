<?php

namespace Hayrullah\RecordErrors;


use App\Console\Commands\ClearCommand;
use Illuminate\Support\ServiceProvider;

class RecordErrorServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(__DIR__ . '/Http/Helpers/*.php') as $file) {
            if (file_exists($file)) {
                require_once($file);
            }
        }

        $this->mergeConfigFrom(__DIR__.'/config/record_errors.php','record_errors');

        $this->app->bind('command.record_errors:clear', ClearCommand::class);

        $this->commands(['command.record_errors:clear',]);
        $this->commands(['command.record_errors:cache',]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([        __DIR__.'/config/record_errors.php' => config_path('record_errors.php'),], 'config');

        if (! class_exists('CreateVisitsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_visits_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_visits_table.php'),
            ], 'migrations');
        }


        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
//        $this->loadViewsFrom(__DIR__ . '/views/frontend/record_errors', 'RecordErrors');
        $this->loadViewsFrom(__DIR__ . '/views/backend/record_errors', 'RecordErrors');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
