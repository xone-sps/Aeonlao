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
/******************** @GuestUserSection ****************** */
Route::group(['prefix' => '/guest', 'middleware' => ['cors']], function () {
    Route::post('/login', 'Auth\LoginController@login')->name('api.post.login');
    Route::post('/jv0ABI4k2qmWQfLwSapBKfIQe7Lw0xTTVpa0xGG6-post', 'Auth\RegisterController@registerCheckerFiledInspector')->name('api.post.register.checker_field_inspector');
    Route::post('/FWSfbih3KioEQAAOTinfTMME4HT5l8faZ9easpl7-post', 'Auth\RegisterController@registerInstitute')->name('api.post.register.institute');
    //Users Email Forgot Password
    Route::post('/forgot-password/email/{token}', 'Auth\ForgotPasswordController@getEmailFromToken')->name('api.post.getEmailFromToken');
    //Users Password Reset
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('api.post.resetPassword');
    //fetchInstituteParentCategories
    Route::get('/institute/category/list-parents/{id}', 'HomeController@getInstituteParentCategories')->name('api.get.institute-parent-categories');
});
/******************** @GuestUserSection ****************** */

/******************** @HomeSection ****************** */
Route::group(['prefix' => '/home', 'middleware' => ['cors', 'parseToken:guest-bearer']], function () {
    Route::get('/index', 'HomeController@index')->name('api.get.home.index');
    /*********@Posts */
    Route::get('/posts/{type}', 'HomeController@responsePosts')->name('get.home.posts');
    Route::get('/posts/{type}/single/{id}', 'HomeController@responsePostsSingle')->name('get.home.posts.single');
    /********* @Posts */
    /****@ContactInfo */
    Route::post('/contact-info', 'HomeController@responsePostContactInfo')->name('api.post.contactInfo');
    /****@ContactInfo */
    /***@MembersChartRange */
    Route::get('/chart-ranges/{id}', 'HomeController@getChartRangeMembers');
    /***@MembersChartRange */
});
/******************** @HomeSection ****************** */

