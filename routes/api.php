<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\QueryLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerSheetController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('query')->controller(QueryController::class)->group(function() {
    Route::post('/', 'askQuestion');
});

Route::get('/test', function() {
    return response()->json([
        'user' => auth()->user()->id
    ]);
});

Route::prefix('user')->controller(UserController::class)->group(function() {
    Route::post('/login', 'signin');
    Route::post('/register', 'signup');
    Route::get('/logout', 'logout');
    Route::post('/update', 'update');
    Route::post('/update-account', 'updateAccount');
});

Route::prefix('answer')->controller(AnswerSheetController::class)->group(function() {
    Route::post('/', 'index');
    Route::get('/show/{answer}', 'show');
    Route::patch('/update/{answer}', 'update');
    Route::post('/store', 'store');
    Route::post('/delete', 'delete');
    Route::get('/archive', 'archive');
    Route::patch('/restore/{id}', 'restore');
    Route::delete('/destroy/{id}', 'destroy');
});

Route::prefix('queries')->controller(QueryLogController::class)->group(function() {
    Route::post('/', 'index');
    Route::post('/old', 'oldQueries');
});

Route::prefix('knowledge-base')->controller(KnowledgeBaseController::class)->group(function() {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::post('/archive', 'archive');
    Route::get('/trashbin', 'trashbin');
    Route::post('/delete', 'delete');
    Route::post('/restore', 'restore');
    // Route::post('/test', 'test');
});

Route::prefix('post')->controller(PostController::class)->group(function() {
    Route::post('/store', 'store');
    Route::get('/', 'index');
    Route::get('/show/{post}', 'show');
    Route::patch('/edit/{post}', 'update');
    Route::delete('/delete/{id}', 'delete');
});