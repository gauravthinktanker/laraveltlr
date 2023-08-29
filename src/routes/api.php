<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api'], function () {

Route::post(
    'timetracker',
    'laraveltlr\tlr\TlrController@Timetracker'
)->name('timetracker');

Route::post(
    'dailyrecord',
    'laraveltlr\tlr\TlrController@Dailyreport'
)->name('dailyrecord');


Route::post(
    'login',
    'laraveltlr\tlr\TlrController@loginTimetracker'
)->name('loginTimetracker');


})
?>