<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get only logged-in admin's folder (Sorted Ascending)
    |--------------------------------------------------------------------------
    */
    private function ownedFolder($id)
    {
        return Folder::with([
            'students' => function ($query) {
                $query->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
                    ->orderBy('roll_number', 'ASC')
                    ->orderBy('id', 'ASC');
            }
        ])
        ->where('created_by', Auth::id())
        ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Get only student's own folder student
    |--------------------------------------------------------------------------
    */
    private function ownedStudent($folderId, $studentId)
    {
        $folder = $this->ownedFolder($folderId);

        $student = Student::where('id', $studentId)
            ->where('folder_id', $folder->id)
            ->firstOrFail();

        return [$folder, $student];
    }

    /*
    |--------------------------------------------------------------------------
    | Attendance Page
    |--------------------------------------------------------------------------
    */
    public function show(Request $request, $id)
    {
        $folder = $this->ownedFolder($id);

        $today = Carbon::today()->toDateString();

        $selectedDate = $request->get('date', $today);

        try {
            $selectedDate = Carbon::parse($selectedDate)->toDateString();
        } catch (\Exception $e) {
            $selectedDate = $today;
        }

        if ($selectedDate > $today) {
            $selectedDate = $today;
        }

        $students = $folder->students;

        $earliestStudentDate = null;

        if ($students->count() > 0) {
            $dates = $students
                ->filter(function ($student) {
                    return !empty($student->created_at);
                })
                ->map(function ($student) {
                    return Carbon::parse($student->created_at)
                        ->startOfDay();
                });

            if ($dates->count() > 0) {
                $earliestStudentDate = $dates->min()->toDateString();
            }
        }

        // Auto-mark all applicable students as 'present' ONLY if it's a PAST missed date and no attendance exists yet
        $existingCount = Attendance::where('folder_id', $folder->id)
            ->where('date', $selectedDate)
            ->count();

        if ($existingCount === 0 && $selectedDate < $today) {
            DB::transaction(function () use ($students, $selectedDate, $folder) {
                foreach ($students as $student) {
                    $studentAddedDate = $student->created_at
                        ? Carbon::parse($student->created_at)->toDateString()
                        : null;

                    if ($studentAddedDate && $selectedDate < $studentAddedDate) {
                        continue;
                    }

                    Attendance::create([
                        'folder_id' => $folder->id,
                        'student_id' => $student->id,
                        'date' => $selectedDate,
                        'status' => 'present', // Past missed date automatically becomes Present
                        'marked_by' => Auth::id(),
                        'marked_at' => now(),
                    ]);
                }
            });
        }

        $attendances = Attendance::where('folder_id', $folder->id)
            ->where('date', $selectedDate)
            ->get()
            ->keyBy('student_id');

        $attendanceHistory = Attendance::where('folder_id', $folder->id)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('student_id');

        $selected_status = [];

        foreach ($students as $student) {
            if (isset($attendances[$student->id])) {
                $selected_status[$student->id] =
                    $attendances[$student->id]->status;
            } else {
                $selected_status[$student->id] = null;
            }
        }

        foreach ($students as $student) {
            if ($student->created_at) {
                $student->student_added_date =
                    Carbon::parse($student->created_at)
                        ->toDateString();
            } else {
                $student->student_added_date = null;
            }

            if ($student->student_added_date) {
                $student->attendance_applicable =
                    $selectedDate >= $student->student_added_date;
            } else {
                $student->attendance_applicable = true;
            }
        }

        return view(
            'admin.folders.attendance',
            compact(
                'folder',
                'students',
                'attendances',
                'selectedDate',
                'today',
                'earliestStudentDate',
                'attendanceHistory',
                'selected_status'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Submit or Update Attendance
    |--------------------------------------------------------------------------
    */
    public function submit(Request $request, $id)
    {
        $folder = $this->ownedFolder($id);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['nullable', 'array'],
            'status.*' => ['nullable', 'in:present,absent'],
        ]);

        $selectedDate = Carbon::parse(
            $validated['date']
        )->toDateString();

        $today = Carbon::today()->toDateString();

        if ($selectedDate > $today) {
            return back()
                ->with(
                    'error',
                    'Future date attendance is not allowed.'
                )
                ->withInput();
        }

        $students = $folder->students()
            ->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
            ->orderBy('roll_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $statuses = $request->input('status', []);

        DB::transaction(function () use (
            $students,
            $statuses,
            $selectedDate,
            $folder
        ) {
            foreach ($students as $student) {

                $studentAddedDate = $student->created_at
                    ? Carbon::parse($student->created_at)->toDateString()
                    : null;

                if (
                    $studentAddedDate &&
                    $selectedDate < $studentAddedDate
                ) {
                    continue;
                }

                $statusToSave = $statuses[$student->id] ?? 'absent';

                Attendance::updateOrCreate(
                    [
                        'folder_id' => $folder->id,
                        'student_id' => $student->id,
                        'date' => $selectedDate,
                    ],
                    [
                        'status' => $statusToSave,
                        'marked_by' => Auth::id(),
                        'marked_at' => now(),
                    ]
                );
            }
        });

        return redirect()
            ->route(
                'admin.attendance.show',
                [
                    'id' => $folder->id,
                    'date' => $selectedDate,
                ]
            )
            ->with(
                'success',
                'Attendance saved successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Student 10 Click Unlock
    |--------------------------------------------------------------------------
    */
    public function unlockPast(Request $request, $folderId, $studentId)
    {
        [$folder, $student] =
            $this->ownedStudent($folderId, $studentId);

        $key =
            'attendance_history_clicks_' .
            $folder->id .
            '_' .
            $student->id;

        $clickCount =
            (int) session()->get($key, 0);

        $clickCount++;

        if ($clickCount > 10) {
            $clickCount = 10;
        }

        session()->put($key, $clickCount);

        if ($clickCount < 10) {
            return response()->json([
                'success' => true,
                'clicks' => $clickCount,
                'unlocked' => false,
            ]);
        }

        session()->put(
            'attendance_history_unlocked_' .
            $folder->id .
            '_' .
            $student->id,
            true
        );

        session()->forget($key);

        return redirect()
            ->route(
                'admin.attendance.history',
                [
                    'folderId' => $folder->id,
                    'studentId' => $student->id,
                ]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Student Attendance History
    |--------------------------------------------------------------------------
    */
    public function history($folderId, $studentId)
    {
        [$folder, $student] =
            $this->ownedStudent($folderId, $studentId);

        $unlocked = session()->get(
            'attendance_history_unlocked_' .
            $folder->id .
            '_' .
            $student->id,
            false
        );

        if (!$unlocked) {
            return redirect()
                ->route(
                    'admin.attendance.show',
                    $folder->id
                )
                ->with(
                    'error',
                    'Student attendance history is locked. Complete the 10-click unlock.'
                );
        }

        $attendances = Attendance::where('folder_id', $folder->id)
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        return view(
            'admin.folders.student-history',
            compact(
                'folder',
                'student',
                'attendances'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Save / Change Specific Student Attendance
    |--------------------------------------------------------------------------
    */
    public function saveHistory(
        Request $request,
        $folderId,
        $studentId
    ) {
        [$folder, $student] =
            $this->ownedStudent($folderId, $studentId);

        $unlocked = session()->get(
            'attendance_history_unlocked_' .
            $folder->id .
            '_' .
            $student->id,
            false
        );

        if (!$unlocked) {
            abort(403, 'Attendance history is locked.');
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent'],
        ]);

        $selectedDate = Carbon::parse(
            $validated['date']
        )->toDateString();

        $today = Carbon::today()->toDateString();

        if ($selectedDate > $today) {
            return back()
                ->with(
                    'error',
                    'Future attendance is not allowed.'
                );
        }

        $studentAddedDate = $student->created_at
            ? Carbon::parse($student->created_at)->toDateString()
            : null;

        if (
            $studentAddedDate &&
            $selectedDate < $studentAddedDate
        ) {
            return back()
                ->with(
                    'error',
                    'Attendance cannot be added before the student was added.'
                );
        }

        Attendance::updateOrCreate(
            [
                'folder_id' => $folder->id,
                'student_id' => $student->id,
                'date' => $selectedDate,
            ],
            [
                'status' => $validated['status'],
                'marked_by' => Auth::id(),
                'marked_at' => now(),
            ]
        );

        return redirect()
            ->route(
                'admin.attendance.history',
                [
                    'folderId' => $folder->id,
                    'studentId' => $student->id,
                ]
            )
            ->with(
                'success',
                'Attendance updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Lock Student History
    |--------------------------------------------------------------------------
    */
    public function lockHistory($folderId, $studentId)
    {
        [$folder, $student] =
            $this->ownedStudent($folderId, $studentId);

        session()->forget(
            'attendance_history_unlocked_' .
            $folder->id .
            '_' .
            $student->id
        );

        session()->forget(
            'attendance_history_unlost_clicks_' .
            $folder->id .
            '_' .
            $student->id
        );

        return redirect()
            ->route(
                'admin.attendance.show',
                $folder->id
            )
            ->with(
                'success',
                'Student attendance history locked again.'
            );
    }
}