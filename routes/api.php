<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceStatusController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeacherController;
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
Route::post('/employees/{id}/edit', [EmployeeController::class, 'update']);
Route::delete('/employees/{id}/delete', [EmployeeController::class, 'destroy']);

Route::get('/teachers', [TeacherController::class, 'index']);
Route::post('/teachers', [TeacherController::class, 'store']);
Route::get('/teachers/{id}', [TeacherController::class, 'show']);
Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit']);
Route::post('/teachers/{id}/edit', [TeacherController::class, 'update']);
Route::delete('/teachers/{id}/delete', [TeacherController::class, 'destroy']);


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
Route::post('/students/{id}/edit', [StudentController::class, 'update']);
Route::delete('/students/{id}/delete', [StudentController::class, 'destroy']);

Route::get('/classes', [ClassesController::class, 'index']);
Route::post('/classes', [ClassesController::class, 'store']);
Route::get('/classes/{id}', [ClassesController::class, 'show']);
Route::get('/classes/{id}/edit', [ClassesController::class, 'edit']);
Route::put('/classes/{id}/edit', [ClassesController::class, 'update']);
Route::delete('/classes/{id}/delete', [ClassesController::class, 'destroy']);

Route::get('/sections', [SectionsController::class, 'index']);
Route::post('/sections', [SectionsController::class, 'store']);
Route::get('/sections/{id}', [SectionsController::class, 'show']);
Route::get('/sections/{id}/edit', [SectionsController::class, 'edit']);
Route::put('/sections/{id}/edit', [SectionsController::class, 'update']);
Route::delete('/sections/{id}/delete', [SectionsController::class, 'destroy']);

Route::get('/attendances', [AttendanceController::class, 'index']);
Route::post('/attendances', [AttendanceController::class, 'store']);
Route::get('/attendances/{id}', [AttendanceController::class, 'show']);
Route::get('/attendances/{id}/edit', [AttendanceController::class, 'edit']);
Route::post('/attendances/{id}/edit', [AttendanceController::class, 'update']);
Route::delete('/attendances/{id}/delete', [AttendanceController::class, 'destroy']);

Route::get('/attendance_status', [AttendanceStatusController::class, 'index']);
Route::get('/studentsByClass/{id}', [StudentController::class, 'getStudentsByClass']);

Route::get('/classrooms', [ClassroomController::class, 'index']);
Route::post('/classrooms', [ClassroomController::class, 'store']);
Route::get('/classrooms/{id}', [ClassroomController::class, 'show']);
Route::get('/classrooms/{id}/edit', [ClassroomController::class, 'edit']);
Route::post('/classrooms/{id}/edit', [ClassroomController::class, 'update']);
Route::delete('/classrooms/{id}/delete', [ClassroomController::class, 'destroy']);

Route::get('/subjects', [SubjectsController::class, 'index']);
Route::post('/subjects', [SubjectsController::class, 'store']);
Route::get('/subjects/{id}', [SubjectsController::class, 'show']);
Route::get('/subjects/{id}/edit', [SubjectsController::class, 'edit']);
Route::post('/subjects/{id}/edit', [SubjectsController::class, 'update']);
Route::delete('/subjects/{id}/delete', [SubjectsController::class, 'destroy']);

Route::get('/subjectsByClass/{id}', [SubjectsController::class, 'getsubjectsByClass']);
