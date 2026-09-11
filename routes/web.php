<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SubjectAttendanceController;
use App\Http\Controllers\Admin\SubjectReportController;

use App\Http\Controllers\Staff\AttendanceController as StaffAttendanceController;
use App\Http\Controllers\Student\ReportController as StudentReportController;


/*
|--------------------------------------------------------------------------
| Landing
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/login',
    [DashboardController::class, 'showLogin']
)->name('admin.login');

Route::post(
    '/admin/login',
    [DashboardController::class, 'login']
)->name('admin.login.post');

Route::post(
    '/admin/logout',
    [DashboardController::class, 'logout']
)->name('admin.logout');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('admin.dashboard');


        /*
        |--------------------------------------------------------------------------
        | FOLDERS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/folders',
            [FolderController::class, 'store']
        )->name('admin.folders.store');

        Route::get(
            '/folders/{id}',
            [FolderController::class, 'show']
        )->name('admin.folders.show');

        Route::put(
            '/folders/{id}',
            [FolderController::class, 'update']
        )->name('admin.folders.update');

        Route::delete(
            '/folders/{id}',
            [FolderController::class, 'destroy']
        )->name('admin.folders.destroy');


        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/folders/{id}/students',
            [StudentController::class, 'index']
        )->name('admin.students.index');

        Route::post(
            '/folders/{id}/students',
            [StudentController::class, 'store']
        )->name('admin.students.store');

        Route::put(
            '/students/{id}',
            [StudentController::class, 'update']
        )->name('admin.students.update');

        Route::delete(
            '/students/{id}',
            [StudentController::class, 'destroy']
        )->name('admin.students.destroy');


        /*
        |--------------------------------------------------------------------------
        | NORMAL ATTENDANCE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/folders/{id}/mark',
            [AttendanceController::class, 'show']
        )->name('admin.attendance.show');

        Route::post(
            '/folders/{id}/attendance/submit',
            [AttendanceController::class, 'submit']
        )->name('admin.attendance.submit');


        /*
        |--------------------------------------------------------------------------
        | STUDENT-SPECIFIC ATTENDANCE HISTORY
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/folders/{folderId}/students/{studentId}/attendance/unlock',
            [AttendanceController::class, 'unlockPast']
        )->name('admin.attendance.unlock');

        Route::get(
            '/folders/{folderId}/students/{studentId}/attendance/history',
            [AttendanceController::class, 'history']
        )->name('admin.attendance.history');

        Route::post(
            '/folders/{folderId}/students/{studentId}/attendance/history',
            [AttendanceController::class, 'saveHistory']
        )->name('admin.attendance.history.save');

        Route::post(
            '/folders/{folderId}/students/{studentId}/attendance/history/lock',
            [AttendanceController::class, 'lockHistory']
        )->name('admin.attendance.history.lock');


        /*
        |--------------------------------------------------------------------------
        | REPORT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/folders/{id}/report',
            [ReportController::class, 'show']
        )->name('admin.report.show');

        Route::get(
            '/folders/{id}/total',
            [ReportController::class, 'total']
        )->name('admin.report.total');

        Route::get(
            '/folders/{id}/pdf',
            [ReportController::class, 'pdf']
        )->name('admin.report.pdf');


        /*
        |--------------------------------------------------------------------------
        | SUBJECT-WISE
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/subjects',
            [SubjectController::class, 'store']
        )->name('admin.subjects.store');

        Route::get(
            '/subjects/{id}',
            [SubjectController::class, 'show']
        )->name('admin.subjects.show');

        // Added Subject Delete Route to fix Method Not Allowed Error
        Route::delete(
            '/subjects/{id}',
            [SubjectController::class, 'destroy']
        )->name('admin.subjects.destroy');

        Route::get(
            '/subjects/{id}/students',
            [SubjectController::class, 'students']
        )->name('admin.subjects.students');

        // Subject Students Actions Routes
        Route::post(
            '/subjects/{id}/students',
            [SubjectController::class, 'storeStudent']
        )->name('admin.subjects.students.store');

        Route::post(
            '/subjects/{id}/copy-students',
            [SubjectController::class, 'copyStudents']
        )->name('admin.subjects.students.copy');

        Route::put(
            '/subject-students/{id}',
            [SubjectController::class, 'updateStudent']
        )->name('admin.subjects.students.update');

        Route::delete(
            '/subject-students/{id}',
            [SubjectController::class, 'destroyStudent']
        )->name('admin.subjects.students.destroy');

        Route::post(
            '/subjects/{id}/select-students',
            [SubjectController::class, 'selectStudents']
        )->name('admin.subjects.select');

        Route::get(
            '/subjects/{id}/attendance',
            [SubjectAttendanceController::class, 'show']
        )->name('admin.subjects.attendance');

        Route::post(
            '/subjects/{id}/attendance/submit',
            [SubjectAttendanceController::class, 'submit']
        )->name('admin.subjects.attendance.submit');

        Route::get(
            '/subjects/{id}/report',
            [SubjectReportController::class, 'show']
        )->name('admin.subjects.report');

        Route::get(
            '/subjects/{id}/total',
            [SubjectReportController::class, 'total']
        )->name('admin.subjects.total');

        // Added Subject PDF Report Route
        Route::get(
            '/subjects/{id}/pdf',
            [SubjectReportController::class, 'pdf']
        )->name('admin.subjects.pdf');
    });


/*
|--------------------------------------------------------------------------
| STAFF LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/staff/login',
    [StaffAttendanceController::class, 'showLogin']
)->name('staff.login');

Route::post(
    '/staff/login',
    [StaffAttendanceController::class, 'login']
)->name('staff.login.post');

Route::post(
    '/staff/logout',
    [StaffAttendanceController::class, 'logout']
)->name('staff.logout');


/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/

Route::middleware(['staff'])
    ->prefix('staff')
    ->group(function () {

        Route::get(
            '/folders',
            [StaffAttendanceController::class, 'index']
        )->name('staff.folders');

        Route::get(
            '/folders/{id}/mark',
            [StaffAttendanceController::class, 'show']
        )->name('staff.attendance.show');

        Route::post(
            '/folders/{id}/mark',
            [StaffAttendanceController::class, 'submit']
        )->name('staff.attendance.mark.post');

        Route::post(
            '/folders/{id}/submit',
            [StaffAttendanceController::class, 'submit']
        )->name('staff.attendance.submit');

        Route::delete(
            '/folders/{id}',
            [StaffAttendanceController::class, 'destroy']
        )->name('staff.folders.destroy');

        Route::get(
            '/folders/{id}/report',
            [ReportController::class, 'show']
        )->name('staff.report.show');
    });


/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/

Route::prefix('student')
    ->group(function () {

        Route::get(
            '/folders',
            [StudentReportController::class, 'folders']
        )->name('student.folders');

        Route::get(
            '/folders/{id}/report',
            [StudentReportController::class, 'show']
        )->name('student.report.show');

        Route::get(
            '/folders/{id}/pdf',
            [StudentReportController::class, 'pdf']
        )->name('student.report.pdf');
    });