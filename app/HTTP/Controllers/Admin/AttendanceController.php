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
    | Get only logged-in admin's folder
    |--------------------------------------------------------------------------
    */
    private function ownedFolder($id)
    {
        return Folder::with([
            'students' => function ($query) {
                $query->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
                      ->orderBy('roll_number')
                      ->orderBy('id');
            }
        ])
        ->where('created_by', Auth::id())
        ->findOrFail($id);
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

        /*
        |--------------------------------------------------------------------------
        | Selected date
        |--------------------------------------------------------------------------
        */
        $selectedDate = $request->get('date', $today);

        try {
            $selectedDate = Carbon::parse($selectedDate)->toDateString();
        } catch (\Exception $e) {
            $selectedDate = $today;
        }

        /*
        |--------------------------------------------------------------------------
        | Future date is not allowed
        |--------------------------------------------------------------------------
        */
        if ($selectedDate > $today) {
            $selectedDate = $today;
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | This creates the $students variable required by Blade.
        |--------------------------------------------------------------------------
        */
        $students = $folder->students;


        /*
        |--------------------------------------------------------------------------
        | Earliest student added date
        |--------------------------------------------------------------------------
        */
        $earliestStudentDate = null;

        if ($students->count() > 0) {

            $dates = $students
                ->filter(function ($student) {
                    return !empty($student->created_at);
                })
                ->map(function ($student) {
                    return Carbon::parse($student->created_at)->startOfDay();
                });

            if ($dates->count() > 0) {
                $earliestStudentDate = $dates->min()->toDateString();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Selected date attendance
        |--------------------------------------------------------------------------
        */
        $attendances = Attendance::where('folder_id', $folder->id)
            ->where('date', $selectedDate)
            ->get()
            ->keyBy('student_id');


        /*
        |--------------------------------------------------------------------------
        | Complete attendance history
        |--------------------------------------------------------------------------
        */
        $attendanceHistory = Attendance::where('folder_id', $folder->id)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('student_id');


        /*
        |--------------------------------------------------------------------------
        | Selected status
        |--------------------------------------------------------------------------
        */
        $selected_status = [];

        foreach ($students as $student) {

            if (isset($attendances[$student->id])) {

                $selected_status[$student->id] =
                    $attendances[$student->id]->status;

            } else {

                $selected_status[$student->id] = null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Add calculated properties to each student
        |--------------------------------------------------------------------------
        */
        foreach ($students as $student) {

            if ($student->created_at) {

                $student->student_added_date =
                    Carbon::parse($student->created_at)->toDateString();

            } else {

                $student->student_added_date = null;
            }


            /*
            |--------------------------------------------------------------------------
            | Attendance applicable only after student was added
            |--------------------------------------------------------------------------
            */
            if ($student->student_added_date) {

                $student->attendance_applicable =
                    $selectedDate >= $student->student_added_date;

            } else {

                $student->attendance_applicable = true;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Past attendance unlock status
        |--------------------------------------------------------------------------
        */
        $pastUnlocked =
            session()->get('attendance_past_unlocked_' . $folder->id, false);


        /*
        |--------------------------------------------------------------------------
        | Return attendance view
        |--------------------------------------------------------------------------
        */
        return view(
            'admin.folders.attendance',
            compact(
                'folder',
                'students',
                'attendances',
                'selectedDate',
                'today',
                'earliestStudentDate',
                'pastUnlocked',
                'attendanceHistory',
                'selected_status'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Submit Attendance
    |--------------------------------------------------------------------------
    */
    public function submit(Request $request, $id)
    {
        $folder = $this->ownedFolder($id);

        /*
        |--------------------------------------------------------------------------
        | Validate request
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['nullable', 'array'],
            'status.*' => ['nullable', 'in:present,absent'],
        ]);


        $selectedDate = Carbon::parse(
            $validated['date']
        )->toDateString();

        $today = Carbon::today()->toDateString();


        /*
        |--------------------------------------------------------------------------
        | Future date blocked
        |--------------------------------------------------------------------------
        */
        if ($selectedDate > $today) {

            return back()
                ->with('error', 'Future date attendance is not allowed.')
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Past date unlock required
        |--------------------------------------------------------------------------
        */
        if ($selectedDate < $today) {

            $unlocked =
                session()->get(
                    'attendance_past_unlocked_' . $folder->id,
                    false
                );

            if (!$unlocked) {

                return back()
                    ->with(
                        'error',
                        'Past attendance is locked. Unlock it first.'
                    )
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Get students
        |--------------------------------------------------------------------------
        */
        $students = $folder->students()
            ->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
            ->orderBy('roll_number')
            ->orderBy('id')
            ->get();


        $statuses = $request->input('status', []);


        /*
        |--------------------------------------------------------------------------
        | Check every applicable student
        |--------------------------------------------------------------------------
        */
        $missingStudents = [];

        foreach ($students as $student) {

            $studentAddedDate = $student->created_at
                ? Carbon::parse($student->created_at)->toDateString()
                : null;


            /*
            |--------------------------------------------------------------------------
            | Student was not added on selected date
            |--------------------------------------------------------------------------
            */
            if (
                $studentAddedDate &&
                $selectedDate < $studentAddedDate
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Every applicable student MUST have status
            |--------------------------------------------------------------------------
            */
            if (
                !isset($statuses[$student->id]) ||
                !in_array(
                    $statuses[$student->id],
                    ['present', 'absent'],
                    true
                )
            ) {

                $missingStudents[] = $student->name;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Partial attendance not allowed
        |--------------------------------------------------------------------------
        */
        if (count($missingStudents) > 0) {

            return back()
                ->with(
                    'error',
                    'Please mark Present or Absent for every applicable student before submitting.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Save attendance
        |--------------------------------------------------------------------------
        |
        | updateOrCreate prevents duplicate records.
        | Existing attendance is updated, not deleted.
        |
        */
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


                /*
                |--------------------------------------------------------------------------
                | Before student added = no attendance
                |--------------------------------------------------------------------------
                */
                if (
                    $studentAddedDate &&
                    $selectedDate < $studentAddedDate
                ) {
                    continue;
                }


                Attendance::updateOrCreate(
                    [
                        'folder_id' => $folder->id,
                        'student_id' => $student->id,
                        'date' => $selectedDate,
                    ],
                    [
                        'status' => $statuses[$student->id],
                        'marked_by' => Auth::id(),
                        'marked_at' => now(),
                    ]
                );
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */
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
    | Unlock Past Attendance
    |--------------------------------------------------------------------------
    */
    public function unlockPast(Request $request, $id)
    {
        $folder = $this->ownedFolder($id);


        /*
        |--------------------------------------------------------------------------
        | Client sends 10 clicks
        |--------------------------------------------------------------------------
        */
        $clickCount = (int) $request->input('click_count', 0);


        if ($clickCount < 10) {

            return back()
                ->with(
                    'error',
                    'Complete the 10-click unlock sequence.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Unlock for this folder
        |--------------------------------------------------------------------------
        */
        session()->put(
            'attendance_past_unlocked_' . $folder->id,
            true
        );


        return back()
            ->with(
                'success',
                'Past attendance unlocked.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Lock Past Attendance
    |--------------------------------------------------------------------------
    */
    public function lockPast($id)
    {
        $folder = $this->ownedFolder($id);


        session()->forget(
            'attendance_past_unlocked_' . $folder->id
        );


        return back()
            ->with(
                'success',
                'Past attendance locked again.'
            );
    }
}