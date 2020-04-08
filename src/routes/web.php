<?php

use Illuminate\Support\Facades\Route;

Route::group([
    //    'middleware' => $middleware,
    'prefix'    => 'lem',
    'namespace' => 'Hayrullah\ErrorsManagement\Http\Controllers',
], function () {
    Route::get('/', 'RecordErrorController@dashboard')->name('lem.dashboard');
    Route::get('records/{code}', 'RecordErrorController@index')->name('lem.records');
    Route::get('records/{code}/{id}', 'RecordErrorController@show')->name('lem.show');
    Route::prefix('ajax')->group(function () {
        Route::post('record/{code}', 'RecordErrorController@store');
    });
    Route::prefix('example')->group(function () {
        Route::get('{code}', 'RecordErrorController@example');
    });
});
