<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceStatusController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassRoutineController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExamCategoryController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\exportController;
use App\Http\Controllers\exportUserController;
use App\Http\Controllers\MarksController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentFeeController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UsersController;
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


// Route::get('users/export', [exportUserController::class, 'export']);

Route::get('studentFee/export/{startDate}/{endDate}/{class_id}/{status}', [exportController::class, 'StudentFeeExport']);

Route::middleware('auth:sanctum')->get('/user' , function (Request $request) {
    return $request->user();
});


Route::get('/users', [UsersController::class, 'index']);
Route::get('/usersByRole/{id}', [UsersController::class, 'GetUserByRole']);
Route::get('/usersByType/{id}', [UsersController::class, 'GetUserByType']);
Route::post('/users', [UsersController::class, 'store']);
Route::get('/users/{id}', [UsersController::class, 'show']);
Route::get('/users/{id}/edit', [UsersController::class, 'show']);
Route::post('/users/{id}/edit', [UsersController::class, 'update']);
Route::delete('/users/{id}/delete', [UsersController::class, 'destroy']);

Route::get('/roles', [RolesController::class, 'index']);
Route::post('/roles', [RolesController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);

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

Route::get('/routines', [ClassRoutineController::class, 'index']);
Route::get('/routineByClass/{id}', [ClassRoutineController::class, 'getRoutinesByClass']);
Route::post('/routines', [ClassRoutineController::class, 'store']);
Route::get('/routines/{id}', [ClassRoutineController::class, 'show']);
Route::get('/routines/{id}/edit', [ClassRoutineController::class, 'edit']);
Route::post('/routines/{id}/edit', [ClassRoutineController::class, 'update']);
Route::delete('/routines/{id}/delete', [ClassRoutineController::class, 'destroy']);

Route::get('/getsyllabusByClass/{classid}', [SyllabusController::class, 'getsyllabusByClass']);

Route::get('/syllabus', [SyllabusController::class, 'index']);
Route::post('/syllabus', [SyllabusController::class, 'store']);
Route::get('/syllabus/{id}', [SyllabusController::class, 'show']);
Route::get('/syllabus/{id}/edit', [SyllabusController::class, 'edit']);
Route::post('/syllabus/{id}/edit', [SyllabusController::class, 'update']);
Route::delete('/syllabus/{id}/delete', [SyllabusController::class, 'destroy']);

Route::get('/examCategories', [ExamCategoryController::class, 'index']);
Route::post('/examCategories', [ExamCategoryController::class, 'store']);
Route::get('/examCategories/{id}', [ExamCategoryController::class, 'show']);
Route::get('/examCategories/{id}/edit', [ExamCategoryController::class, 'edit']);
Route::post('/examCategories/{id}/edit', [ExamCategoryController::class, 'update']);
Route::delete('/examCategories/{id}/delete', [ExamCategoryController::class, 'destroy']);

Route::get('/getExamByClass/{classid}', [ExamsController::class, 'getExamsByClass']);

Route::get('/exams', [ExamsController::class, 'index']);
Route::post('/exams', [ExamsController::class, 'store']);
Route::get('/exams/{id}', [ExamsController::class, 'show']);
Route::get('/exams/{id}/edit', [ExamsController::class, 'edit']);
Route::post('/exams/{id}/edit', [ExamsController::class, 'update']);
Route::delete('/exams/{id}/delete', [ExamsController::class, 'destroy']);

Route::get('/marks', [MarksController::class, 'index']);
Route::post('/marks', [MarksController::class, 'store']);
Route::post('/marks/{id}/edit', [MarksController::class, 'store']);
Route::get('/marks/{id}', [MarksController::class, 'show']);
Route::get('/marks/{id}/edit', [MarksController::class, 'edit']);
Route::delete('/marks/{id}/delete', [MarksController::class, 'destroy']);

Route::get('/studentFee', [StudentFeeController::class, 'index']);
Route::post('/bulkStudentFee', [StudentFeeController::class, 'createBulkStudentFees']);
Route::post('/studentFee', [StudentFeeController::class, 'store']);
Route::get('/studentFee/{id}', [StudentFeeController::class, 'edit']);
Route::post('/studentFee/{id}/edit', [StudentFeeController::class, 'update']);
Route::delete('/studentFee/{id}/delete', [StudentFeeController::class, 'destroy']);

