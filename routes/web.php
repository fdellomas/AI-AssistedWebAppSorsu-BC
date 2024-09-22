<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\QueryLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerSheetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('{path}', function () {
    return view('welcome');
})->where('path', '.+');

Route::prefix('query')->controller(QueryController::class)->group(function() {
    Route::post('/', 'query');
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
    Route::post('/store', 'store');
    Route::post('/delete', 'delete');
});

Route::prefix('queries')->controller(QueryLogController::class)->group(function() {
    Route::post('/', 'index');
    Route::post('/old', 'oldQueries');
});