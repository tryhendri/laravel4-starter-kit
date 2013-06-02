<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Register all the admin routes.
|
*/

Route::group(array('prefix' => 'admin'), function()
{

	# Blog Articles Management
	Route::group(array('prefix' => 'articles'), function()
	{
		Route::get('/', array('as' => 'articles', 'uses' => 'Controllers\Admin\ArticlesController@getIndex'));

		## maybe add routes to manage individual comments ?!?
		Route::get('comments', array('as' => 'articles/comments', 'uses' => 'Controllers\Admin\ArticlesCommentsController@getIndex'));

		Route::get('create', array('as' => 'article/create', 'uses' => 'Controllers\Admin\ArticlesController@getCreate'));
		Route::post('create', 'Controllers\Admin\ArticlesController@postCreate');

		Route::get('view/{id}', array('as' => 'article/update', 'uses' => 'Controllers\Admin\ArticlesController@getEdit'));
		Route::post('view/{id}', 'Controllers\Admin\ArticlesController@postEdit');

		Route::get('copy/{id}', array('as' => 'article/copy', 'uses' => 'Controllers\Admin\ArticlesController@getCopy'));
		Route::post('copy/{id}', 'Controllers\Admin\ArticlesController@postCopy');

		Route::get('delete/{id}', array('as' => 'article/delete', 'uses' => 'Controllers\Admin\ArticlesController@getDelete'));

		Route::get('view/{id}/comments', array('as' => 'article/comments', 'uses' => 'Controllers\Admin\ArticlesController@getComments'));

	});

	# User Management
	Route::group(array('prefix' => 'users'), function()
	{
		Route::get('/', array('as' => 'users', 'uses' => 'Controllers\Admin\UsersController@getIndex'));

		Route::get('create', array('as' => 'user/create', 'uses' => 'Controllers\Admin\UsersController@getCreate'));
		Route::post('create', 'Controllers\Admin\UsersController@postCreate');

		Route::get('view/{id}', array('as' => 'user/update', 'uses' => 'Controllers\Admin\UsersController@getEdit'));
		Route::post('view/{id}', 'Controllers\Admin\UsersController@postEdit');

		Route::get('delete/{id}', array('as' => 'user/delete', 'uses' => 'Controllers\Admin\UsersController@getDelete'));

		Route::get('restore/{id}', array('as' => 'user/restore', 'uses' => 'Controllers\Admin\UsersController@getRestore'));
	});

	# Group Management
	Route::group(array('prefix' => 'groups'), function()
	{
		Route::get('/', array('as' => 'groups', 'uses' => 'Controllers\Admin\GroupsController@getIndex'));

		Route::get('create', array('as' => 'group/create', 'uses' => 'Controllers\Admin\GroupsController@getCreate'));
		Route::post('create', 'Controllers\Admin\GroupsController@postCreate');

		Route::get('view/{id}', array('as' => 'group/update', 'uses' => 'Controllers\Admin\GroupsController@getEdit'));
		Route::post('view/{id}', 'Controllers\Admin\GroupsController@postEdit');

		Route::get('delete/{id}', array('as' => 'group/delete', 'uses' => 'Controllers\Admin\GroupsController@getDelete'));
	});

	# Dashboard
	Route::get('/', array('as' => 'admin', 'uses' => 'Controllers\Admin\DashboardController@getIndex'));

});

/*
|--------------------------------------------------------------------------
| Authentication and Authorization Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::group(array('prefix' => 'auth'), function()
{

	# Login
	Route::get('signin', array('as' => 'signin', 'uses' => 'AuthController@getSignin'));
	Route::post('signin', 'AuthController@postSignin');

	# Register
	Route::get('signup', array('as' => 'signup', 'uses' => 'AuthController@getSignup'));
	Route::post('signup', 'AuthController@postSignup');

	# Account Activation
	Route::get('activate/{activationCode}', array('as' => 'activate', 'uses' => 'AuthController@getActivate'));

	# Forgot Password
	Route::get('forgot-password', array('as' => 'forgot-password', 'uses' => 'AuthController@getForgotPassword'));
	Route::post('forgot-password', 'AuthController@postForgotPassword');

	# Forgot Password Confirmation
	Route::get('forgot-password/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'AuthController@getForgotPasswordConfirm'));
	Route::post('forgot-password/{passwordResetCode}', 'AuthController@postForgotPasswordConfirm');

	# Logout
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));

});

/*
|--------------------------------------------------------------------------
| Account Routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::group(array('prefix' => 'account'), function()
{

	# Account Dashboard
	Route::get('/', array('as' => 'account', 'uses' => 'Controllers\Account\DashboardController@getIndex'));

	# Profile
	Route::get('profile', array('as' => 'profile', 'uses' => 'Controllers\Account\ProfileController@getIndex'));
	Route::post('profile', 'Controllers\Account\ProfileController@postIndex');

	# Change Password
	Route::get('change-password', array('as' => 'change-password', 'uses' => 'Controllers\Account\ChangePasswordController@getIndex'));
	Route::post('change-password', 'Controllers\Account\ChangePasswordController@postIndex');

	# Change Email
	Route::get('change-email', array('as' => 'change-email', 'uses' => 'Controllers\Account\ChangeEmailController@getIndex'));
	Route::post('change-email', 'Controllers\Account\ChangeEmailController@postIndex');

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('about-us', function()
{
	//
	return View::make('frontend/about-us');
});

Route::get('contact-us', array('as' => 'contact-us', 'uses' => 'ContactUsController@getIndex'));
Route::post('contact-us', 'ContactUsController@postIndex');

Route::get('article/{postSlug}', array('as' => 'view-article', 'uses' => 'BlogController@getView'));
Route::post('article/{postSlug}', 'BlogController@postView');

Route::get('/', array('as' => 'home', 'uses' => 'BlogController@getIndex'));
