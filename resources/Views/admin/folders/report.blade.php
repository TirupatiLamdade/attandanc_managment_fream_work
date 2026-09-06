
@extends('layouts.app')
@section('title', 'Report - ' . $folder->name)
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.folders.show', $folder->id) }}" class="text-accent hover:underline text-sm">← Back to Folder</a>
    <h1 class="text-3xl font-bold mt-2">Attendance Report - {{ $folder->name }}</h1>
</div>

<!-- Filter Tabs -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6 flex gap-4 flex-wrap">
    <a href="{{ route('admin.report.show', ['id' => $folder->id, 'type' => 'daily', 'date' => \Carbon\Carbon::today()->toDateString()]) }}" 
       class="px-4 py-2 rounded-lg {{ $type === 'daily' ? 'bg-accent text-white' : 'bg-bg text-secondary hover:bg-bg/70' }}">Daily</a>
    <a href="{{ route('admin.report.show', ['id' => $folder->id, 'type' => 'monthly', 'month' => \Carbon\Carbon::today()->format('Y-m')]) }}" 
       class="px-4 py-2 rounded-lg {{ $type === 'monthly' ? 'bg-accent text-white' : 'bg-bg text-secondary hover:bg-bg/70' }}">Monthly</a>
    <a href="{{ route('admin.report.show', ['id' => $folder->id, 'type' => 'custom', 'start' => \Carbon\Carbon::today()->startOfMonth()->toDateString(), 'end' => \Carbon\Carbon::today()->endOfMonth()->toDateString()]) }}" 
       class="px-4 py-2 rounded-lg {{ $type === 'custom' ? 'bg-accent text-white' : 'bg-bg text-secondary hover:bg-bg/70' }}">Custom</a>
    
    <a href="{{ route('admin.report.pdf', ['id' => $folder->id, 'type' => $type, 'date' => $date, 'month' => $month, 'start' => $start, 'end' => $end]) }}" 
       class="ml-auto px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger/90">📄 Download PDF</a>
</div>

<!-- Report Table -->
<div class="bg-surface border border-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-bg border-b border-border">
            <tr>
                <th class="text-left p-4 text-secondary font-medium">Serno</th>
                <th class="text-left p-4 text-secondary font-medium">Name</th>
                <th class="text-left p-4 text-secondary font-medium">Roll</th>
                <th class="text-left p-4 text-secondary font-medium">Branch</th>
                <th class="text-center p-4 text-secondary font-medium">Present</th>
                <th class="text-center p-4 text-secondary font-medium">Absent</th>
                <th class="text-center p-4 text-secondary font-medium">Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($studentsData as $data)
            <tr class="border-b border-border">
                <td class="p-4">{{ $data['serno'] }}</td>
                <td class="p-4">{{ $data['name'] }}</td>
                <td class="p-4">{{ $data['roll'] }}</td>
                <td class="p-4">{{ $data['branch'] }}</td>
                <td class="p-4 text-center text-success">{{ $data['present'] }}</td>
                <td class="p-4 text-center text-danger">{{ $data['absent'] }}</td>
                <td class="p-4 text-center font-semibold">{{ $data['percentage'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection