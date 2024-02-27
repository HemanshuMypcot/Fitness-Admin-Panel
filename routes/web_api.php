<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['basicAuth'])->group(function () {
	Route::post('register_api', 'AuthApiController@index');
	Route::post('forgot_api', 'AuthApiController@forgotPassword');
	Route::post('validate_otp', 'AuthApiController@validateOtp');
	Route::post('resend_otp', 'AuthApiController@resendOtp');
    Route::post('login_api', 'AuthApiController@loginWithPassword');
	Route::post('reset_password', 'AuthApiController@changePassword');
	Route::post('preferred_language/details', 'PreferredLanguageApiController@index');

	Route::middleware(['tokenAuth'])->group(function () {
		// Startup API
		Route::post('startup_api', 'StartupApiController@index')->name('startup_api');

		// User APIs
		Route::post('users/change_password', 'UserApiController@changePassword');
		Route::post('users/update', 'UserApiController@update');
		Route::post('users/delete', 'UserApiController@destroy');
        Route::post('users/logout', 'UserApiController@logoutUser')->name('user.logout');
		Route::post('users/me', 'UserApiController@show');
		Route::post('users/notification/status', 'UserApiController@updateNotificationStatus');

		//course category api
		Route::post('course_category/list', 'CourseCategoryApiController@index')->name('course_category.list');

        // Instructor review
		Route::post('instructors/review', 'InstructorApiController@addReview');

        // Booked courses
        Route::post('booked_course/create','BookedCourseApiController@store')->name('booked_course.create');
        Route::post('booked_course/list','BookedCourseApiController@index')->name('booked_course.index');
		Route::post('booked_course/show','BookedCourseApiController@show')->name('booked_course.show');
        Route::post('booked_course/cancel','BookedCourseApiController@cancel')->name('booked_course.cancel');


		// Policies api
        Route::post('policies', 'PolicyApiController@show')->name('policies.show');

		//contact us
		Route::post('contact/create','ContactApiController@store')->name('contact.create');
		Route::post('contact/show','ContactApiController@show')->name('contact.show');

		//courses api
        Route::post('courses/list', 'CourseApiController@index')->name('courses.list');
        Route::post('courses/show', 'CourseApiController@show')->name('courses.show');
        Route::post('courses/get_payment_details', 'CourseApiController@getPaymentDetails')->name('courses.get_payment_details');

		//schedule api
		Route::post('schedule/list', 'ScheduleApiController@index')->name('schedule.list');

		//update fcm
        Route::post('notification_token/update', 'UserApiController@updateFcmId')->name('notification_token.update');

		//notification list
        Route::post('notification/list','UserNotificationApiController@index');
        
         //home collection api
        Route::post('home_collection/list', 'HomeCollectionApiController@index')->name('home_collection.list');
    });
});

