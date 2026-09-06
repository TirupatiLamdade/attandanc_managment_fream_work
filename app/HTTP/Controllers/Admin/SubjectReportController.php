<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectFolder;
use App\Models\SubjectAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubjectReportController extends Controller
{
    public function show(Request $request, $id)
    {
        $subject = SubjectFolder::with('students')
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $type = $request->get(
            'type',
            'daily'
        );

        $date = $request->get(
            'date',
            Carbon::today()->toDateString()
        );

        $month = $request->get(
            'month',
            Carbon::today()->format('Y-m')
        );

        $start = $request->get(
            'start',
            Carbon::today()
                ->startOfMonth()
                ->toDateString()
        );

        $end = $request->get(
            'end',
            Carbon::today()
                ->endOfMonth()
                ->toDateString()
        );

        $studentsData = [];

        foreach ($subject->students as $student) {

            $query = SubjectAttendance::where(
                    'subject_folder_id',
                    $subject->id
                )
                ->where(
                    'student_id',
                    $student->id
                );

            if ($type === 'daily') {

                $query->where(
                    'date',
                    $date
                );

            } elseif ($type === 'monthly') {

                $query->whereBetween(
                    'date',
                    [
                        Carbon::parse($month)
                            ->startOfMonth()
                            ->toDateString(),

                        Carbon::parse($month)
                            ->endOfMonth()
                            ->toDateString()
                    ]
                );

            } else {

                $query->whereBetween(
                    'date',
                    [
                        $start,
                        $end
                    ]
                );
            }

            $records = $query->get();

            $present = $records
                ->where('status', 'present')
                ->count();

            $absent = $records
                ->where('status', 'absent')
                ->count();

            $total = $present + $absent;

            $percentage = $total > 0
                ? round(
                    ($present / $total) * 100,
                    2
                )
                : 0;

            $studentsData[] = [
                'student' => $student,
                'present' => $present,
                'absent' => $absent,
                'total' => $total,
                'percentage' => $percentage,
            ];
        }

        return view(
            'admin.subjects.report',
            compact(
                'subject',
                'type',
                'date',
                'month',
                'start',
                'end',
                'studentsData'
            )
        );
    }

    public function total($id)
    {
        $subject = SubjectFolder::with('students')
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $studentsData = [];

        foreach ($subject->students as $student) {

            $records = SubjectAttendance::where(
                    'subject_folder_id',
                    $subject->id
                )
                ->where(
                    'student_id',
                    $student->id
                )
                ->get();

            $present = $records
                ->where('status', 'present')
                ->count();

            $absent = $records
                ->where('status', 'absent')
                ->count();

            $total = $present + $absent;

            $percentage = $total > 0
                ? round(
                    ($present / $total) * 100,
                    2
                )
                : 0;

            $studentsData[] = [
                'student' => $student,
                'present' => $present,
                'absent' => $absent,
                'total' => $total,
                'percentage' => $percentage,
            ];
        }

        return view(
            'admin.subjects.total',
            compact(
                'subject',
                'studentsData'
            )
        );
    }
}