<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function () {
    Route::get(
        'tlr-month',
        'laraveltlr\tlr\TlrController@index'
    )->name('tlr_month');
    Route::get(
        'tlr-year',
        'laraveltlr\tlr\TlrController@tlrYear'
    )->name('tlr_year');
    Route::get(
        'tlr-point',
        'laraveltlr\tlr\TlrController@tlrAdminMonth'
    )->name('tlr_month_admin');
    Route::post(
        'tlr-month-delete'
        ,'laraveltlr\tlr\TlrController@tlrAdminMonthDelete'
    )->name('point.delete');
    Route::get(
        'tlr-create'
        ,'laraveltlr\tlr\TlrController@tlrCreate'
    )->name('point.create');
    Route::post(
        'tlr-store'
        ,'laraveltlr\tlr\TlrController@tlrStore'
    )->name('point.store');
    Route::get(
        'tlr-points/{month}',
        'laraveltlr\tlr\TlrController@monthset'
    )->name('monthset');
    Route::get(
        'tlr-point/{month_user}',
        'laraveltlr\tlr\TlrController@monthsetuser'
    )->name('monthsetuser');
   

    Route::get(
        'tlr-point-master',
        'laraveltlr\tlr\TlrController@masterindex'
    )->name('pointmaster.index');
    Route::get(
        'tlr-point/{month}/{user_id}',
        'laraveltlr\tlr\TlrController@userset'
    )->name('point.user');

    Route::get(
        'tlr-headers',
        'laraveltlr\tlr\TlrController@topicindex'
    )->name('topic.index');

    Route::get(
        'tlr-headers/create',
        'laraveltlr\tlr\TlrController@topiccreate'
    )->name('topic.create');

    Route::post(
        'tlr-headers/store',
        'laraveltlr\tlr\TlrController@topicstore'
    )->name('topic.store');

    Route::post(
        'tlr-headers/delete',
        'laraveltlr\tlr\TlrController@topicdelete'
    )->name('topic.delete');

    Route::get(
        'tlr-headers/edit/{id}',
        'laraveltlr\tlr\TlrController@topicedit'
    )->name('topic.edit');


    Route::put(
        'tlr-headers/{id}',
        'laraveltlr\tlr\TlrController@topicupdate'
    )->name('topic.update');

    Route::get(
        'services',
        'laraveltlr\tlr\TlrController@services'
    )->name('services.index');

    Route::get(
        'services-create',
        'laraveltlr\tlr\TlrController@servicesCreate'
    )->name('services.create');

    Route::post(
        'services-store',
        'laraveltlr\tlr\TlrController@servicesStore'
    )->name('services.store');

    Route::get(
        'services-edit/{id}',
        'laraveltlr\tlr\TlrController@servicesEdit'
    )->name('services.edit');

    Route::put(
        'services-update/{id}',
        'laraveltlr\tlr\TlrController@servicesUpadate'
    )->name('services.update');

    Route::post(
        'services-delete',
        'laraveltlr\tlr\TlrController@servicesDelete'
    )->name('services.delete');

    Route::post(
        'services-category',
        'laraveltlr\tlr\TlrController@categoryStore'
    )->name('category.store');
    Route::get(
        'timelog/{user_id}',
        'laraveltlr\tlr\TlrController@timelog'
    )->name('timelog');

    Route::get(
        'faq',
        'laraveltlr\tlr\TlrController@processData'
    )->name('processData');

    Route::get(
        'aptitude',
        'laraveltlr\tlr\TlrController@aptitude'
    )->name('aptitude');

    Route::get(
        'aptitude-create',
        'laraveltlr\tlr\TlrController@aptitudeCreate'
    )->name('aptitude.create');
     
    Route::post(
        'aptitude_store',
        'laraveltlr\tlr\TlrController@aptitudeStore'
    )->name('aptitude.store');

    Route::post(
        'aptitude_delete/{id}',
        'laraveltlr\tlr\TlrController@aptitudeDelete'
    )->name('aptitude.delete');

    Route::get(
        'aptitude-edit/{id}',
        'laraveltlr\tlr\TlrController@aptitudeEdit'
    )->name('aptitude.edit');

    Route::post(
        'aptitude/savetechnology',
        'laraveltlr\tlr\TlrController@saveTechnology'
    )->name('saveTechnology');

    Route::put(
        'aptitude_update/{id}',
        'laraveltlr\tlr\TlrController@aptitudeUpdate'
    )->name('aptitude.update');

    Route::post(
        'storequestion',
        'laraveltlr\tlr\TlrController@ImportQuestion'
    )->name('aptitude.import.store');

    Route::get(
        'aptitude/startpage/{token}',
        'laraveltlr\tlr\TlrController@StartPage'
    )->name('startPage');
    Route::post(
        'aptitude/savetoken',
        'laraveltlr\tlr\TlrController@saveToken'
    )->name('aptitude.saveToken');

    Route::post(
        'generate/{token}',
        'laraveltlr\tlr\TlrController@generate'
    )->name('aptitude.generate');

    Route::get(
        'yearly-report',
        'laraveltlr\tlr\TlrController@yearlyReport'
    )->name('yearly.report');
    Route::get(
        'time-tracker',
        'laraveltlr\tlr\TlrController@timeTracker'
    )->name('time.tracker');

    Route::get(
        'showImage/{user_id}/{start_date}/{end_date}',
        'laraveltlr\tlr\TlrController@showImage'
    )->name('showImage');

});
Route::post(
    'emp-store',
    'laraveltlr\tlr\TlrController@storeEMP'
)->name('storeEMP');
