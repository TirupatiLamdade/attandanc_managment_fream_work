<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report - {{ $folder->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f5f7; }
        h1 { color: #2E3440; }
    </style>
</head>
<body>
    <h1>Attendance Report - {{ $folder->name }}</h1>
    <p>Type: {{ ucfirst($type) }} | 
    @if($type === 'daily') Date: {{ $date }}
    @elseif($type === 'monthly') Month: {{ $month }}
    @else From: {{ $start }} To: {{ $end }}
    @endif</p>
    
    <table>
        <thead>
            <tr>
                <th>Serno</th>
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
                <td>{{ $data['serno'] }}</td>
                <td>{{ $data['name'] }}</td>
                <td>{{ $data['roll'] }}</td>
                <td>{{ $data['branch'] }}</td>
                <td>{{ $data['present'] }}</td>
                <td>{{ $data['absent'] }}</td>
                <td>{{ $data['percentage'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>