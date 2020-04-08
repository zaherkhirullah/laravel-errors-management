<?php

use Illuminate\Support\Facades\Route;

Route::group([
    //    'middleware' => $middleware,
    'prefix'    => 'lem',
    'namespace' => 'Hayrullah\Lem\Http\Controllers',
], function () {
    Route::get('/', 'LemController@dashboard')->name('lem.dashboard');
    Route::get('records/{code}', 'LemController@index')->name('lem.records');
    Route::get('records/{code}/{id}', 'LemController@show')->name('lem.show');
    Route::prefix('ajax')->group(function () {
        Route::post('record/{code}', 'LemController@store');
    });
    Route::prefix('example')->group(function () {
        Route::get('{code}', 'LemController@example');
    });
});
