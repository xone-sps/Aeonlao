<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin/me', 'middleware' => []], function () {
    Route::get('/', 'AdminController@index')->name('admin.get.index');
    Route::get('/members/checker', 'AdminController@index')->name('admin.get.index.members.checker');
    Route::get('/members/field-inspector', 'AdminController@index')->name('admin.get.index.members.field-inspector');
    Route::get('/about-us', 'AdminController@index')->name('admin.get.about-us');
    Route::get('/sitesetting', 'AdminController@index')->name('admin.get.sitesetting');
    Route::get('/contactinfo', 'AdminController@index')->name('admin.get.contactinfo');
    Route::get('/news', 'AdminController@index')->name('admin.get.news');
    Route::get('/activity', 'AdminController@index')->name('admin.get.activity');
    Route::get('/scholarship', 'AdminController@index')->name('admin.get.scholarship');
    Route::get('/upload-files', 'AdminController@index')->name('admin.get.uploadfile');
    Route::get('/review-assessment', 'AdminController@index')->name('admin.get.review-assessment');
    Route::get('/review-assessment/{id}', 'AdminController@index')->name('admin.get.review-assessment.single');
    Route::get('/assessment', 'AdminController@index')->name('admin.get.assessment');
    Route::get('/assessment/start-assessment', 'AdminController@index')->name('admin.get.assessment.create');
    Route::get('/assessment/preview-assessment/{id}', 'AdminController@index')->name('admin.get.assessment.preview.single');
    Route::get('/assessment/send', 'AdminController@index')->name('admin.get.assessment.send');
    Route::get('/members/institute', 'AdminController@index')->name('admin.get.members.institute');
    Route::get('/institute-categories', 'AdminController@index')->name('admin.get.institute-categories');
    Route::get('/review-assessments-field-inspector', 'AdminController@index')->name('admin.get.review-assessments-field-inspector');
    Route::get('/review-assessments-field-inspector/{id}', 'AdminController@index')->name('admin.get.review-assessments-field-inspector-single');
});
