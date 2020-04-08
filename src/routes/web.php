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
    Route::delete('records/{id}', 'LemController@destroy')->name('lem.destroy');
    Route::post('records/restore/{id}', 'LemController@restore')->name('lem.restore');
    Route::post('records/force-delete/{id}', 'LemController@forceDelete')->name('lem.forceDelete');
    Route::prefix('ajax')->group(function () {
        Route::post('record/{code}', 'LemController@store');
    });
    Route::prefix('example')->group(function () {
        Route::get('{code}', 'LemController@example');
    });
});
