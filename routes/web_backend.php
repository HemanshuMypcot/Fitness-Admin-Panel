<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register backend routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login
Route::get('/', 'LoginController@index')->name('login');
Route::post('login', 'LoginController@login');
Route::get('forgot-password', 'LoginController@forgotPassword')->name('password.request');
Route::post('forgot-password', 'LoginController@forgotPasswordStore')->name('password.email');
Route::get('/reset-password/{token}/{email}', 'LoginController@passwordReset')->name('password.reset')->middleware('signed');
Route::post('/reset-password', 'LoginController@passwordUpdate')->name('password.update');

Route::get('/password_expired', 'AdminController@passwordExpired');
Route::post('/force_reset_password', 'AdminController@resetExpiredPassword');

Route::group(['middleware' => ['customAuth']], function () {
	// Dashboard
	Route::get('dashboard', 'DashboardController@index');
    Route::get('dashboard/test', 'DashboardController@index_phpinfo');
	Route::post('admin_dashboard_chart', 'DashboardController@userDashboardChart');
	Route::post('booked_course_dashboard_chart', 'DashboardController@bookedCourseDashboardChart');

	//profile
	Route::get('/profile', 'AdminController@profile');
	Route::post('/updateProfile', 'AdminController@updateProfile');

	//change password
	Route::get('/updatePassword', 'AdminController@updatePassword');
	Route::post('/resetPassword', 'AdminController@resetPassword');

	//staff
	Route::get('staff', 'StaffController@index');
	Route::post('staff/fetch', 'StaffController@fetch')->name('staff_fetch');
	Route::get('staff/add', 'StaffController@add');
	Route::post('staff/save', 'StaffController@store');
	Route::get('staff/edit/{id}', 'StaffController@edit');
	Route::post('staff/update', 'StaffController@update');
	Route::post('publish/staff', 'StaffController@updateStatus');
	Route::get('staff/view/{id}', 'StaffController@view');
	Route::get('staff/change_password/{id}', 'StaffController@changePassword');
    Route::post('staff/changePassword', 'StaffController@changeStaffPassword');

    //manage role
	Route::get('roles', 'RoleController@roles');
	Route::post('role/fetch', 'RoleController@roleData')->name('role/fetch');
	Route::get('role_permission/{id}', 'RoleController@assignRolePermission');
	Route::post('publish/permission', 'RoleController@publishPermission');

    //general settings
    Route::get('general_settings', 'GeneralSettingController@index');
    Route::post('updateSettingInfo', 'GeneralSettingController@updateSetting');

    //about us
    Route::get('about_us', 'AboutUsController@index');
    Route::post('about_us/update', 'AboutUsController@update');

    //terms and condition
    Route::get('terms', 'TermsAndConditionController@index');
    Route::post('terms/update', 'TermsAndConditionController@update');

    //privacy policy
    Route::get('privacy_policy', 'PrivacyPolicyController@index');
    Route::post('privacy_policy/update', 'PrivacyPolicyController@update');

    //cancellation policy
    Route::get('cancellation_policy', 'CancellationPolicyController@index');
    Route::post('cancellation_policy/update', 'CancellationPolicyController@update');

    //refund policy
    Route::get('refund_policy', 'RefundPolicyController@index');
    Route::post('refund_policy/update', 'RefundPolicyController@update');

    //instructor
    Route::get('instructors', 'InstructorController@index');
    Route::post('instructors/fetch', 'InstructorController@fetch');
	Route::get('instructors/add', 'InstructorController@create');
    Route::post('instructors/save', 'InstructorController@store');
	Route::get('instructors/edit/{id}', 'InstructorController@edit');
	Route::post('instructors/update', 'InstructorController@update');
	Route::get('instructors/view/{id}', 'InstructorController@show');
	Route::post('instructors/publish', 'InstructorController@updateStatus');
	Route::post('instructors/force_update', 'InstructorController@forceUpdateStatus');

	//Location
	Route::get('location', 'LocationController@index');
	Route::get('location/add', 'LocationController@create');
	Route::get('location/edit/{id}', 'LocationController@edit');
	Route::post('location/update', 'LocationController@update');
	Route::post('location/fetch', 'LocationController@fetch');
	Route::get('location/view/{id}', 'LocationController@show');
	Route::post('location/save', 'LocationController@store');
	Route::post('location/delete_img', 'LocationController@deleteImage');
	Route::post('location/publish', 'LocationController@updateStatus');

    //course category
    Route::get('course_categories', 'CourseCategoryController@index');
    Route::post('course_categories/fetch', 'CourseCategoryController@fetch');
    Route::get('course_categories/add', 'CourseCategoryController@create');
    Route::post('course_categories/save', 'CourseCategoryController@store');
	Route::get('course_categories/edit/{id}', 'CourseCategoryController@edit');
	Route::post('course_categories/update', 'CourseCategoryController@update');
	Route::get('course_categories/view/{id}', 'CourseCategoryController@show');
	Route::post('course_categories/publish', 'CourseCategoryController@updateStatus');

    // movie cateogory
    Route::get('movie_categories', 'MovieCategoryController@index');
    Route::get('movie_categories/add', 'MovieCategoryController@create');
    Route::post('movie_categories/save', 'MovieCategoryController@store');
    Route::post('movie_categories/fetch', 'MovieCategoryController@fetch');
    Route::post('movie_categories/publish', 'MovieCategoryController@updateStatus');
    Route::get('movie_categories/view/{id}', 'MovieCategoryController@show');
    Route::get('movie_categories/edit/{id}', 'MovieCategoryController@edit');
    Route::post('movie_categories/update', 'MovieCategoryController@update');
    
    
	//customer
	Route::get('customer', 'CustomerController@index');
	Route::post('customer/fetch', 'CustomerController@fetch');
	Route::get('customer/view/{id}', 'CustomerController@show');
	Route::get('customer/verify/{id}', 'CustomerController@isVerify');
	Route::get('customer/change_password/{id}', 'CustomerController@changePassword');
	Route::post('customer/changePassword', 'CustomerController@changeCustomerPassword');
    Route::post('customer/publish', 'CustomerController@updateStatus');

	// Notifications
    Route::get('notification', 'NotificationController@index');
    Route::post('notification/fetch', 'NotificationController@fetch');
    Route::get('notification/add', 'NotificationController@create');
    Route::post('notification/save', 'NotificationController@store');
    Route::get('notification/edit/{id}', 'NotificationController@edit');
    Route::get('notification/view/{id}', 'NotificationController@show');
    Route::post('notification/update', 'NotificationController@update');
    Route::get('get_masters_listing/{type}', 'NotificationController@getMastersListing');
    Route::get('notification/send/{id}', 'NotificationController@sendNotification');
    Route::post('notification/send', 'NotificationController@sendUserNotification');

	// booked Course
    Route::get('booked_course', 'BookedCourseController@index');
    Route::post('booked_course/fetch', 'BookedCourseController@fetch');
	Route::get('booked_course/mark_as_paid/{id}', 'BookedCourseController@markAsPaid');
	Route::post('update_booking_status', 'BookedCourseController@updateBookingSatatus');
    Route::get('booked_course/view/{id}', 'BookedCourseController@show');

    //course
    Route::get('course', 'CourseController@index');
    Route::post('course/fetch', 'CourseController@fetch');
    Route::get('course/add', 'CourseController@create');
    Route::post('course/save', 'CourseController@store');
	Route::get('course/edit/{id}', 'CourseController@edit');
	Route::post('course/update', 'CourseController@update');
	Route::get('course/view/{id}', 'CourseController@show');
	Route::get('course/copy/{id}', 'CourseController@copyCourse');
    Route::post('get_instructor/{id}', 'CourseController@getInstructorsByCategory');

	Route::post('course/publish', 'CourseController@updateStatus');

    //Home Collection
    Route::get('home_collection', 'HomeCollectionController@index');
    Route::post('home_collection/fetch', 'HomeCollectionController@fetch');
    Route::get('home_collection/add', 'HomeCollectionController@create');
    Route::post('home_collection/save', 'HomeCollectionController@store');
    Route::get('home_collection/edit/{id}', 'HomeCollectionController@edit');
    Route::post('home_collection/update', 'HomeCollectionController@update');
    Route::post('home_collection/publish', 'HomeCollectionController@updateStatus');
    Route::get('home_collection/view/{id}', 'HomeCollectionController@show');
    Route::get('home_collection/delete/{id}', 'HomeCollectionController@destroy');
    Route::get('get_mapped_listing/{type}', 'HomeCollectionController@getMappedListing');

    //Report
    Route::get('customer_report', 'ReportController@index');
    Route::post('customer_report_export', 'ReportController@customerReportExport');

    //booked_course_report
    Route::get('booked_course_report', 'ReportController@booked_index');
    Route::post('booked_course_report_export', 'ReportController@bookedCourseReportExport');

    //User Review
    Route::get('user_review', 'UserReviewController@index');
    Route::post('user_review/fetch', 'UserReviewController@fetch');
	Route::get('user_review/view/{id}', 'UserReviewController@show');

    //enquiry
    Route::get('enquiries', 'EnquiryController@index');
    Route::post('enquiries/fetch', 'EnquiryController@fetch');
    Route::get('enquiries/view/{id}', 'EnquiryController@show');
    
    // Logout
	Route::get('/logout', function () {
		session()->forget('data');
		return redirect('/webadmin');
	});
});
