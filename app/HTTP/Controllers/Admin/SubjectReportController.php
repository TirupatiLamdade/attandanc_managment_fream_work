<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectFolder;
use App\Models\SubjectAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SubjectReportController extends Controller
{
    private function ownedSubject($id)
    {
        return SubjectFolder::where('created_by', Auth::id())
            ->with([
                'students' => function ($query) {
                    $query
                        ->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
                        ->orderBy('roll_number')
                        ->orderBy('id');
                }
            ])
            ->findOrFail($id);
    }

    public function show(Request $request, $id)
    {
        $subject = $this->ownedSubject($id);

        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->toDateString());
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $start = $request->get('start', Carbon::today()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::today()->endOfMonth()->toDateString());

        $studentsData = [];
        $hasAttendance = false;
        $serno = 1;

        foreach ($subject->students as $student) {
            $query = SubjectAttendance::where('subject_folder_id', $subject->id)
                ->where('student_id', $student->id);

            if ($type === 'daily') {
                $query->where('date', $date);
                $record = $query->first();
                $dailyStatus = '-';

                if ($record) {
                    $hasAttendance = true;
                    $dailyStatus = ucfirst($record->status);
                }

                $studentsData[] = [
                    'serno' => $serno++,
                    'name' => $student->name,
                    'roll' => $student->roll_number,
                    'branch' => $student->branch,
                    'phone' => $student->phone,
                    'daily_status' => $dailyStatus,
                ];
            } else {
                if ($type === 'monthly') {
                    $query->whereBetween('date', [
                        Carbon::parse($month)->startOfMonth()->toDateString(),
                        Carbon::parse($month)->endOfMonth()->toDateString()
                    ]);
                } elseif ($type === 'custom') {
                    $query->whereBetween('date', [$start, $end]);
                }

                $records = $query->get();
                if ($records->count() > 0) {
                    $hasAttendance = true;
                }

                $present = $records->where('status', 'present')->count();
                $absent = $records->where('status', 'absent')->count();
                $totalDays = $present + $absent;
                $percentage = $totalDays > 0 ? round(($present / $totalDays) * 100, 2) : 0;

                $studentsData[] = [
                    'serno' => $serno++,
                    'name' => $student->name,
                    'roll' => $student->roll_number,
                    'branch' => $student->branch,
                    'phone' => $student->phone,
                    'total_days' => $totalDays,
                    'present' => $present,
                    'absent' => $absent,
                    'percentage' => $percentage,
                ];
            }
        }

        if ($type === 'all') {
            $hasAttendance = SubjectAttendance::where('subject_folder_id', $subject->id)->exists();
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
                'studentsData',
                'hasAttendance'
            )
        );
    }

    /**
     * Total attendance summary with Date-wise filter (Matching normal folder layout).
     */
    public function total(Request $request, $id)
    {
        $subject = $this->ownedSubject($id);

        $today = Carbon::today()->toDateString();
        $selectedDate = $request->get('date', $today);

        $totalStudents = $subject->students->count();

        $attendances = SubjectAttendance::where('subject_folder_id', $subject->id)
            ->where('date', $selectedDate)
            ->pluck('status', 'student_id');

        $present = 0;
        $absent = 0;
        $marked = 0;

        $studentsData = [];
        $serno = 1;

        foreach ($subject->students as $student) {
            $status = $attendances[$student->id] ?? null;

            if ($status === 'present') {
                $present++;
                $marked++;
            } elseif ($status === 'absent') {
                $absent++;
                $marked++;
            }

            $studentsData[] = [
                'serno' => $serno++,
                'name' => $student->name,
                'roll' => $student->roll_number,
                'branch' => $student->branch,
                'phone' => $student->phone,
                'daily_status' => $status ? ucfirst($status) : '- Not Marked -',
            ];
        }

        $notMarked = max(0, $totalStudents - $marked);
        $percentage = $marked > 0 ? round(($present / $marked) * 100, 2) : 0;

        return view('admin.subjects.total', compact(
            'subject',
            'totalStudents',
            'present',
            'absent',
            'marked',
            'notMarked',
            'percentage',
            'selectedDate',
            'today',
            'studentsData'
        ));
    }

    public function pdf(Request $request, $id)
    {
        $subject = $this->ownedSubject($id);

        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->toDateString());
        $month = $request->get('month', Carbon::today()->format('Y-m'));
        $start = $request->get('start', Carbon::today()->startOfMonth()->toDateString());
        $end = $request->get('end', Carbon::today()->endOfMonth()->toDateString());

        $studentsData = [];
        $serno = 1;

        foreach ($subject->students as $student) {
            $query = SubjectAttendance::where('subject_folder_id', $subject->id)
                ->where('student_id', $student->id);

            if ($type === 'daily') {
                $query->where('date', $date);
                $record = $query->first();
                $dailyStatus = $record ? ucfirst($record->status) : '-';

                $studentsData[] = [
                    'serno' => $serno++,
                    'name' => $student->name,
                    'roll' => $student->roll_number,
                    'branch' => $student->branch,
                    'phone' => $student->phone,
                    'daily_status' => $dailyStatus,
                ];
            } else {
                if ($type === 'monthly') {
                    $query->whereBetween('date', [
                        Carbon::parse($month)->startOfMonth()->toDateString(),
                        Carbon::parse($month)->endOfMonth()->toDateString()
                    ]);
                } elseif ($type === 'custom') {
                    $query->whereBetween('date', [$start, $end]);
                }

                $records = $query->get();
                $present = $records->where('status', 'present')->count();
                $absent = $records->where('status', 'absent')->count();
                $totalDays = $present + $absent;
                $percentage = $totalDays > 0 ? round(($present / $totalDays) * 100, 2) : 0;

                $studentsData[] = [
                    'serno' => $serno++,
                    'name' => $student->name,
                    'roll' => $student->roll_number,
                    'branch' => $student->branch,
                    'phone' => $student->phone,
                    'total_days' => $totalDays,
                    'present' => $present,
                    'absent' => $absent,
                    'percentage' => $percentage,
                ];
            }
        }

        $pdf = Pdf::loadView('admin.subjects.pdf', compact(
            'subject', 'type', 'date', 'month', 'start', 'end', 'studentsData'
        ));

        return $pdf->download('Subject-Attendance-Report-' . $subject->name . '.pdf');
    }
}