<?php

namespace Hayrullah\RecordErrors;


use Hayrullah\RecordErrors\Commands\CacheCommand;
use Hayrullah\RecordErrors\Commands\ClearCommand;
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
        foreach (glob(__DIR__ . '/Helpers/*.php') as $file) {
            if (file_exists($file)) {
                require_once($file);
            }
        }

        $this->mergeConfigFrom(__DIR__.'/config/record_errors.php','record_errors');

        $this->app->bind('command.record_errors:clear', ClearCommand::class);
        $this->app->bind('command.record_errors:cache', CacheCommand::class);
        $this->commands(['command.record_errors:clear','command.record_errors:cache',]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

//        if (! class_exists('CreateRecordErrorsTable')) {
//            $this->publishes([
//                __DIR__.'/../database/migrations/create_record_errors_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_record_errors_table.php'),
//            ], 'migrations');
//        }
//
//        if (! class_exists('CreateVisitsTable')) {
//            $this->publishes([
//                __DIR__.'/../database/migrations/create_visits_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_visits_table.php'),
//            ], 'migrations');
//        }

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views/backend/record_errors', 'RecordErrors');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([__DIR__.'/config/record_errors.php' => config_path('record_errors.php'),], 'config');

    }
}
