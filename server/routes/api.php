<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Dashboard posts API endpoint
Route::get('/dashboard/posts', function () {
    // Simulate a delay to show loading indicators
    sleep(2);
    
    return response()->json([
        ['id' => 1, 'title' => 'Sample Post 1', 'content' => 'This is a sample post content'],
        ['id' => 2, 'title' => 'Sample Post 2', 'content' => 'Another sample post content'],
        ['id' => 3, 'title' => 'Sample Post 3', 'content' => 'Yet another sample post content'],
    ]);
});

// Test POST endpoint
Route::post('/test-post', function (Request $request) {
    // Simulate a delay to show loading indicators
    sleep(2);
    
    return response()->json([
        'success' => true,
        'message' => 'Test POST request received',
        'data' => $request->all()
    ]);
});

// Dashboard posts API endpoint
Route::get('/dashboard/posts', function () {
    // Simulate a delay to show loading indicators
    sleep(2);
    
    return response()->json([
        ['id' => 1, 'title' => 'Sample Post 1', 'content' => 'This is a sample post content'],
        ['id' => 2, 'title' => 'Sample Post 2', 'content' => 'Another sample post content'],
        ['id' => 3, 'title' => 'Sample Post 3', 'content' => 'Yet another sample post content'],
    ]);
});

// Test POST endpoint
Route::post('/test-post', function (Request $request) {
    // Simulate a delay to show loading indicators
    sleep(2);
    
    return response()->json([
        'success' => true,
        'message' => 'Test POST request received',
        'data' => $request->all()
    ]);
});