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

$conf = [
    'prefix_class' => 'Web\Backend\\',
    'prefix_alias' => config('constant.location.backend_path'). '.'
];

Route::prefix('xms')->group(function() use ($conf) {
	// login
	Route::match(['get', 'post'], 'login', $conf['prefix_class']. 'Auth\AuthController@login')
		->name($conf['prefix_alias']. 'auth.login');
	// logout
	Route::get('logout', $conf['prefix_class']. 'Auth\AuthController@logout')
		->name($conf['prefix_alias']. 'auth.logout');

	// authenticated user
	Route::middleware('auth.backend:backend')->group(function() use ($conf) {
		// dashboard
		Route::get('/', $conf['prefix_class']. 'DashboardController@index')
			->name($conf['prefix_alias']. 'index');
		// profile
		Route::match(['get', 'post'], '/me', $conf['prefix_class']. 'UserController@profile')
			->name($conf['prefix_alias']. 'user.profile');
		// change password
		Route::post('/me/change_password', $conf['prefix_class']. 'UserController@changePassword')
			->name($conf['prefix_alias']. 'user.changepassword');

		// main modules here
		// groups
		Route::prefix('groups')->group(function() use ($conf) {
			Route::match(['get', 'post'], '/', $conf['prefix_class']. 'UserGroupController@index')
				->name($conf['prefix_alias']. 'user_group.index');
			Route::match(['get', 'post'], '/create', $conf['prefix_class']. 'UserGroupController@create')
				->name($conf['prefix_alias']. 'user_group.create');
			Route::match(['get', 'post'], '/update/{id?}', $conf['prefix_class']. 'UserGroupController@update')
				->name($conf['prefix_alias']. 'user_group.update');
			Route::match(['get', 'post'], '/authorize/{id?}', $conf['prefix_class']. 'UserGroupController@authorizer')
				->name($conf['prefix_alias']. 'user_group.authorize');
			Route::delete('/delete', $conf['prefix_class']. 'UserGroupController@delete')
				->name($conf['prefix_alias']. 'user_group.delete');
		});
		// users
		Route::prefix('users')->group(function() use ($conf) {
			Route::match(['get', 'post'], '/', $conf['prefix_class']. 'UserController@index')
				->name($conf['prefix_alias']. 'user.index');
			Route::match(['get', 'post'], '/create', $conf['prefix_class']. 'UserController@create')
				->name($conf['prefix_alias']. 'user.create');
			Route::match(['get', 'post'], '/update/{id?}', $conf['prefix_class']. 'UserController@update')
				->name($conf['prefix_alias']. 'user.update');
			Route::delete('/delete', $conf['prefix_class']. 'UserController@delete')
				->name($conf['prefix_alias']. 'user.delete');
			Route::post('/delete_picture', $conf['prefix_class']. 'UserController@deletePicture')
				->name($conf['prefix_alias']. 'user.delete_picture');
		});
		// site
		Route::prefix('sites')->group(function() use ($conf) {
			Route::match(['get', 'post'], '/', $conf['prefix_class']. 'SiteController@index')
				->name($conf['prefix_alias']. 'site.index');
			Route::post('/delete_picture', $conf['prefix_class']. 'SiteController@deletePicture')
				->name($conf['prefix_alias']. 'site.delete_picture');
		});
		// logs (backend)
		Route::prefix('logs')->group(function() use ($conf) {
			Route::match(['get', 'post'], '/', $conf['prefix_class']. 'UserLogController@index')
				->name($conf['prefix_alias']. 'user_log.index');
		});
		// backend menu (module)
		Route::prefix('menus')->group(function() use ($conf) {
			Route::match(['get', 'post'], '/', $conf['prefix_class']. 'UserMenuController@index')
				->name($conf['prefix_alias']. 'user_menu.index');
			Route::match(['get', 'post'], '/create', $conf['prefix_class']. 'UserMenuController@create')
				->name($conf['prefix_alias']. 'user_menu.create');
			Route::match(['get', 'post'], '/update/{id?}', $conf['prefix_class']. 'UserMenuController@update')
				->name($conf['prefix_alias']. 'user_menu.update');
			Route::delete('/delete', $conf['prefix_class']. 'UserMenuController@delete')
				->name($conf['prefix_alias']. 'user_menu.delete');
		});
		// front menu (page)
		Route::prefix('pages')->group(function() use ($conf) {
			Route::match(['get', 'post'], '/', $conf['prefix_class']. 'PageController@index')
				->name($conf['prefix_alias']. 'page.index');
			Route::match(['get', 'post'], '/create', $conf['prefix_class']. 'PageController@create')
				->name($conf['prefix_alias']. 'page.create');
			Route::match(['get', 'post'], '/update/{id?}', $conf['prefix_class']. 'PageController@update')
				->name($conf['prefix_alias']. 'page.update');
			Route::delete('/delete', $conf['prefix_class']. 'PageController@delete')
				->name($conf['prefix_alias']. 'page.delete');
			Route::post('/delete_picture', $conf['prefix_class']. 'PageController@deletePicture')
				->name($conf['prefix_alias']. 'page.delete_picture');
		});
	});

});
