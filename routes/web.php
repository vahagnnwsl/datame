<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register/individual', 'Auth\RegisterController@showIndividualRegistrationForm')->name('register-individual');
Route::get('register/legal', 'Auth\RegisterController@showLegalRegistrationForm')->name('register-legal');
Route::post('register', 'Auth\RegisterController@register')->name('register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::get('/', 'HomeController@index')->name('index');
Route::get('about', 'HomeController@about')->name('about');
Route::get('faq', 'HomeController@faq')->name('faq');
Route::get('doc-api', 'HomeController@docApi')->name('doc-api');
Route::get('oferta', 'HomeController@oferta')->name('oferta');
Route::get('tariff', 'HomeController@tariff')->name('tariff');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('app-report/template-pdf/{identity}/{font_td?}/{font_name?}/{font_stat?}/{font_type?}', 'HomeController@appTemplateForPdf')->name('app-report.template.pdf');
Route::get('app-report/demo', 'HomeController@appReportDemo')->name('app-report.demo');
Route::get('app-report/pdf/{identity}/{font_td?}/{font_name?}/{font_stat?}/{font_type?}', 'HomeController@loadPdf')->name('app-report');

Route::group(['middleware' => ['auth','verified']], function() {

    Route::get('app-report/{app_id}', 'HomeController@appReport')->name('app-report');

    Route::get('apps', 'AppController@index')->name('apps');
    Route::get('profile', 'ProfileUserController@index')->name('profile-user');
    Route::get('messages', 'MessageController@index')->name('messages');
    Route::get('personal-token', 'PersonalTokenUserController@index')->name('personal-token');

    Route::get('application', 'ProfileUserController@application')->name('application');

    Route::group(['middleware' => ['auth.admin']], function() {

        Route::prefix('admin')->group(function() {

            Route::get('users', 'Admin\UsersController@index')->name('admin.users');
            Route::get('searching', 'Admin\SearchingController@index')->name('admin.searching');
            Route::get('messages', 'Admin\MessagesController@index')->name('admin.messages');
            Route::get('proxies', 'Admin\ProxyController@index')->name('admin.proxies');
            Route::post('proxies', 'Admin\ProxyController@store')->name('admin.proxies.store');
            Route::post('proxies/{id}', 'Admin\ProxyController@update')->name('admin.proxies.update');
            Route::post('proxies/{id}/delete', 'Admin\ProxyController@destroy')->name('admin.proxies.destroy');

            Route::get('own-databases', 'Admin\OwnDatabasesController@index')->name('admin.own-databases');
            Route::post('own-databases/{id}', 'Admin\OwnDatabasesController@update');

            Route::get('files', 'Admin\OwnDatabasesController@getFiles')->name('admin.get-files');
            Route::post('files/fields', 'Admin\OwnDatabasesController@getFileFields')->name('admin.get-file-fields');
            Route::post('files/fields/save', 'Admin\OwnDatabasesController@saveFileFields')->name('admin.save-file-fields');


        });
    });

});
