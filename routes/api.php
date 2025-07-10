<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\Auth;
use App\Http\Controllers\v1\UserVerification;
use App\Http\Controllers\v1\Admin\Application;
use App\Http\Controllers\v1\NewPassword;
use App\Http\Controllers\v1\ChatMessageController;
use App\Http\Controllers\v1\DiagnosisController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ArticleController;

Route::prefix('v1')->group(function () {

    // ✅ Public Routes
    Route::post('/registration',        [UserController::class, 'store']);
    Route::post('/login',               [Auth::class, 'login']);
    Route::post('/password-reset',      [NewPassword::class, 'requestPasswordReset']);
    Route::put('/reset-password',       [NewPassword::class, 'resetPassword']);

    // ✅ Auth Routes, accessible without verification
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/email-verification',      [UserVerification::class, 'sendVerificationEmail']);
        Route::get('/verify-email/{id}/{hash}', [UserVerification::class, 'verify'])->name("verification.verify");
        Route::get('/dashboard',                [Application::class, 'dashboard']);
        Route::get('/logout',                   [Auth::class, 'logout']);

        // ✅ ChatBot Routes
        Route::get('/chat',   [ChatMessageController::class, 'index']);
        Route::post('/chat',  [ChatMessageController::class, 'store']);

        // ✅ Profile Routes
        Route::get('/profile',        [UserController::class, 'getProfile']);
        Route::put('/profile',        [UserController::class, 'editProfile']);
        Route::delete('/profile',     [UserController::class, 'deleteProfile']);

        // ✅ Diagnosis Route (محمي)
        Route::post('/diagnose', [DiagnosisController::class, 'diagnose']);

        // ✅ Patients Routes (محمية)
        Route::post('/patients', [PatientController::class, 'store']);
        Route::get('/patients', [PatientController::class, 'index']);
        Route::get('/patients/{id}', [PatientController::class, 'show']);
        Route::put('/patients/{id}', [PatientController::class, 'update']);
        Route::delete('/patients/{id}', [PatientController::class, 'destroy']);
        Route::get('/patients/{id}/diagnoses', [PatientController::class, 'diagnosisHistory']);
    });

    // ✅ Fallback route
    Route::fallback(function(){
        return response()->json('Page Not Found. If error persists, contact info@website.com', 404);
    });

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/articles/{id}/favorite', [ArticleController::class, 'toggleFavorite']);
        Route::get('/articles/{id}/share', [ArticleController::class, 'share']);
        Route::get('/articles/{id}/download', [ArticleController::class, 'download']);

        Route::get('/favorites', [ArticleController::class, 'myFavorites']);
        Route::get('/favorites/{id}', [ArticleController::class, 'showFavorite']);
        Route::delete('/favorites/{id}', [ArticleController::class, 'removeFavorite']);
    });
});