Route::group(['prefix' => '/', 'middleware' => ['cors', 'parseToken', 'auth:api']], function () {

    /**
     ***************** @AdminSection routes ************************
     */
    Route::group(['prefix' => '/admin', 'middleware' => []], function () {
        /*** @Searches ** */
        Route::get('/searches/{type}', 'AdminController@responseSearches')->name('api.admin.get.searches');
        /*** @Searches ** */


        Route::group(['prefix' => '/institute', 'middleware' => []], function () {
            /*** @Category ** */
            Route::get('/category', 'AdminController@responseActionListInstituteCategories')->name('api.admin.get.institute.category.lists');
            Route::post('/category/create', 'AdminController@responseActionCreateInstituteCategory')->name('api.admin.post.institute.category.create');
            Route::post('/category/update/{id}', 'AdminController@responseActionUpdateInstituteCategory')->name('api.admin.post.institute.category.update');
            Route::delete('/category/delete/{id}', 'AdminController@responseActionDeleteInstituteCategory')->name('api.admin.delete.institute.category.delete');
            /*** @Category ** */

        });
        Route::post('/assessment-post/create', 'AdminController@responseActionCreateAsessment')->name('api.admin.post.assessment.create');
        Route::group(['prefix' => '/assessment', 'middleware' => []], function () {
            Route::get('/fetch/{id}', 'AdminController@responseActionFecthAsessment')->name('api.admin.get.assessment.fetch');
            Route::post('/update/{id}', 'AdminController@responseActionUpdateAsessment')->name('api.admin.post.assessment.update');
            Route::post('/update-status/{id}', 'AdminController@responseActionUpdateStatusAsessment')->name('api.admin.post.assessment.update-status');
            Route::delete('/delete/{id}', 'AdminController@responseActionDeleteAsessment')->name('api.admin.delete.assessment.delete');
            Route::get('/send-users', 'AdminController@responseActionFetchSendAsessmentUsers')->name('api.admin.fetch.send-assessment-users');
            Route::post('/send/{type}', 'AdminController@responseActionSendAsessmentUsers')->name('api.admin.post.send-assessment-users');
        });

        /*** @SiteInfo ** */
        Route::post('site-info/manage', 'AdminController@responseActionManageSiteInfo')->name('api.admin.post.site-info.manage');
        Route::get('site-info', 'AdminController@getSiteInfo')->name('api.admin.get.site-info');
        /*** @SiteInfo ** */
        /*** @News ** */
        Route::post('/news/create', 'AdminController@insertNews');
        Route::post('/news/update/{id}', 'AdminController@updateNews');
        Route::delete('/news/delete/{id}', 'AdminController@DeleteNews');
        /***@News ** */
        /*** @Activity ** */
        Route::post('/activity/create', 'AdminController@insertActivity');
        Route::post('/activity/update/{id}', 'AdminController@updateActivity');
        Route::delete('/activity/delete/{id}', 'AdminController@DeleteActivity');
        /***@Activity ** */
        /*** @Event ** */
        Route::post('/event/create', 'AdminController@insertEvent');
        Route::post('/event/update/{id}', 'AdminController@updateEvent');
        Route::delete('/event/delete/{id}', 'AdminController@DeleteEvent');
        /***@Event ** */
        /*** @Scholarship ** */
        Route::post('/scholarship/create', 'AdminController@insertScholarship');
        Route::post('/scholarship/update/{id}', 'AdminController@updateScholarship');
        Route::delete('/scholarship/delete/{id}', 'AdminController@DeleteScholarship');
        /***@Scholarship ** */

        /***@ContactInfo ** */
        Route::get('/contactinfo', 'AdminController@getContactInfo');
        Route::post('/contactinfo/manage', 'AdminController@manageContactInfo');
        /** @ContactInfo ** */
        /***@AboutInfo ** */
        Route::get('/aboutinfo', 'AdminController@getAboutInfo');
        Route::post('/aboutinfo/manage', 'AdminController@manageAboutInfo');
        /** @AboutInfo ** */
        /***@Banner ** */
        Route::post('/banner/create', 'AdminController@insertBanner');
        Route::post('/banner/update/{id}', 'AdminController@updateBanner');
        Route::delete('/banner/delete/{id}', 'AdminController@deleteBanner');
        /***@Banner ** */
        /***@File ** */
        Route::post('/file/create', 'AdminController@insertFile');
        Route::post('/file/update/{id}', 'AdminController@updateFile');
        Route::delete('/file/delete/{id}', 'AdminController@deleteFile');
        /***@File ** */

        /*** @UploadPostsImage * */
        Route::post('posts/upload-images', 'AdminController@responseActionUploadImages')->name('api.admin.post.posts.uploadImages');
        Route::get('/posts/get-images', 'AdminController@responseActionGetImages')->name('api.admin.post.posts.getImages');
        Route::post('posts/delete-images/', 'AdminController@responseActionDeleteImages')->name('api.admin.delete.posts.delete');
        /*** @UploadPostsImage * */
        /*** @SendUserResetPasswordLink * */
        Route::post('/users-send-reset-password-link/{id}', 'AdminController@responseActionSendUserResetPasswordLink')->name('api.admin.post.users.sendResetPasswordLink');
        /*** @SendUserResetPasswordLink * */
        /*** @postManagePostsStatus * */
        Route::post('posts-status/manage', 'AdminController@responseActionManagePostsStatus')->name('api.admin.post.posts.ManagePostsStatus');
        /*** @postManagePostsStatus * */
    });
    /**
     ***************** @AdminSection routes ************************
     */
    /******************** @UserSection ****************** */
    Route::group(['prefix' => '/users', 'middleware' => []], function () {
        Route::post('me', 'UserController@me')->name('api.user.post.me');
        /*** @Searches ** */
        Route::get('/searches/{type}', 'UserController@responseSearches')->name('api.user.get.searches');
        /*** @Searches ** */


        /*** @UserMemberProfileSingle** */
        /*** @DashboardData Make it can accessible for admin and user * */
        Route::get('/dashboard-data', 'UserController@responseDashboardData')->name('api.user.get.dashboardData');
        /*** @DashboardData Make it can accessible for admin and user * */
        Route::group(['prefix' => '/assessment', 'middleware' => []], function () {
            Route::get('/fetch/{id}', 'UserController@responseActionFetchAssessment')->name('api.user.get.assessment.fetch');
            Route::post('/check-assessment/change-status/{id}', 'UserController@responseActionChangeCheckAsessmentStatus')->name('api.user.post.assessment.change-check-assessment-status');
            Route::post('/check-assessment/save-answer-status-score/{id}', 'UserController@responseActionSaveCheckAsessmentAnswerStatusScore')->name('api.user.post.assessment.save-answer-status-score');
            Route::post('/check-assessment/save-answer/{id}', 'UserController@responseSaveAessmentAnswer')->name('api.user.post.assessment.saveAnswer');
            Route::get('/institute/fetch', 'UserController@responseActionFetchInstituteUsers')->name('api.user.fetch.institutes');
        });


    });

    /**
     ***************** @AdminSection routes ************************
     */

    /******************** @UserSection ****************** */
    /** @Logout */
    Route::post('/logout', 'Auth\LoginController@logout')->name('api.get.logout');
    /** @Logout */
});
