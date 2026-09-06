<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function show(Request $request, $id)
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

        return view('admin.folders.report', compact('folder', 'type', 'date', 'month', 'start', 'end', 'studentsData'));
    }

    public function total($id)
    {
        $folder = Folder::with('students')->findOrFail($id);
        $today = Carbon::today()->toDateString();

        $attendances = Attendance::where('folder_id', $id)
            ->where('date', $today)
            ->get();

        $total = $folder->students->count();
        $present = $attendances->where('status', 'present')->count();
        $absent = $attendances->where('status', 'absent')->count();

        return view('admin.folders.total', compact('folder', 'total', 'present', 'absent'));
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

        $pdf = Pdf::loadView('admin.folders.pdf', compact('folder', 'type', 'date', 'month', 'start', 'end', 'studentsData'));
        return $pdf->download('report_' . $folder->name . '_' . $type . '.pdf');
    }
}