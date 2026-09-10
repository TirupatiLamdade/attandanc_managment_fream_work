<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Only current admin's folder.
     */
    private function ownedFolder($id)
    {
        return Folder::where('created_by', Auth::id())
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

    /**
     * Build report data.
     */
    private function buildReportData(
        Folder $folder,
        string $type,
        string $date,
        string $month,
        string $start,
        string $end
    ) {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */

        if ($type === 'daily') {

            $rangeStart = Carbon::createFromFormat(
                'Y-m-d',
                $date
            )->startOfDay();

            $rangeEnd = $rangeStart->copy();

        } elseif ($type === 'monthly') {

            $rangeStart = Carbon::createFromFormat(
                'Y-m',
                $month
            )->startOfMonth();

            $rangeEnd = Carbon::createFromFormat(
                'Y-m',
                $month
            )->endOfMonth();

        } elseif ($type === 'all') {

            // For 'all', range starts from the earliest student creation or default past date, up to today
            $earliestStudent = $folder->students->min('created_at');
            $rangeStart = $earliestStudent ? Carbon::parse($earliestStudent)->startOfDay() : $today->copy()->subYear();
            $rangeEnd = $today->copy()->endOfDay();

        } else {

            $rangeStart = Carbon::createFromFormat(
                'Y-m-d',
                $start
            )->startOfDay();

            $rangeEnd = Carbon::createFromFormat(
                'Y-m-d',
                $end
            )->endOfDay();

            if ($rangeStart->gt($rangeEnd)) {
                throw new \InvalidArgumentException(
                    'From date cannot be after To date.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FUTURE DATE PROTECTION
        |--------------------------------------------------------------------------
        */

        if ($rangeStart->gt($today)) {
            $rangeStart = $today->copy()->startOfDay();
        }

        if ($rangeEnd->gt($today)) {
            $rangeEnd = $today->copy()->endOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | DAILY REPORT
        |--------------------------------------------------------------------------
        */

        if ($type === 'daily') {

            $dailyAttendances = Attendance::where(
                'folder_id',
                $folder->id
            )
                ->whereDate(
                    'date',
                    $rangeStart->toDateString()
                )
                ->whereIn(
                    'status',
                    ['present', 'absent']
                )
                ->with('student')
                ->get();

            $studentsData = [];

            foreach ($dailyAttendances as $attendance) {

                if (!$attendance->student) {
                    continue;
                }

                $student = $attendance->student;

                if (
                    $student->created_at &&
                    Carbon::parse(
                        $student->created_at
                    )->startOfDay()->gt($rangeStart)
                ) {
                    continue;
                }

                $studentsData[] = [
                    'serno' => 0,

                    'name' =>
                        $student->name,

                    'roll' =>
                        $student->roll_number,

                    'branch' =>
                        $student->branch,

                    'phone' =>
                        $student->phone,

                    'added_date' =>
                        $student->created_at
                            ? Carbon::parse(
                                $student->created_at
                            )->format('Y-m-d')
                            : null,

                    'present' =>
                        $attendance->status === 'present'
                            ? 1
                            : 0,

                    'absent' =>
                        $attendance->status === 'absent'
                            ? 1
                            : 0,

                    'not_marked' => 0,

                    'total_days' => 1,

                    'percentage' =>
                        $attendance->status === 'present'
                            ? 100
                            : 0,

                    'daily_status' =>
                        ucfirst(
                            strtolower(
                                $attendance->status
                            )
                        ),
                ];
            }

            usort(
                $studentsData,
                function ($a, $b) {

                    $rollA = intval(
                        preg_replace(
                            '/[^0-9]/',
                            '',
                            $a['roll']
                        )
                    );

                    $rollB = intval(
                        preg_replace(
                            '/[^0-9]/',
                            '',
                            $b['roll']
                        )
                    );

                    if ($rollA === $rollB) {
                        return strnatcasecmp(
                            $a['roll'],
                            $b['roll']
                        );
                    }

                    return $rollA <=> $rollB;
                }
            );

            foreach (
                $studentsData
                as $index => &$studentData
            ) {
                $studentData['serno'] =
                    $index + 1;
            }

            unset($studentData);

            return [
                'studentsData' => $studentsData,
                'rangeStart' => $rangeStart,
                'rangeEnd' => $rangeEnd,
                'hasAttendance' => count($studentsData) > 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY / CUSTOM / ALL REPORT
        |--------------------------------------------------------------------------
        */

        $studentsData = [];

        $periodAttendanceExists =
            Attendance::where(
                'folder_id',
                $folder->id
            )
                ->whereBetween(
                    'date',
                    [
                        $rangeStart->toDateString(),
                        $rangeEnd->toDateString(),
                    ]
                )
                ->whereIn(
                    'status',
                    ['present', 'absent']
                )
                ->exists();

        foreach ($folder->students as $student) {

            $studentAdded =
                $student->created_at
                    ? Carbon::parse(
                        $student->created_at
                    )->startOfDay()
                    : $rangeStart->copy();

            $applicableStart =
                $rangeStart
                    ->copy()
                    ->max($studentAdded);

            $applicableEnd =
                $rangeEnd
                    ->copy()
                    ->min(
                        $today
                            ->copy()
                            ->endOfDay()
                    );

            if (
                $applicableStart
                    ->gt($applicableEnd)
            ) {
                continue;
            }

            $totalDays =
                $applicableStart
                    ->copy()
                    ->startOfDay()
                    ->diffInDays(
                        $applicableEnd
                            ->copy()
                            ->startOfDay()
                    ) + 1;

            $records = Attendance::where(
                'folder_id',
                $folder->id
            )
                ->where(
                    'student_id',
                    $student->id
                )
                ->whereBetween(
                    'date',
                    [
                        $applicableStart
                            ->toDateString(),

                        $applicableEnd
                            ->toDateString(),
                    ]
                )
                ->whereIn(
                    'status',
                    ['present', 'absent']
                )
                ->get();

            $present =
                $records
                    ->where(
                        'status',
                        'present'
                    )
                    ->count();

            $absent =
                $records
                    ->where(
                        'status',
                        'absent'
                    )
                    ->count();

            $notMarked =
                max(
                    0,
                    $totalDays -
                    $present -
                    $absent
                );

            $percentage =
                $totalDays > 0
                    ? round(
                        ($present / $totalDays) * 100,
                        2
                    )
                    : 0;

            if ($type !== 'all' && $records->count() === 0) {
                continue;
            }

            $studentsData[] = [
                'serno' =>
                    count($studentsData) + 1,

                'name' =>
                    $student->name,

                'roll' =>
                    $student->roll_number,

                'branch' =>
                    $student->branch,

                'phone' =>
                    $student->phone,

                'added_date' =>
                    $studentAdded
                        ->format('Y-m-d'),

                'present' =>
                    $present,

                'absent' =>
                    $absent,

                'not_marked' =>
                    $notMarked,

                'total_days' =>
                    $totalDays,

                'percentage' =>
                    $percentage,

                'daily_status' =>
                    null,
            ];
        }

        return [
            'studentsData' =>
                $studentsData,

            'rangeStart' =>
                $rangeStart,

            'rangeEnd' =>
                $rangeEnd,

            'hasAttendance' =>
                $type === 'all' ? count($studentsData) > 0 : ($periodAttendanceExists && count($studentsData) > 0),
        ];
    }

    /**
     * Show report.
     */
    public function show(
        Request $request,
        $id
    ) {
        $folder =
            $this->ownedFolder($id);

        $type =
            $request->get(
                'type',
                'daily'
            );

        if (
            !in_array(
                $type,
                [
                    'daily',
                    'monthly',
                    'custom',
                    'all'
                ],
                true
            )
        ) {
            $type = 'daily';
        }

        $date =
            $request->get(
                'date',
                Carbon::today()
                    ->format('Y-m-d')
            );

        $month =
            $request->get(
                'month',
                Carbon::today()
                    ->format('Y-m')
            );

        $start =
            $request->get(
                'start',
                Carbon::today()
                    ->startOfMonth()
                    ->format('Y-m-d')
            );

        $end =
            $request->get(
                'end',
                Carbon::today()
                    ->endOfMonth()
                    ->format('Y-m-d')
            );

        try {

            $data =
                $this->buildReportData(
                    $folder,
                    $type,
                    $date,
                    $month,
                    $start,
                    $end
                );

        } catch (\Throwable $e) {

            return back()
                ->with(
                    'error',
                    'Invalid report date selection.'
                );
        }

        return view(
            'admin.folders.report',
            array_merge(
                [
                    'folder' =>
                        $folder,

                    'type' =>
                        $type,

                    'date' =>
                        $date,

                    'month' =>
                        $month,

                    'start' =>
                        $start,

                    'end' =>
                        $end,
                ],
                $data
            )
        );
    }

    /**
     * Today's total.
     */
    public function total($id)
    {
        $folder =
            $this->ownedFolder($id);

        $today =
            Carbon::today();

        $total =
            $folder->students
                ->filter(
                    function ($student)
                    use ($today) {

                        if (
                            !$student->created_at
                        ) {
                            return true;
                        }

                        return Carbon::parse(
                            $student->created_at
                        )
                            ->startOfDay()
                            ->lte($today);
                    }
                )
                ->count();

        $attendances =
            Attendance::where(
                'folder_id',
                $folder->id
            )
                ->whereDate(
                    'date',
                    $today->toDateString()
                )
                ->get();

        $present =
            $attendances
                ->where(
                    'status',
                    'present'
                )
                ->count();

        $absent =
            $attendances
                ->where(
                    'status',
                    'absent'
                )
                ->count();

        $marked =
            $present + $absent;

        $notMarked =
            max(
                0,
                $total - $marked
            );

        $percentage =
            $total > 0
                ? round(
                    ($present / $total) * 100,
                    2
                )
                : 0;

        return view(
            'admin.folders.total',
            compact(
                'folder',
                'total',
                'present',
                'absent',
                'marked',
                'notMarked',
                'percentage'
            )
        );
    }

    /**
     * Download PDF ONLY if attendance exists.
     */
    public function pdf(
        Request $request,
        $id
    ) {
        $folder =
            $this->ownedFolder($id);

        $type =
            $request->get(
                'type',
                'daily'
            );

        if (
            !in_array(
                $type,
                [
                    'daily',
                    'monthly',
                    'custom',
                    'all'
                ],
                true
            )
        ) {
            return back()->with(
                'error',
                'Invalid report type.'
            );
        }

        $date =
            $request->get(
                'date',
                Carbon::today()
                    ->format('Y-m-d')
            );

        $month =
            $request->get(
                'month',
                Carbon::today()
                    ->format('Y-m')
            );

        $start =
            $request->get(
                'start',
                Carbon::today()
                    ->startOfMonth()
                    ->format('Y-m-d')
            );

        $end =
            $request->get(
                'end',
                Carbon::today()
                    ->endOfMonth()
                    ->format('Y-m-d')
            );

        try {

            $data =
                $this->buildReportData(
                    $folder,
                    $type,
                    $date,
                    $month,
                    $start,
                    $end
                );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                'Invalid report date selection.'
            );
        }

        if (
            !$data['hasAttendance'] ||
            count($data['studentsData']) === 0
        ) {
            return back()->with(
                'error',
                'No attendance records found for this period. PDF is not available.'
            );
        }

        $pdf =
            Pdf::loadView(
                'admin.folders.pdf',
                array_merge(
                    [
                        'folder' =>
                            $folder,

                        'type' =>
                            $type,

                        'date' =>
                            $date,

                        'month' =>
                            $month,

                        'start' =>
                            $start,

                        'end' =>
                            $end,
                    ],
                    $data
                )
            );

        return $pdf->download(
            'attendance_report_' .
            $folder->name .
            '_' .
            $type .
            '.pdf'
        );
    }
}