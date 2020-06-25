<?php

use App\Src\Domain\Models\User;
use Illuminate\Http\Request;

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

Route::group(['middleware'=>'as-json'],function(){

    Route::get('test',function(){
        dd(auth('api')->user());
    });

    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('login', 'PassportController@login');
        Route::get('check/{email}', 'PassportController@checkEmail');
        Route::post('register', 'PassportController@register');
        Route::get('user/details', 'PassportController@details');
        Route::post('logout', 'PassportController@logout');
    });

    Route::group(['namespace' => 'Page'], function () {
        //Page routes
        Route::group(['middleware' => 'auth:api'], function () {
            //Page routes authenticated
        });
    });

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth:api', 'is-admin']], function () {
        //Admin routes goes here
        Route::resources([
            'usuarios'=>'UserController',
        ]);
        Route::resource('permisos','PermissionController')->only(['index','destroy','toggle','order']);
        Route::resource('roles','RoleController')->except(['medias']);
    });
});

