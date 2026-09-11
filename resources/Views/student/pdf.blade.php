<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Attendance Report - {{ $folder->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f4f5f7; color: #2E3440; }
        h1 { color: #2E3440; font-size: 22px; margin-bottom: 5px; }
        p { font-size: 13px; color: #666; }
    </style>
</head>
<body>
    <h1>My Attendance Report - {{ $folder->name }}</h1>
    <p>Type: {{ ucfirst($type) }} | 
    @if($type === 'daily') Date: {{ $date }}
    @elseif($type === 'monthly') Month: {{ $month }}
    @else From: {{ $start }} To: {{ $end }}
    @endif</p>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Branch</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentsData as $data)
            <tr>
                <td>{{ $data['student']->name ?? $data['name'] ?? '' }}</td>
                <td>{{ $data['student']->roll_number ?? $data['roll'] ?? '' }}</td>
                <td>{{ $data['student']->branch ?? $data['branch'] ?? '' }}</td>
                <td>{{ $data['present'] }}</td>
                <td>{{ $data['absent'] }}</td>
                <td>{{ $data['percentage'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>