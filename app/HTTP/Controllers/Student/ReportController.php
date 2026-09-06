<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\SubjectFolder;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function folders()
    {
        $folders = Folder::all();
        $subjects = SubjectFolder::all();

        return view('student.folders', compact('folders', 'subjects'));
    }

    public function show(Request $request, $id)
    {
        $folder = Folder::with('students')->findOrFail($id);
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

        foreach ($students as $student) {
            $query = Attendance::where('folder_id', $id)->where('student_id', $student->id);

            if ($type === 'daily') {
                $query->where('date', $date);
            } elseif ($type === 'monthly') {
                $query->whereBetween('date', [Carbon::parse($month)->startOfMonth(), Carbon::parse($month)->endOfMonth()]);
            } else {
                $query->whereBetween('date', [$start, $end]);
            }

            $records = $query->get();
            $present = $records->where('status', 'present')->count();
            $absent = $records->where('status', 'absent')->count();
            $total = $present + $absent;
            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;

            $firstAttendance = Attendance::where('student_id', $student->id)
                ->orderBy('date', 'asc')
                ->first();
            
            $avgQuery = Attendance::where('student_id', $student->id);
            if ($firstAttendance) {
                $avgQuery->where('date', '>=', $firstAttendance->date);
            }
            $allRecords = $avgQuery->get();
            $avgPresent = $allRecords->where('status', 'present')->count();
            $avgTotal = $allRecords->count();
            $avgPercentage = $avgTotal > 0 ? round(($avgPresent / $avgTotal) * 100, 2) : 0;

            $studentsData[] = [
                'student' => $student,
                'present' => $present,
                'absent' => $absent,
                'percentage' => $percentage,
                'avg_percentage' => $avgPercentage,
            ];
        }

        return view('student.report', compact('folder', 'type', 'date', 'month', 'start', 'end', 'studentsData'));
    }

    public function pdf(Request $request, $id)
    {
        $folder = Folder::with('students')->findOrFail($id);
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->toDateString());
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $start = $request->get('start', Carbon::today()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::today()->endOfMonth()->toDateString());

        $studentsData = [];

        foreach ($folder->students as $student) {
            $query = Attendance::where('folder_id', $id)->where('student_id', $student->id);

            if ($type === 'daily') {
                $query->where('date', $date);
            } elseif ($type === 'monthly') {
                $query->whereBetween('date', [Carbon::parse($month)->startOfMonth(), Carbon::parse($month)->endOfMonth()]);
            } else {
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
                'serno' => $student->serno,
                'present' => $present,
                'absent' => $absent,
                'percentage' => $percentage,
            ];
        }

        $pdf = Pdf::loadView('student.pdf', compact('folder', 'type', 'date', 'month', 'start', 'end', 'studentsData'));
        return $pdf->download('my_report_' . $folder->name . '.pdf');
    }
}