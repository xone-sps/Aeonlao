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

Route::get('/', 'HomeController@index')->name('get.home.index');
Route::get('/login', 'HomeController@index')->name('get.home.login');
Route::get('/logout', 'Auth\LoginController@sessionLogout')->name('logout');
Route::get('/users-logout', 'HomeController@index')->name('get.home.users-logout');
Route::get('/jv0ABI4k2qmWQfLwSapBKfIQe7Lw0xTTVpa0xGG6', 'HomeController@index')->name('get.home.register.checker_field_inspector');
Route::get('/FWSfbih3KioEQAAOTinfTMME4HT5l8faZ9easpl7', 'HomeController@index')->name('get.home.register.institute');

Route::get('/register-successfully', 'HomeController@index')->name('get.home.register-successfully');

Route::get('/about', 'HomeController@index')->name('get.home.about');
Route::get('/contact', 'HomeController@index')->name('get.home.contact');
/**@Posts */
Route::group(['prefix' => 'posts/{type}'], function () {
    Route::get('/', 'HomeController@responsePosts')->name('get.home.posts');
    Route::get('/single/{id}', 'HomeController@responsePostsSingle')->name('get.home.posts.single');
});
/**@Posts */

/**@ResetPasswordForm */
Route::get('password/reset/{token}', 'HomeController@index')->name('password.reset');
Route::get('/reset-password-successfully', 'HomeController@index')->name('get.home.reset-password-successfully');
/**@ResetPasswordForm */

/***** @UserRoutes ***** */
Route::group(['prefix' => 'users/me', 'middleware' => []], function () {
    Route::get('/', 'UserController@index')->name('get.user.index');
    Route::get('/profile-settings', 'UserController@index')->name('get.user.profileSettings');
    Route::get('/members-profile', 'UserController@index')->name('get.user.membersProfile');
    Route::get('/members-profile/{id}', 'UserController@index')->name('get.user.singleMemberProfile');
    Route::get('/download-files', 'UserController@index')->name('get.user.download-files');
    Route::get('/download-files/export', 'UserController@downloadFileExport')->name('get.user.download-files.export');
    /***@AutoUserLogin*/
    Route::get('auto-login/{confirmation_token}', 'Auth\LoginController@userAutoLogin')->name('get.user.UserAutoLogin');
    /***@AutoUserLogin*/
});
/***** @UserRoutes ***** */
/***** @FieldInspector ***** */
Route::group(['prefix' => 'field-inspector/me', 'middleware' => []], function () {
    Route::get('/', 'FieldInspectorController@index')->name('field-inspector.get.index');
    Route::get('/profile-settings', 'FieldInspectorController@index')->name('field-inspector.get.profile-settings');
    Route::get('/check-assessments', 'FieldInspectorController@index')->name('field-inspector.get.check-assessments');
    Route::get('/check-assessments/{id}', 'FieldInspectorController@index')->name('field-inspector.get.check-assessments.single');
});
/***** @FieldInspector ***** */

/***** @InstituteRoutes ***** */
Route::group(['prefix' => 'institute/me', 'middleware' => []], function () {
    Route::get('/', 'InstituteProfileController@index')->name('institute.get.index');
    Route::get('/profile-settings', 'InstituteProfileController@index')->name('institute.get.profile-settings');
    Route::get('/check-assessments', 'InstituteProfileController@index')->name('institute.get.check-assessments');
    Route::get('/check-assessments/{id}', 'InstituteProfileController@index')->name('institute.get.check-assessments.single');
});
/***** @InstituteRoutes ***** */

/***** @CheckerRoutes ***** */
Route::group(['prefix' => 'checker/me', 'middleware' => []], function () {
    Route::get('/', 'CheckerController@index')->name('checker.get.index');
    Route::get('/profile-settings', 'CheckerController@index')->name('checker.get.profile-settings');
    Route::get('/check-assessments', 'CheckerController@index')->name('checker.get.check-assessments');
    Route::get('/check-assessments/{id}', 'CheckerController@index')->name('checker.get.check-assessments.single');
    Route::get('/review-assessments-field-inspector', 'CheckerController@index')->name('checker.get.review-assessments-field-inspector');
    Route::get('/review-assessments-field-inspector/{id}', 'CheckerController@index')->name('checker.get.review-assessments-field-inspector.single');
});
/***** @CheckerRoutes ***** */






