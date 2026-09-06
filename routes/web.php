<?php

use Illuminate\Support\Facades\Route;


// =====================================================
// ADMIN CONTROLLERS
// =====================================================

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SubjectAttendanceController;
use App\Http\Controllers\Admin\SubjectReportController;


// =====================================================
// STAFF CONTROLLERS
// =====================================================

use App\Http\Controllers\Staff\AttendanceController as StaffAttendanceController;


// =====================================================
// STUDENT CONTROLLERS
// =====================================================

use App\Http\Controllers\Student\ReportController as StudentReportController;


// =====================================================
// LANDING PAGE
// =====================================================

Route::get('/', function () {
    return view('landing');
})->name('landing');


// =====================================================
// ADMIN AUTH
// =====================================================

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


// =====================================================
// ADMIN PANEL
// =====================================================

Route::middleware(['admin'])
    ->prefix('admin')
    ->group(function () {


        // =================================================
        // DASHBOARD
        // =================================================

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('admin.dashboard');


        // =================================================
        // NORMAL FOLDERS
        // =================================================

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


        // =================================================
        // STUDENTS
        // =================================================

        // ⭐ Open Students Management Page
        Route::get(
            '/folders/{id}/students',
            [StudentController::class, 'index']
        )->name('admin.students.index');


        // Add Student
        Route::post(
            '/folders/{id}/students',
            [StudentController::class, 'store']
        )->name('admin.students.store');


        // Update Student
        Route::put(
            '/students/{id}',
            [StudentController::class, 'update']
        )->name('admin.students.update');


        // Delete Student
        Route::delete(
            '/students/{id}',
            [StudentController::class, 'destroy']
        )->name('admin.students.destroy');


        // =================================================
        // NORMAL FOLDER ATTENDANCE
        // =================================================

        Route::get(
            '/folders/{id}/mark',
            [AttendanceController::class, 'show']
        )->name('admin.attendance.show');


        Route::post(
            '/folders/{id}/attendance/submit',
            [AttendanceController::class, 'submit']
        )->name('admin.attendance.submit');


        // =================================================
        // NORMAL FOLDER REPORT
        // =================================================

        Route::get(
            '/folders/{id}/report',
            [ReportController::class, 'show']
        )->name('admin.report.show');


        // =================================================
        // NORMAL FOLDER TOTAL
        // =================================================

        Route::get(
            '/folders/{id}/total',
            [ReportController::class, 'total']
        )->name('admin.report.total');


        // =================================================
        // NORMAL FOLDER PDF REPORT
        // =================================================

        Route::get(
            '/folders/{id}/pdf',
            [ReportController::class, 'pdf']
        )->name('admin.report.pdf');


        // #################################################
        // SUBJECT-WISE SECTION
        // #################################################

        // Dashboard
        //     ↓
        // Create Subject
        //     ↓
        // Subject Folder
        //     ↓
        // Students
        //     ↓
        // Select Students from Normal Folders
        //     ↓
        // Mark Attendance
        // Report
        // Total
        // #################################################


        // =================================================
        // CREATE SUBJECT
        // =================================================

        Route::post(
            '/subjects',
            [SubjectController::class, 'store']
        )->name('admin.subjects.store');


        // =================================================
        // OPEN SUBJECT
        // =================================================

        Route::get(
            '/subjects/{id}',
            [SubjectController::class, 'show']
        )->name('admin.subjects.show');


        // =================================================
        // SUBJECT STUDENTS PAGE
        // =================================================

        // ⭐ Open Subject Students Management Page
        Route::get(
            '/subjects/{id}/students',
            [SubjectController::class, 'students']
        )->name('admin.subjects.students');


        // =================================================
        // SELECT STUDENTS FROM NORMAL FOLDERS
        // =================================================

        Route::post(
            '/subjects/{id}/select-students',
            [SubjectController::class, 'selectStudents']
        )->name('admin.subjects.select');


        // =================================================
        // SUBJECT-WISE MARK ATTENDANCE
        // =================================================

        Route::get(
            '/subjects/{id}/attendance',
            [SubjectAttendanceController::class, 'show']
        )->name('admin.subjects.attendance');


        Route::post(
            '/subjects/{id}/attendance/submit',
            [SubjectAttendanceController::class, 'submit']
        )->name('admin.subjects.attendance.submit');


        // =================================================
        // SUBJECT-WISE REPORT
        // =================================================

        Route::get(
            '/subjects/{id}/report',
            [SubjectReportController::class, 'show']
        )->name('admin.subjects.report');


        // =================================================
        // SUBJECT-WISE TOTAL
        // =================================================

        Route::get(
            '/subjects/{id}/total',
            [SubjectReportController::class, 'total']
        )->name('admin.subjects.total');

    });


// =====================================================
// STAFF AUTH
// =====================================================

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


// =====================================================
// STAFF PANEL
// =====================================================

Route::middleware(['staff'])
    ->prefix('staff')
    ->group(function () {


        // =================================================
        // STAFF FOLDERS
        // =================================================

        Route::get(
            '/folders',
            [StaffAttendanceController::class, 'index']
        )->name('staff.folders');


        // =================================================
        // STAFF MARK ATTENDANCE
        // =================================================

        Route::get(
            '/folders/{id}/mark',
            [StaffAttendanceController::class, 'show']
        )->name('staff.attendance.show');


        Route::post(
            '/folders/{id}/submit',
            [StaffAttendanceController::class, 'submit']
        )->name('staff.attendance.submit');

    });


// =====================================================
// STUDENT PANEL
// =====================================================

Route::prefix('student')
    ->group(function () {


        // =================================================
        // STUDENT FOLDERS
        // =================================================

        Route::get(
            '/folders',
            [StudentReportController::class, 'folders']
        )->name('student.folders');


        // =================================================
        // STUDENT REPORT
        // =================================================

        Route::get(
            '/folders/{id}/report',
            [StudentReportController::class, 'show']
        )->name('student.report.show');


        // =================================================
        // STUDENT PDF REPORT
        // =================================================

        Route::get(
            '/folders/{id}/pdf',
            [StudentReportController::class, 'pdf']
        )->name('student.report.pdf');

    });