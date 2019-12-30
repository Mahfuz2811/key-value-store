<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {

    Route::get('/values', 'Api\KeyValueController@getValues');
    Route::post('/values', 'Api\KeyValueController@addValues');
    Route::patch('/values', 'Api\KeyValueController@updateValues');

    Route::get('/ttl', 'Api\TtlController@getTtl');
    Route::post('/ttl', 'Api\TtlController@updateTtl');

});

Route::fallback(function () {
    return response()->json([
        'status' => false,
        'message' => 'Page Not Found'
    ], 404);
});
