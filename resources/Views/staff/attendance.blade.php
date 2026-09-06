@extends('layouts.app')
@section('title', 'Mark Attendance - ' . $folder->name)
@section('content')
<div class="mb-6">
    <a href="{{ route('staff.folders') }}" class="text-accent hover:underline text-sm">← Back to Folders</a>
    <h1 class="text-3xl font-bold mt-2">{{ $folder->name }} - Attendance</h1>
    <p class="text-secondary mt-1">Date: {{ \Carbon\Carbon::today()->format('d M, Y') }}</p>
</div>

@if(session('success'))
    <div class="bg-success/10 border border-success text-success px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif

<!-- Quick Stats -->
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-surface border border-border rounded-xl p-4 text-center">
        <div class="text-2xl font-bold text-primary">{{ $folder->students->count() }}</div>
        <div class="text-secondary text-sm">Total Students</div>
    </div>
    <div class="bg-surface border border-success rounded-xl p-4 text-center" id="presentCount">
        <div class="text-2xl font-bold text-success">0</div>
        <div class="text-secondary text-sm">Present</div>
    </div>
    <div class="bg-surface border border-danger rounded-xl p-4 text-center" id="absentCount">
        <div class="text-2xl font-bold text-danger">0</div>
        <div class="text-secondary text-sm">Absent</div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6 flex gap-4">
    <button onclick="markAll('present')" class="bg-success hover:bg-success/90 text-white px-6 py-2 rounded-lg">✅ All Present</button>
    <button onclick="markAll('absent')" class="bg-danger hover:bg-danger/90 text-white px-6 py-2 rounded-lg">❌ All Absent</button>
</div>

<form method="POST" action="{{ route('staff.attendance.submit', $folder->id) }}" class="bg-surface border border-border rounded-xl p-6 glow-accent">
    @csrf
    <input type="hidden" name="is_subject" value="{{ $isSubject ? '1' : '0' }}">
    
    <table class="w-full mb-6">
        <thead class="bg-bg border-b border-border">
            <tr>
                <th class="text-left p-4 text-secondary font-medium">Serno</th>
                <th class="text-left p-4 text-secondary font-medium">Name</th>
                <th class="text-left p-4 text-secondary font-medium">Roll</th>
                <th class="text-center p-4 text-secondary font-medium">Mark Attendance</th>
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
                               onchange="updateCounts()"
                               class="text-success focus:ring-success mr-2 attendance-radio">
                        <span class="text-success">Present</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="student_{{ $student->id }}" value="absent" 
                               {{ isset($attendances[$student->id]) && $attendances[$student->id] === 'absent' ? 'checked' : '' }}
                               onchange="updateCounts()"
                               class="text-danger focus:ring-danger mr-2 attendance-radio">
                        <span class="text-danger">Absent</span>
                    </label>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <button type="submit" class="bg-accent hover:bg-accentHover text-white px-8 py-3 rounded-lg glow-accent font-semibold">Submit Attendance</button>
</form>

<script>
function updateCounts() {
    let present = 0;
    let absent = 0;
    document.querySelectorAll('.attendance-radio').forEach(radio => {
        if (radio.checked) {
            if (radio.value === 'present') present++;
            else if (radio.value === 'absent') absent++;
        }
    });
    document.querySelector('#presentCount .font-bold').textContent = present;
    document.querySelector('#absentCount .font-bold').textContent = absent;
}

function markAll(status) {
    document.querySelectorAll('.attendance-radio').forEach(radio => {
        if (radio.value === status) {
            radio.checked = true;
        }
    });
    updateCounts();
}

// Initialize counts on page load
updateCounts();
</script>
@endsection