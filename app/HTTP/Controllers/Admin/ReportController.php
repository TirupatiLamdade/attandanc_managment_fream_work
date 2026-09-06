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
        return Folder::where(
                'created_by',
                Auth::id()
            )
            ->with([
                'students' => function ($query) {
                    $query
                        ->orderByRaw(
                            'CAST(roll_number AS UNSIGNED) ASC'
                        )
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
         * Never report future dates.
         */
        if ($rangeStart->gt($today)) {
            $rangeStart = $today->copy();
        }

        if ($rangeEnd->gt($today)) {
            $rangeEnd = $today->copy()->endOfDay();
        }

        $studentsData = [];

        foreach ($folder->students as $student) {

            $studentAdded =
                $student->created_at
                    ? Carbon::parse(
                        $student->created_at
                    )->startOfDay()
                    : $rangeStart->copy();

            /*
             * Applicable period starts at:
             * max(report start, student added date)
             */
            $applicableStart =
                $rangeStart->copy()->max(
                    $studentAdded
                );

            $applicableEnd =
                $rangeEnd->copy()->min(
                    $today->copy()->endOfDay()
                );

            $present = 0;
            $absent = 0;
            $notMarked = 0;
            $totalDays = 0;
            $dailyStatus = 'Not Attendance Marked';

            /*
             * Student did not exist during selected period.
             */
            if ($applicableStart->gt($applicableEnd)) {

                $dailyStatus = 'Not Applicable';

            } else {

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
                            $applicableStart->toDateString(),
                            $applicableEnd->toDateString(),
                        ]
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

                /*
                 * Daily status.
                 */
                if ($type === 'daily') {

                    $record =
                        $records->first();

                    if ($record) {

                        $dailyStatus =
                            ucfirst(
                                $record->status
                            );

                    } else {

                        $dailyStatus =
                            'Not Attendance Marked';
                    }
                }
            }

            $percentage =
                $totalDays > 0
                    ? round(
                        ($present / $totalDays) * 100,
                        2
                    )
                    : 0;

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
                    $studentAdded->format(
                        'Y-m-d'
                    ),

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
                    $dailyStatus,
            ];
        }

        return [
            'studentsData' => $studentsData,
            'rangeStart' => $rangeStart,
            'rangeEnd' => $rangeEnd,
        ];
    }

    /**
     * Show report.
     */
    public function show(Request $request, $id)
    {
        $folder = $this->ownedFolder($id);

        $type = $request->get(
            'type',
            'daily'
        );

        if (!in_array(
            $type,
            ['daily', 'monthly', 'custom'],
            true
        )) {
            $type = 'daily';
        }

        $date =
            $request->get(
                'date',
                Carbon::today()->format('Y-m-d')
            );

        $month =
            $request->get(
                'month',
                Carbon::today()->format('Y-m')
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
                    'folder' => $folder,
                    'type' => $type,
                    'date' => $date,
                    'month' => $month,
                    'start' => $start,
                    'end' => $end,
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
        $folder = $this->ownedFolder($id);

        $today = Carbon::today();

        $total =
            $folder->students->filter(
                function ($student) use ($today) {

                    if (!$student->created_at) {
                        return true;
                    }

                    return Carbon::parse(
                        $student->created_at
                    )->startOfDay()->lte($today);
                }
            )->count();

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
                ->where('status', 'present')
                ->count();

        $absent =
            $attendances
                ->where('status', 'absent')
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
     * Download PDF.
     */
    public function pdf(Request $request, $id)
    {
        $folder = $this->ownedFolder($id);

        $type =
            $request->get(
                'type',
                'daily'
            );

        $date =
            $request->get(
                'date',
                Carbon::today()->format('Y-m-d')
            );

        $month =
            $request->get(
                'month',
                Carbon::today()->format('Y-m')
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

        $data =
            $this->buildReportData(
                $folder,
                $type,
                $date,
                $month,
                $start,
                $end
            );

        $pdf = Pdf::loadView(
            'admin.folders.pdf',
            array_merge(
                [
                    'folder' => $folder,
                    'type' => $type,
                    'date' => $date,
                    'month' => $month,
                    'start' => $start,
                    'end' => $end,
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