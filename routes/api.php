<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [RegisteredUserController::class, 'register']);
Route::get('/book', 'BookController@index');

Route::get('/mock-data', function () {
    return response()->json([
        'message' => 'This is a mock data response.',
        'data' => [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'age' => 30,
        ],
    ]);
});
