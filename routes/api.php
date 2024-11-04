<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KindergartenController;
use App\Http\Controllers\Api\GreenAreaController;
use App\Http\Controllers\Api\EducationDataController;
use App\Http\Controllers\Api\TransitStopController;
use App\Http\Controllers\Api\AirQualityController;
use App\Http\Controllers\Api\QualityOfLifeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', [UserController::class, 'getUser']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/kindergartens', [KindergartenController::class, 'index']);
Route::get('/kindergartens/{id}', [KindergartenController::class, 'show']);
Route::post('/nearby-kindergartens', [KindergartenController::class, 'nearby']);

Route::get('/green-areas', [GreenAreaController::class, 'index']);
Route::get('/green-areas/{id}', [GreenAreaController::class, 'show']);
Route::post('/nearby-green-areas', [GreenAreaController::class, 'nearby']);

Route::get('/education-data', [EducationDataController::class, 'index']);
Route::get('/education-data/{id}', [EducationDataController::class, 'show']);
Route::post('/nearby-education-data', [EducationDataController::class, 'nearby']);

Route::get('/transit-stops', [TransitStopController::class, 'index']);
Route::get('/transit-stops/{id}', [TransitStopController::class, 'show']);
Route::post('/nearby-transit-stops', [TransitStopController::class, 'nearby']);

Route::post('/air-quality', [AirQualityController::class, 'getAirQualityByLocation']);

Route::post('/calculate-quality-of-life', [QualityOfLifeController::class, 'calculate']);