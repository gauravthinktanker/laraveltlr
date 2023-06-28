<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function () {
    Route::get(
        'timezones/{timezone}',
        'laraveltlr\tlr\TlrController@index'
    );
});
