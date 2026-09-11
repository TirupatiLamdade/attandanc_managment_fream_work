<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject Attendance Report - {{ $subject->name }}</title>
    <style>
        @page { margin: 20px 25px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; margin: 0; padding: 0; background-color: #ffffff; }
        .college-header { width: 100%; border-bottom: 2px solid #0f172a; padding-bottom: 8px; margin-bottom: 12px; text-align: center; }
        .college-logo { width: 48px; height: 48px; object-fit: cover; border-radius: 50%; margin-bottom: 4px; border: 1px solid #cbd5e1; }
        .college-title { font-size: 15px; font-weight: bold; color: #0f172a; margin: 0; text-transform: uppercase; }
        .college-subtitle { font-size: 10px; font-weight: bold; color: #f97316; margin: 2px 0 0 0; text-transform: uppercase; }
        .report-title-box { text-align: center; margin-bottom: 12px; }
        .report-title-box h1 { margin: 0; font-size: 18px; color: #0f172a; font-weight: bold; }
        .folder-highlight { font-size: 12px; color: #475569; margin-top: 3px; font-weight: bold; }
        .report-info { width: 100%; margin-bottom: 12px; }
        .report-info table { width: 100%; border-collapse: collapse; }
        .report-info td { border: 1px solid #cbd5e1; padding: 6px 8px; font-size: 10px; }
        .label { font-weight: bold; width: 28%; background: #f1f5f9; color: #334155; }
        .summary { width: 100%; margin-bottom: 15px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { border: 1px solid #cbd5e1; padding: 6px; text-align: center; }
        .summary-title { font-weight: bold; background: #f8fafc; color: #475569; font-size: 9px; text-transform: uppercase; }
        .summary-value { font-size: 12px; font-weight: bold; color: #0f172a; }
        .attendance-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .attendance-table th { background: #0f172a; color: #ffffff; border: 1px solid #0f172a; padding: 7px 5px; text-align: center; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .attendance-table td { border: 1px solid #cbd5e1; padding: 6px 5px; vertical-align: middle; font-size: 10px; }
        .center { text-align: center; }
        .present { color: #15803d; font-weight: bold; }
        .absent { color: #b91c1c; font-weight: bold; }
        .no-attendance { border: 1px solid #cbd5e1; padding: 25px; text-align: center; margin-top: 25px; font-size: 13px; font-weight: bold; color: #b91c1c; background: #fef2f2; }
        .footer { margin-top: 25px; border-top: 1px solid #cbd5e1; padding-top: 6px; text-align: center; font-size: 8px; color: #64748b; }
    </style>
</head>
<body>

@php
    $pdfType = $type ?? request('type', 'daily');
    $pdfDate = $date ?? request('date');
    $pdfMonth = $month ?? request('month');
    $pdfStart = $start ?? request('start');
    $pdfEnd = $end ?? request('end');
    $pdfStudents = $studentsData ?? [];
    $pdfHasAttendance = count($pdfStudents) > 0;

    $logoFile = public_path('images/logo.png');
    $logoBase64 = '';
    if (file_exists($logoFile)) {
        $logoData = file_get_contents($logoFile);
        $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
    }
@endphp

<div class="college-header">
    @if($logoBase64)
        <img src="{{ $logoBase64 }}" class="college-logo" alt="Logo">
    @endif
    <h2 class="college-title">Matoshri Pratishthan's</h2>
    <p class="college-subtitle">Vishwabharti Polytechnic Institute</p>
</div>

<div class="report-title-box">
    <h1>Subject Attendance Report</h1>
    <div class="folder-highlight">Subject: {{ $subject->name }}</div>
</div>

<div class="report-info">
    <table>
        <tr>
            <td class="label">Report Type</td>
            <td>
                @if($pdfType === 'daily') Daily Report
                @elseif($pdfType === 'monthly') Monthly Report
                @else Custom Range Report
                @endif
            </td>
        </tr>
        @if($pdfType === 'daily')
            <tr><td class="label">Attendance Date</td><td>{{ $pdfDate ?? '-' }}</td></tr>
        @elseif($pdfType === 'monthly')
            <tr><td class="label">Selected Month</td><td>{{ $pdfMonth ?? '-' }}</td></tr>
        @elseif($pdfType === 'custom')
            <tr><td class="label">From Date</td><td>{{ $pdfStart ?? '-' }}</td></tr>
            <tr><td class="label">To Date</td><td>{{ $pdfEnd ?? '-' }}</td></tr>
        @endif
    </table>
</div>

@if(!$pdfHasAttendance)
    <div class="no-attendance">
        ⚠️ No attendance records found for this period.<br>
        <span style="font-size: 9px; font-weight: normal; color: #475569;">PDF generation is unavailable because no matching logs exist.</span>
    </div>
@else
    @if($pdfType === 'daily')
        <div class="summary">
            <table>
                <tr>
                    <td class="summary-title">Total Marked</td>
                    <td class="summary-title">Present</td>
                    <td class="summary-title">Absent</td>
                </tr>
                <tr>
                    <td class="summary-value">{{ count($pdfStudents) }}</td>
                    <td class="summary-value" style="color: #15803d;">{{ collect($pdfStudents)->where('daily_status', 'Present')->count() }}</td>
                    <td class="summary-value" style="color: #b91c1c;">{{ collect($pdfStudents)->where('daily_status', 'Absent')->count() }}</td>
                </tr>
            </table>
        </div>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th style="width: 7%;">S.No</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 15%;">Roll Number</th>
                    <th style="width: 18%;">Branch</th>
                    <th style="width: 20%;">Mobile Number</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pdfStudents as $data)
                    <tr>
                        <td class="center">{{ $data['serno'] }}</td>
                        <td><strong>{{ $data['name'] }}</strong></td>
                        <td class="center">{{ $data['roll'] }}</td>
                        <td>{{ $data['branch'] }}</td>
                        <td class="center">{{ $data['phone'] ?: '-' }}</td>
                        <td class="center">
                            @if(strtolower($data['daily_status'] ?? '') === 'present')
                                <span class="present">Present</span>
                            @elseif(strtolower($data['daily_status'] ?? '') === 'absent')
                                <span class="absent">Absent</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @php
            $totalStudents = count($pdfStudents);
            $totalPresent = collect($pdfStudents)->sum('present');
            $totalAbsent = collect($pdfStudents)->sum('absent');
            $averagePercentage = $totalStudents > 0 ? round(collect($pdfStudents)->avg('percentage'), 2) : 0;
        @endphp

        <div class="summary">
            <table>
                <tr>
                    <td class="summary-title">Students</td>
                    <td class="summary-title">Present</td>
                    <td class="summary-title">Absent</td>
                    <td class="summary-title">Avg. Percentage</td>
                </tr>
                <tr>
                    <td class="summary-value">{{ $totalStudents }}</td>
                    <td class="summary-value" style="color: #15803d;">{{ $totalPresent }}</td>
                    <td class="summary-value" style="color: #b91c1c;">{{ $totalAbsent }}</td>
                    <td class="summary-value" style="color: #2563eb;">{{ $averagePercentage }}%</td>
                </tr>
            </table>
        </div>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th style="width: 6%;">S.No</th>
                    <th style="width: 22%;">Name</th>
                    <th style="width: 12%;">Roll No</th>
                    <th style="width: 15%;">Branch</th>
                    <th style="width: 17%;">Mobile Number</th>
                    <th style="width: 10%;">Total Days</th>
                    <th style="width: 9%;">Present</th>
                    <th style="width: 9%;">Absent</th>
                    <th style="width: 10%;">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pdfStudents as $data)
                    <tr>
                        <td class="center">{{ $data['serno'] }}</td>
                        <td><strong>{{ $data['name'] }}</strong></td>
                        <td class="center">{{ $data['roll'] }}</td>
                        <td>{{ $data['branch'] }}</td>
                        <td class="center">{{ $data['phone'] ?: '-' }}</td>
                        <td class="center">{{ $data['total_days'] }}</td>
                        <td class="center present">{{ $data['present'] }}</td>
                        <td class="center absent">{{ $data['absent'] }}</td>
                        <td class="center"><strong>{{ $data['percentage'] }}%</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endif

<div class="footer">
    Matoshri Pratishthan's Vishwabharti Polytechnic Institute &bull; Generated by Attendance Management System (AMS 2026)
</div>

</body>
</html>