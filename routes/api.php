<?php

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

Route::post( 'register',[\App\Http\Controllers\AuthController::class,'register']);
Route::post( 'login',[\App\Http\Controllers\AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function(){

    /* START USER İŞLEMLERİ */
    Route::get('logout',[\App\Http\Controllers\AuthController::class,'logout']);
    Route::get( 'get',[\App\Http\Controllers\AuthController::class,'get']);
    Route::get( 'getByEmail',[\App\Http\Controllers\AuthController::class,'getByEmail']);
    Route::get( 'getByTeacher',[\App\Http\Controllers\AuthController::class,'getByTeacher']);
    Route::post( 'update',[\App\Http\Controllers\AuthController::class,'update']);
    Route::get( 'delete',[\App\Http\Controllers\AuthController::class,'delete']);
    /* END USER İŞLEMLERİ */

    /* START DERS İŞLEMLERİ */
    Route::post( 'lesson/create',[\App\Http\Controllers\LessonController::class,'create']);
    Route::post( 'lesson/update',[\App\Http\Controllers\LessonController::class,'update']);
    Route::post( 'lesson/delete',[\App\Http\Controllers\LessonController::class,'delete']);
    Route::get( 'lesson/get',[\App\Http\Controllers\LessonController::class,'get']);
    /* END DERS İŞLEMLERİ */

    /* START ŞUBE İŞLEMLERİ */
    Route::post( 'branch/create',[\App\Http\Controllers\BranchController::class,'create']);
    Route::post( 'branch/update',[\App\Http\Controllers\BranchController::class,'update']);
    Route::post( 'branch/delete',[\App\Http\Controllers\BranchController::class,'delete']);
    Route::get( 'branch/get',[\App\Http\Controllers\BranchController::class,'get']);
    /* END ŞUBE İŞLEMLERİ */

    /* START ÖĞRENCİ İŞLEMLERİ */
    Route::post( 'student/create',[\App\Http\Controllers\StudentController::class,'create']);
    Route::post( 'student/update',[\App\Http\Controllers\StudentController::class,'update']);
    Route::post( 'student/delete',[\App\Http\Controllers\StudentController::class,'delete']);
    Route::get( 'student/get',[\App\Http\Controllers\StudentController::class,'get']);
    Route::get( 'student/getByStudentNo',[\App\Http\Controllers\StudentController::class,'getByStudentNo']);
    Route::get( 'student/getByBranchId',[\App\Http\Controllers\StudentController::class,'getByBranchId']);
    /* END ÖĞRENCİ İŞLEMLERİ */

    /* START ÖĞRETMEN-DERS İŞLEMLERİ */
    Route::post( 'teacherlesson/create',[\App\Http\Controllers\TeacherLessonController::class,'create']);
    Route::post( 'teacherlesson/update',[\App\Http\Controllers\TeacherLessonController::class,'update']);
    Route::post( 'teacherlesson/delete',[\App\Http\Controllers\TeacherLessonController::class,'delete']);
    Route::get( 'teacherlesson/get',[\App\Http\Controllers\TeacherLessonController::class,'get']);
    Route::get( 'teacherlesson/getByLessonId',[\App\Http\Controllers\TeacherLessonController::class,'getByLessonId']);
    Route::get( 'teacherlesson/getByTeacherId',[\App\Http\Controllers\TeacherLessonController::class,'getByTeacherId']);
    /* END ÖĞRETMEN-DERS İŞLEMLERİ*/

    /* START ÖĞRENCİ-DERS İŞLEMLERİ */
    Route::post( 'studentlesson/create',[\App\Http\Controllers\StudentLessonController::class,'create']);
    Route::post( 'studentlesson/update',[\App\Http\Controllers\StudentLessonController::class,'update']);
    Route::post( 'studentlesson/delete',[\App\Http\Controllers\StudentLessonController::class,'delete']);
    Route::get( 'studentlesson/get',[\App\Http\Controllers\StudentLessonController::class,'get']);
    Route::get( 'studentlesson/getByStudentId',[\App\Http\Controllers\StudentLessonController::class,'getByStudentId']);
    Route::get( 'studentlesson/getByLessonId',[\App\Http\Controllers\StudentLessonController::class,'getByLessonId']);
    /* END ÖĞRENCİ-DERS İŞLEMLERİ*/
});
