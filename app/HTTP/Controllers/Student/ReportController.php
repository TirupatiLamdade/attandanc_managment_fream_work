<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\SubjectFolder;
use App\Models\Attendance;
use App\Models\SubjectAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function folders()
    {
        $today = Carbon::today()->toDateString();

        // Class Folders with Today's Attendance Status & Student Count
        $folders = Folder::withCount('students')->get()->map(function ($folder) use ($today) {
            $folder->today_attendance_marked = Attendance::where('folder_id', $folder->id)
                ->where('date', $today)
                ->whereIn('status', ['present', 'absent'])
                ->exists();
            return $folder;
        });

        // Subject Folders with Today's Attendance Status & Student Count
        $subjects = SubjectFolder::withCount('students')->get()->map(function ($subject) use ($today) {
            $subject->today_attendance_marked = SubjectAttendance::where('subject_folder_id', $subject->id)
                ->where('date', $today)
                ->whereIn('status', ['present', 'absent'])
                ->exists();
            return $subject;
        });

        return view('student.folders', compact('folders', 'subjects'));
    }

    public function show(Request $request, $id)
    {
        // Determine whether it's a Class Folder or Subject Folder
        $folder = Folder::with('students')->find($id);
        $isSubject = false;

        if (!$folder) {
            $folder = SubjectFolder::with('students')->findOrFail($id);
            $isSubject = true;
        }

        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->toDateString());
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $start = $request->get('start', Carbon::today()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::today()->endOfMonth()->toDateString());
        $search = $request->get('search');

        $students = $folder->students;

        if ($search) {
            $students = $students->filter(function ($s) use ($search) {
                return stripos($s->name, $search) !== false ||
                       stripos($s->roll_number, $search) !== false ||
                       stripos($s->branch, $search) !== false ||
                       stripos($s->phone, $search) !== false;
            });
        }

        $studentsData = [];
        $hasAttendance = false;

        foreach ($students as $student) {
            $query = $isSubject
                ? SubjectAttendance::where('subject_folder_id', $id)->where('student_id', $student->id)
                : Attendance::where('folder_id', $id)->where('student_id', $student->id);

            if ($type === 'daily') {
                $query->where('date', $date);
            } elseif ($type === 'monthly') {
                $query->whereBetween('date', [Carbon::parse($month)->startOfMonth(), Carbon::parse($month)->endOfMonth()]);
            } elseif ($type === 'custom') {
                $query->whereBetween('date', [$start, $end]);
            }
            // If type is 'all', no date filter is applied.

            $records = $query->get();
            if ($records->isNotEmpty()) {
                $hasAttendance = true;
            }

            $present = $records->where('status', 'present')->count();
            $absent = $records->where('status', 'absent')->count();
            $total = $present + $absent;
            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            $dailyStatus = '-';
            if ($type === 'daily') {
                $singleRec = $records->first();
                if ($singleRec) {
                    $dailyStatus = ucfirst($singleRec->status);
                }
            }

            // Overall Average Calculation
            $avgQuery = $isSubject
                ? SubjectAttendance::where('subject_folder_id', $id)->where('student_id', $student->id)
                : Attendance::where('folder_id', $id)->where('student_id', $student->id);

            $allRecords = $avgQuery->get();
            $avgPresent = $allRecords->where('status', 'present')->count();
            $avgTotal = $allRecords->count();
            $avgPercentage = $avgTotal > 0 ? round(($avgPresent / $avgTotal) * 100, 2) : 0;

            $studentsData[] = [
                'student' => $student,
                'name' => $student->name,
                'roll' => $student->roll_number,
                'branch' => $student->branch,
                'phone' => $student->phone,
                'serno' => $student->serno ?? 1,
                'present' => $present,
                'absent' => $absent,
                'total_days' => $total,
                'percentage' => $percentage,
                'avg_percentage' => $avgPercentage,
                'daily_status' => $dailyStatus,
            ];
        }

        return view('student.report', compact('folder', 'type', 'date', 'month', 'start', 'end', 'studentsData', 'hasAttendance'));
    }

    public function pdf(Request $request, $id)
    {
        $folder = Folder::with('students')->find($id);
        $isSubject = false;

        if (!$folder) {
            $folder = SubjectFolder::with('students')->findOrFail($id);
            $isSubject = true;
        }

        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->toDateString());
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $start = $request->get('start', Carbon::today()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::today()->endOfMonth()->toDateString());

        $studentsData = [];

        foreach ($folder->students as $student) {
            $query = $isSubject
                ? SubjectAttendance::where('subject_folder_id', $id)->where('student_id', $student->id)
                : Attendance::where('folder_id', $id)->where('student_id', $student->id);

            if ($type === 'daily') {
                $query->where('date', $date);
            } elseif ($type === 'monthly') {
                $query->whereBetween('date', [Carbon::parse($month)->startOfMonth(), Carbon::parse($month)->endOfMonth()]);
            } elseif ($type === 'custom') {
                $query->whereBetween('date', [$start, $end]);
            }

            $records = $query->get();
            $present = $records->where('status', 'present')->count();
            $absent = $records->where('status', 'absent')->count();
            $total = $present + $absent;
            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            $studentsData[] = [
                'name' => $student->name,
                'roll' => $student->roll_number,
                'branch' => $student->branch,
                'serno' => $student->serno ?? 1,
                'present' => $present,
                'absent' => $absent,
                'percentage' => $percentage,
            ];
        }

        $pdf = Pdf::loadView('student.pdf', compact('folder', 'type', 'date', 'month', 'start', 'end', 'studentsData'));
        return $pdf->download('my_report_' . $folder->name . '.pdf');
    }
}