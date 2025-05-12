<?php

use App\Http\Controllers\Api\ParentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::get('validate', [\App\Http\Controllers\Api\AuthController::class, 'validate'])->middleware('auth:api');
    Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->middleware('role:admin');
    Route::post('update-profile', [\App\Http\Controllers\Api\AuthController::class, 'updateProfile'])->name('customer-auth.update')->middleware('auth:api');
    Route::post('change-password', [\App\Http\Controllers\Api\AuthController::class, 'changePassword'])->middleware('auth:api');
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:api');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('store/{model}', [App\Http\Controllers\Api\ModelController::class, 'store']);
    Route::get('index/{model}', [App\Http\Controllers\Api\ModelController::class, 'index']);
    Route::apiResource('parents', \App\Http\Controllers\Api\ParentController::class);
    Route::get('/parent-profile-personal',[\App\Http\Controllers\Api\ParentController::class,'profilePersonal']);
    Route::apiResource('classroom', \App\Http\Controllers\Api\ClassroomController::class);
    Route::apiResource('section', \App\Http\Controllers\Api\SectionController::class);
    Route::apiResource('student', \App\Http\Controllers\Api\StudentController::class);
    Route::get('/student-profile-personal',[\App\Http\Controllers\Api\StudentController::class,'profilePersonal']);
});
