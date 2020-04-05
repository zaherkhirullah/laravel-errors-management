<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Hayrullah\RecordErrors\Http\Controllers')->group(function () {

    Route::get('/{code}', function ($code) {
        switch ($code) {
            case "401":
            case "403":
            case "404":
            case "419":
            case "429":
            case "500":
            case "503":
                abort($code);
                break;
            default:
                abort(404);
                break;
        }
    });
    Route::prefix('admin')->group(function () {
        Route::get('error-management', "RecordErrorController@dashboard")->name('error-records.dashboard');
        Route::get('error-records/{code}', "RecordErrorController@index")->name('error-records');
//        Route::get('error-records/{code}/{trash?}', "RecordErrorController@index")->name('error-records');
        Route::get('error-records/{code}/{id}', "RecordErrorController@show")->name('error-records.show');
        //    Route::post('error-records/{code}/store', "RecordErrorController@store");
        //    Route::get('error-records/{code}', 'RecordErrorController@index');
        //    Route::resource('error-records/{code}', 'RecordErrorController');
    });
    Route::prefix('ajax')->group(function () {
        Route::post('error-record/{code}', 'RecordErrorController@store');
    });
});
