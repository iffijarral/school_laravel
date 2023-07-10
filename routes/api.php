<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PrevExamController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\StatisticsController;
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

Route::get('/hello', function () {
    return 'Hello, World!';
});

// User Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/signup', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
// Following route is to change password after logging in to the system
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->put('/change-password', [AuthController::class, 'changePassword']);
// Following route is to send email containg link to reset the password
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
// Validate token and redirect to get new password page 
Route::get('/password/reset/{token}', [AuthController::class, 'reset'])->name('password.reset');
// reset new password
Route::post('/password/reset', [AuthController::class, 'postReset']);
// Logout route
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->post('/logout', [AuthController::class, 'logout']);

// Package Routes
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->post('/packages', [PackageController::class, 'store']);

// Test Routes
Route::get('/tests', [TestController::class, 'index']);
// This route is only for example test. Because this one is free, so don't need middleware
Route::get('/test/{id}', [TestController::class, 'show2']);
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->get('/tests/{id}', [TestController::class, 'show']);

// Retrieve exam info like date, fee etc...
Route::get('exam/', [ExamController::class, 'index']);

// Retrieve previous exams 
Route::get('prevexams/', [PrevExamController::class, 'index']);
Route::get('prevexams/{id}', [PrevExamController::class, 'show']);

// Retrieve user from cookie
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->get('/session', [SessionController::class, 'index']);

// Retrieve statistics
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->get('/statistics', [StatisticsController::class, 'index']);
// Save statistics
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->post('/statistics', [StatisticsController::class, 'store']);

// Payment
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->post('create-payment-intent', [PaymentController::class, 'index']);
Route::middleware(['SanctumTokenFromCookie', 'auth:sanctum'])->post('save-payment-transaction', [PaymentController::class, 'store']);