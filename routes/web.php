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

use App\Src\Domain\Models\User;
use Facades\Plank\Mediable\MediaUploader;
use Illuminate\Support\Facades\DB;
use Plank\Mediable\Media;
use Spatie\Permission\Models\Role;

Route::get('test', function () {
    return 'testing ;)';
});


Route::get('api/medias', function () {
    return response()->json(
        Media::all()
    );
});

Route::get(
    '{uri}',
    '\\' . Pallares\LaravelNuxt\Controllers\NuxtController::class
)->where('uri', '.*');
