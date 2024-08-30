<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/formulario', 'App\Http\Controllers\FormController@index')->name('formulario');
Route::get('/generarxml', 'App\Http\Controllers\XmlController@index')->name('generarxml');
Route::get('/listxml', 'App\Http\Controllers\ListXmlController@index')->name('listxml');
Route::get('/validarxml', 'App\Http\Controllers\ValidateXmlController@index')->name('validarxml');
Route::get('/uploadxml', 'App\Http\Controllers\UploadXmlController@index')->name('uploadxml');
Route::post('/uploadxml', 'App\Http\Controllers\UploadXmlController@uploadfile')->name('uploadxml');
Route::post('/sendblockchain/{id}', 'App\Http\Controllers\SignXmlController@sendblockchain')->name('sendblockchain');
Route::post('/digitalsign/{id}', 'App\Http\Controllers\SignXmlController@digitalsign')->name('digitalsign');
Route::post('/publish-hash/{id}','App\Http\Controllers\BlockchainController@publishHash')->name('publish-hash');
Route::post('/validate/{id}', 'App\Http\Controllers\ValidateXmlController@verifyIntegrity')->name('validate');
Route::get('data-entry/create', 'App\Http\Controllers\DataEntryController@create')->name('data_entry.create');
Route::post('data-entry/store', 'App\Http\Controllers\DataEntryController@store')->name('data_entry.store');



Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade');
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons');
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

