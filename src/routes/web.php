<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\Hayrullah\ErrorsManagement\Http\Controllers')->group(function () {
    Route::prefix('errors-management')->group(function () {
        Route::get('', 'RecordErrorController@dashboard')->name('error-records.dashboard');
        Route::get('records/{code}', 'RecordErrorController@index')->name('errors_management:records');
        Route::get('records/{code}/{id}', 'RecordErrorController@show')->name('errors_management:show');
    });
    Route::prefix('ajax')->group(function () {
        Route::post('error-record/{code}', 'RecordErrorController@store');
    });
});
