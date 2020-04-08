<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('demo', 'Api\AppController@getAppDemo')->name('api.apps.info.demo');

Route::middleware('auth:api')->group(function() {

    Route::prefix('apps')->group(function() {

        Route::get('{app_id}', 'Api\AppController@getApp')->name('api.apps.info');
        Route::get('short/{app_id}', 'Api\AppController@getAppShort')->name('api.apps.info.short');
        Route::post('/filter/{page?}/{limit?}', 'Api\AppController@getApps')->name('api.apps.filter');
        Route::post('/filter/all/{page?}/{limit?}', 'Api\AppController@getAppsAll')->name('api.apps.filter-all');
        Route::post('store', 'Api\AppController@store')->name('api.apps.store');
    });

    //Роуты только для админа
    Route::middleware('auth.admin')->group(function() {

    });

    Route::prefix("user")->group(function() {
        Route::get('/', 'Api\UserController@getUser')->name('api.user');;
        Route::post('individual', 'Api\UserController@postIndividualData')->name('api.user.individual.save');
        Route::post('legal', 'Api\UserController@postLegalData')->name('api.user.legal.save');
        Route::post('password', 'Api\UserController@postChangePasswordData')->name('api.user.password.change');
    });

    //апи для создание/редактирования физического лица
    Route::post('users/individual/{page?}/{limit?}', 'Api\Users\IndividualUserController@getUsers')->name('api.users.individual');
    Route::post('users/register/individual', 'Api\Users\IndividualUserController@register')->name('api.users.individual.register');
    Route::post('users/edit/individual', 'Api\Users\IndividualUserController@postEdit')->name('api.users.individual.edit');
    Route::post('users/confirm/individual', 'Api\Users\IndividualUserController@postConfirm')->name('api.users.individual.confirm');

    //апи для создание/редактирования юридического лица
    Route::post('users/legal/{page?}/{limit?}', 'Api\Users\LegalUserController@getUsers')->name('api.users.legal');
    Route::post('users/register/legal', 'Api\Users\LegalUserController@register')->name('api.users.legal.register');
    Route::post('users/edit/legal', 'Api\Users\LegalUserController@postEdit')->name('api.users.legal.edit');
    Route::post('users/confirm/legal', 'Api\Users\LegalUserController@postConfirm')->name('api.users.legal.confirm');


    Route::post('messages/store/all/register', 'Api\ForAllMessageController@storeMessageRegisterUser')->name('api.messages.all.register.store');
    Route::post('messages/store/all/unregister', 'Api\ForAllMessageController@storeMessageUnRegisterUser')->name('api.messages.all.unregister.store');
    Route::post('messages/store/user', 'Api\UserMessageController@storeMessage')->name('api.messages.user.store');
    Route::any('messages/all/{page?}/{limit?}', 'Api\UserMessageController@getMessages')->name('api.messages.list');
    Route::post('messages/read/{message_id}/', 'Api\UserMessageController@isReadMessage')->name('api.messages.read');

});



