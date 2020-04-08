<?php

use Illuminate\Support\Facades\Route;

Route::group([
    //    'middleware' => $middleware,
    'prefix'    => 'errors-management',
    'namespace' => 'Hayrullah\ErrorsManagement\Http\Controllers',
], function () {
    Route::get('/', 'RecordErrorController@dashboard')->name('error-records.dashboard');
    Route::get('records/{code}', 'RecordErrorController@index')->name('errors_management:records');
    Route::get('records/{code}/{id}', 'RecordErrorController@show')->name('errors_management:show');
    Route::prefix('ajax')->group(function () {
        Route::post('record/{code}', 'RecordErrorController@store');
    });
});
