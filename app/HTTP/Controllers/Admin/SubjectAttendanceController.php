<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectFolder;
use App\Models\SubjectAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubjectAttendanceController extends Controller
{
    /**
     * Show subject attendance page
     */
    public function show($id)
    {
        $subject = SubjectFolder::with('students')
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $today = Carbon::today()->toDateString();

        $attendances = SubjectAttendance::where(
                'subject_folder_id',
                $subject->id
            )
            ->where('date', $today)
            ->pluck('status', 'student_id');

        return view(
            'admin.subjects.attendance',
            compact(
                'subject',
                'attendances',
                'today'
            )
        );
    }

    /**
     * Save subject attendance
     */
    public function submit(Request $request, $id)
    {
        $subject = SubjectFolder::with('students')
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $today = Carbon::today()->toDateString();

        foreach ($subject->students as $student) {

            $status = $request->input(
                'student_' . $student->id
            );

            if ($status) {

                SubjectAttendance::updateOrCreate(
                    [
                        'subject_folder_id' => $subject->id,
                        'student_id' => $student->id,
                        'date' => $today,
                    ],
                    [
                        'status' => $status,
                        'marked_by' => Auth::id(),
                        'marked_at' => now(),
                    ]
                );
            }
        }

        return redirect()
            ->route(
                'admin.subjects.show',
                $subject->id
            )
            ->with(
                'success',
                'Subject attendance marked successfully.'
            );
    }
}