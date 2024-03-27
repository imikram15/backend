<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user' , function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/employees/{id}', [EmployeeController::class, 'show']);
Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit']);
Route::put('/employees/{id}/edit', [EmployeeController::class, 'update']);
Route::delete('/employees/{id}/delete', [EmployeeController::class, 'destroy']);

Route::get('/designations', [DesignationController::class, 'index']);
Route::post('/designations', [DesignationController::class, 'store']);
Route::get('/designations/{id}', [DesignationController::class, 'show']);
Route::get('/designations/{id}/edit', [DesignationController::class, 'edit']);
Route::put('/designations/{id}/edit', [DesignationController::class, 'update']);
Route::delete('/designations/{id}/delete', [DesignationController::class, 'destroy']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit']);
Route::put('/categories/{id}/edit', [CategoryController::class, 'update']);
Route::delete('/categories/{id}/delete', [CategoryController::class, 'destroy']);

Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/departments', [DepartmentController::class, 'store']);
Route::get('/departments/{id}', [DepartmentController::class, 'show']);
Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit']);
Route::put('/departments/{id}/edit', [DepartmentController::class, 'update']);
Route::delete('/departments/{id}/delete', [DepartmentController::class, 'destroy']);


Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::get('/students/{id}/edit', [StudentController::class, 'edit']);
Route::put('/students/{id}/edit', [StudentController::class, 'update']);
Route::delete('/students/{id}/delete', [StudentController::class, 'destroy']);

Route::get('/school_class', [SchoolClassController::class, 'index']);
Route::post('/school_class', [SchoolClassController::class, 'store']);
Route::get('/school_class/{id}', [SchoolClassController::class, 'show']);
Route::get('/school_class/{id}/edit', [SchoolClassController::class, 'edit']);
Route::put('/school_class/{id}/edit', [SchoolClassController::class, 'update']);
Route::delete('/school_class/{id}/delete', [SchoolClassController::class, 'destroy']);