<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\Hayrullah\ErrorManagement\Http\Controllers')->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('errors-management', "RecordErrorController@dashboard")->name('error-records.dashboard');
        Route::get('error-records/{code}', "RecordErrorController@index")->name('error-records');
        Route::get('error-records/{code}/{id}', "RecordErrorController@show")->name('error-records.show');
    });
    Route::prefix('ajax')->group(function () {
        Route::post('error-record/{code}', 'RecordErrorController@store');
    });
});
