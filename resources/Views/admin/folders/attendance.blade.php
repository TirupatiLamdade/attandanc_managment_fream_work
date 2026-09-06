@extends('layouts.app')
@section('title', 'Mark Attendance - ' . $folder->name)
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.folders.show', $folder->id) }}" class="text-accent hover:underline text-sm">← Back to Folder</a>
    <h1 class="text-3xl font-bold mt-2">Mark Attendance - {{ $folder->name }}</h1>
    <p class="text-secondary mt-1">Date: {{ \Carbon\Carbon::today()->format('d M, Y') }}</p>
</div>

@if(session('success'))
    <div class="bg-success/10 border border-success text-success px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.attendance.submit', $folder->id) }}" class="bg-surface border border-border rounded-xl p-6 glow-accent">
    @csrf
    <table class="w-full mb-6">
        <thead class="bg-bg border-b border-border">
            <tr>
                <th class="text-left p-4 text-secondary font-medium">Serno</th>
                <th class="text-left p-4 text-secondary font-medium">Name</th>
                <th class="text-left p-4 text-secondary font-medium">Roll</th>
                <th class="text-center p-4 text-secondary font-medium">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($folder->students as $student)
            <tr class="border-b border-border">
                <td class="p-4">{{ $student->serno }}</td>
                <td class="p-4">{{ $student->name }}</td>
                <td class="p-4">{{ $student->roll_number }}</td>
                <td class="p-4 text-center">
                    <label class="inline-flex items-center mr-6">
                        <input type="radio" name="student_{{ $student->id }}" value="present" 
                               {{ isset($attendances[$student->id]) && $attendances[$student->id] === 'present' ? 'checked' : '' }}
                               class="text-success focus:ring-success mr-2">
                        <span class="text-success">Present</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="student_{{ $student->id }}" value="absent" 
                               {{ isset($attendances[$student->id]) && $attendances[$student->id] === 'absent' ? 'checked' : '' }}
                               class="text-danger focus:ring-danger mr-2">
                        <span class="text-danger">Absent</span>
                    </label>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit" class="bg-accent hover:bg-accentHover text-white px-8 py-3 rounded-lg glow-accent font-semibold">Submit Attendance</button>
</form>
@endsection