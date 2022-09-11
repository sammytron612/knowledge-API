<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ArticleController;
use App\Http\Middleware\BearerToken;

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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

*/

//Route::apiResource('article', [ArticleController::class]);

Route::middleware(BearerToken::class)->namespace('\App\Http\Controllers\Api')->group(function(){

    Route::get('/search/{searchTerm}', [App\Http\Controllers\Api\ApiController::class, 'search']);

    Route::get('/search-comment/{searchTerm}', [App\Http\Controllers\Api\ApiController::class, 'inCommentSearch']);

    Route::post('/create', [App\Http\Controllers\Api\ApiController::class, 'create']);

    Route::post('/update', [App\Http\Controllers\Api\ApiController::class, 'update']);

    Route::get('/show/{id}', [App\Http\Controllers\Api\ApiController::class, 'show']);

    Route::get('/all', [App\Http\Controllers\Api\ApiController::class, 'showAll']);

    Route::delete('/delete-attachment/{id}', [App\Http\Controllers\Api\ApiController::class, 'deleteAttachment']);

    Route::get('/show-body/{id}', [App\Http\Controllers\Api\ApiController::class, 'returnBody']);

    Route::get('/section/index', [App\Http\Controllers\Api\SectionController::class, 'index']);
    Route::get('/section', [App\Http\Controllers\Api\SectionController::class, 'returnSections']);
    Route::put('/section/update/{section}', [App\Http\Controllers\Api\SectionController::class, 'update']);
    Route::post('/section/create', [App\Http\Controllers\Api\SectionController::class, 'store']);


});

