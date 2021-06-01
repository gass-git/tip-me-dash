<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

/* --------- general routes --------- */
Auth::routes(['verify' => true]);
Route::get('/', 'Controller@show_welcome');
Route::get('dashboard', 'DashboardController@index')->name('dashboard')->middleware('verified');

/* --------- google login ---------- */
Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

/* --------- edit profile ---------- */
Route::get('edit_profile', 'EditProfileController@show')->name('edit_profile')->middleware('verified');
Route::post('update_profile', 'EditProfileController@update')->name('update_profile');
Route::post('change_password','EditProfileController@reset_password')->name('change_password');

/* --------- community activity ----- */
Route::get('community_activity', 'CommunityActivityController@show')->name('community_activity');

/* ---------- newcomers -------------- */
Route::get('newcomers','NewComersController@show')->name('newcomers');

/* --------- user page ------------- */
Route::post('brilliant','UserPageController@brilliant')->name('brilliant');
Route::post('likes_it','UserPageController@likes_it')->name('likes_it');
Route::post('loves_it','UserPageController@loves_it')->name('loves_it');
Route::post('delete_post', 'UserPageController@delete_post')->name('delete_post');
Route::post('boost_reputation','UserPageController@boost_reputation')->name('boost_reputation');
Route::post('post_message/{username}', 'UserPageController@post_message')->name('post_message');
Route::get('{username}', 'UserPageController@show')->name('user_page')->middleware('verified');



