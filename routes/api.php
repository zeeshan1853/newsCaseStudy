<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    $data = [
        'message' => "Welcome to our API"
    ];
    return response()->json($data, 200);
});

Route::prefix('/v1')->group(function () {
    Route::get('/test', function () {
        return "Hello world";
    });

    Route::get('/news/sources', [NewsController::class, 'getSources']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/news', [NewsController::class, 'getNews']);
    Route::middleware('jwt.verify')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::get('/preference', [UserPreferenceController::class, 'index']);
        Route::post('/preference', [UserPreferenceController::class, 'store']);
    });
    Route::fallback(function () {
        return response()->json([
            'message' => 'Page Not Found. If error persists, contact info@news-web.com'
        ], 404);
    });
});