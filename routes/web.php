<?php

use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\UserPageController;
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

/* --------- General routes --------- */
Auth::routes(['verify' => true]);
Route::get('/', 'Controller@show_welcome');
Route::get('dashboard', 'DashboardController@index')->name('dashboard')->middleware('verified');

/* --------- google login ---------- */
Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

/* --------- Tip POSTS ------------------- */
Route::post('process_tip/{username}', 'TipController@process_tip');
Route::post('confirm_tip', 'TipController@confirm_tip')->name('confirm_tip');
Route::post('unconfirmed', 'TipController@unconfirmed')->name('unconfirmed');

/* --------- Praise tip ------------------ */
Route::post('praise', 'UserPageController@praise');

/* --------- Edit profile ---------- */
Route::get('edit_profile', 'SettingsController@show')->name('edit_profile')->middleware('verified');
Route::post('save_one','SettingsController@save_one');
Route::post('save_two', 'SettingsController@save_two');
Route::post('change_password','SettingsController@reset_password')->name('change_password');
Route::post('delete_acc', 'SettingsController@delete_acc')->name('delete_acc');
Route::post('change_email','SettingsController@change_email')->name('change_email');

/* ----------- User page POSTS ------------- */
Route::post('upload_header_img', 'UserPageController@upload_header_img');

/* ----------- User page GETS -------------- */
Route::get('{username}', 'UserPageController@show')->name('user_page');






