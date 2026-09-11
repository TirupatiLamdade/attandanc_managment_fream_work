@extends('layouts.app')

@section('title', 'Mark Attendance - ' . $folder->name)

@section('content')

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-transform duration-200" id="zoomable-container">

    {{-- ===================================================== --}}
    {{-- FLOATING TOP NOTIFICATION MESSAGE --}}
    {{-- ===================================================== --}}
    @if(session('success'))
        <div
            id="flashAlert"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-[#10B981]/95 border border-[#10B981] text-white px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer backdrop-blur-md"
        >
            <span class="w-3 h-3 rounded-full bg-white animate-pulse shrink-0"></span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div
            id="flashAlert"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-[#EF4444]/95 border border-[#EF4444] text-white px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer backdrop-blur-md"
        >
            <span class="w-3 h-3 rounded-full bg-white animate-pulse shrink-0"></span>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ===================================================== --}}
    {{-- HEADER WITH TITLE, BACK BUTTON, & ZOOM CONTROLS --}}
    {{-- ===================================================== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-border p-8 sm:p-10 rounded-3xl shadow-darkCard">
            <div class="space-y-3">
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- Back to Folders Button with Thin Border --}}
                    <a href="{{ route('staff.folders') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-surface border border-border text-primary hover:border-accent text-sm font-bold transition shadow-sm">
                        &larr; Back to Folders
                    </a>
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold text-primary tracking-tight break-words flex items-baseline gap-3 pt-1">
                    <span class="w-4 h-4 rounded-full bg-accent inline-block shrink-0"></span>
                    <span>
                        {{ $folder->name }}
                        @if(!empty($folder->branch))
                            <span class="text-secondary font-medium text-xl sm:text-2xl">, Branch :- {{ $folder->branch }}</span>
                        @endif
                        <span class="text-secondary font-medium text-2xl">/ Mark Attendance</span>
                    </span>
                </h1>

                <p class="text-secondary text-base sm:text-lg">
                    Date: {{ \Carbon\Carbon::today()->format('d M, Y') }} • Select attendance status for each student.
                </p>
            </div>

            {{-- Zoom Controls --}}
            <div class="flex items-center gap-2 bg-bg p-2 rounded-2xl border border-border self-start md:self-auto shrink-0">
                <span id="zoomLevelText" class="text-xs font-mono font-bold text-primary px-2">100%</span>
                <button type="button" onclick="changeZoom(-0.1)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-surface border border-border text-primary font-bold hover:border-accent transition">-</button>
                <button type="button" onclick="changeZoom(0.1)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-surface border border-border text-primary font-bold hover:border-accent transition">+</button>
                <button type="button" onclick="resetZoom()" class="px-3 py-1.5 rounded-xl bg-accent text-white text-xs font-semibold hover:bg-accentHover transition shadow-sm">Reset</button>
            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- SEARCH BAR --}}
    {{-- ===================================================== --}}
    <div class="mb-8 flex justify-start">
        <div class="relative w-full max-w-xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-secondary">
                🔍
            </span>
            <input
                type="text"
                id="studentSearch"
                placeholder="Search by name, roll number, branch, or mobile number..."
                class="bg-surface border border-border rounded-2xl pl-11 pr-5 py-3.5 text-sm text-primary w-full shadow-inner focus:outline-none focus:border-accent"
                autocomplete="off"
            >
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- QUICK STATS SUMMARY CARDS --}}
    {{-- ===================================================== --}}
    @php
        $totalStudents = $folder->students->count();
        $presentCount = 0;
        $absentCount = 0;

        foreach ($folder->students as $student) {
            $status = $selected_status[$student->id] ?? null;
            if ($status === 'present') {
                $presentCount++;
            } elseif ($status === 'absent') {
                $absentCount++;
            }
        }
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-surface border border-border p-6 rounded-3xl text-center shadow-darkCard">
            <div class="text-4xl font-extrabold text-primary tracking-tight mt-1">{{ $totalStudents }}</div>
            <div class="text-secondary text-sm font-semibold uppercase tracking-wider mt-2">Total Students</div>
        </div>
        <div class="bg-surface border border-success/40 p-6 rounded-3xl text-center shadow-darkCard">
            <div class="text-4xl font-extrabold text-success tracking-tight mt-1" id="presentCountDisplay">{{ $presentCount }}</div>
            <div class="text-success text-sm font-semibold uppercase tracking-wider mt-2">Present</div>
        </div>
        <div class="bg-surface border border-danger/40 p-6 rounded-3xl text-center shadow-darkCard">
            <div class="text-4xl font-extrabold text-danger tracking-tight mt-1" id="absentCountDisplay">{{ $absentCount }}</div>
            <div class="text-danger text-sm font-semibold uppercase tracking-wider mt-2">Absent</div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- ATTENDANCE TABLE FORM --}}
    {{-- ===================================================== --}}
    <div class="bg-surface border border-border rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <form method="POST" action="{{ route('staff.attendance.submit', $folder->id) }}" id="attendanceForm" onsubmit="return validateAttendanceSubmit(event)">
            @csrf
            <input type="hidden" name="is_subject" value="{{ $isSubject ? '1' : '0' }}">

            <div class="p-8 border-b border-border flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-primary">Student Attendance</h2>
                    <p class="text-secondary text-sm mt-1">Mark attendance for all students before submitting.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-base min-w-[1000px]" id="studentsTable">
                    <thead class="bg-bg border-b border-border">
                        <tr class="text-sm uppercase tracking-wider text-secondary">
                            <th class="w-20 py-5 px-6">Serno</th>
                            <th class="py-5 px-6">Name & Added Date</th>
                            <th class="py-5 px-6">Roll Number</th>
                            <th class="py-5 px-6">Branch</th>
                            <th class="py-5 px-6">Mobile</th>
                            <th class="py-5 px-6 text-center">Attendance Status</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @forelse($folder->students as $student)
                            @php
                                $currentStatus = $selected_status[$student->id] ?? null;
                            @endphp
                            <tr class="border-b border-border hover:bg-surfaceHover transition-colors student-row"
                                data-search="{{ strtolower(($student->name ?? '') . ' ' . ($student->roll_number ?? '') . ' ' . ($student->branch ?? '') . ' ' . ($student->phone ?? '')) }}"
                            >
                                <td class="p-6 font-semibold text-secondary">
                                    <span class="bg-bg px-3 py-1 rounded-lg border border-border text-xs font-mono">{{ $student->serno }}</span>
                                </td>
                                <td class="p-6">
                                    <div class="font-bold text-primary text-lg">{{ $student->name }}</div>
                                    @if($student->created_at)
                                        <span class="text-secondary text-xs mt-1 block">
                                            Added: {{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-6 font-mono font-semibold text-primary">{{ $student->roll_number }}</td>
                                <td class="p-6 text-secondary">{{ $student->branch }}</td>
                                <td class="p-6 font-mono text-secondary">{{ $student->phone }}</td>
                                <td class="p-6 text-center">
                                    {{-- Display Mode (If already saved/marked): Shows Status Badge and Edit Button --}}
                                    <div class="flex items-center justify-center gap-3 {{ is_null($currentStatus) ? 'hidden' : '' }}" id="display-box-{{ $student->id }}">
                                        @if($currentStatus === 'present')
                                            <span class="px-4 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 bg-success/10 border border-success/40 text-success">
                                                ✓ Present
                                            </span>
                                        @elseif($currentStatus === 'absent')
                                            <span class="px-4 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 bg-danger/10 border border-danger/40 text-danger">
                                                ✕ Absent
                                            </span>
                                        @endif
                                        <button
                                            type="button"
                                            onclick="enableEdit('{{ $student->id }}')"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-accent/20 border border-accent/40 text-accent hover:bg-accent/30 transition-all cursor-pointer shadow-sm"
                                        >
                                            Edit
                                        </button>
                                    </div>

                                    {{-- Unmarked Mode (No attendance marked yet): Shows ONLY Present and Absent buttons --}}
                                    <div class="flex items-center justify-center gap-2 {{ !is_null($currentStatus) ? 'hidden' : '' }}" id="edit-box-{{ $student->id }}">
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-border transition-all status-btn present {{ $currentStatus === 'present' ? 'active-present' : 'bg-bg text-secondary hover:border-success hover:text-success' }}"
                                            data-student="{{ $student->id }}"
                                            data-status="present"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✓ Present
                                        </button>
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-border transition-all status-btn absent {{ $currentStatus === 'absent' ? 'active-absent' : 'bg-bg text-secondary hover:border-danger hover:text-danger' }}"
                                            data-student="{{ $student->id }}"
                                            data-status="absent"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✕ Absent
                                        </button>
                                    </div>

                                    {{-- Edit Mode (Triggered when Edit is clicked): Shows Present, Absent, AND Save button --}}
                                    <div class="flex items-center justify-center gap-2 hidden" id="edit-save-box-{{ $student->id }}">
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-border transition-all status-btn present {{ $currentStatus === 'present' ? 'active-present' : 'bg-bg text-secondary hover:border-success hover:text-success' }}"
                                            data-student="{{ $student->id }}"
                                            data-status="present"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✓ Present
                                        </button>
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-border transition-all status-btn absent {{ $currentStatus === 'absent' ? 'active-absent' : 'bg-bg text-secondary hover:border-danger hover:text-danger' }}"
                                            data-student="{{ $student->id }}"
                                            data-status="absent"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✕ Absent
                                        </button>
                                        <button
                                            type="button"
                                            onclick="saveSingleStudent('{{ $student->id }}')"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold bg-success text-white hover:bg-success/90 transition-all cursor-pointer shadow-md"
                                        >
                                            Save
                                        </button>
                                    </div>

                                    <input type="hidden" name="student_{{ $student->id }}" id="status-{{ $student->id }}" value="{{ $currentStatus }}" class="attendance-input" data-name="{{ $student->name }}" data-roll="{{ $student->roll_number }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-20 text-center text-secondary">No students found in this folder.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($folder->students->count() > 0)
                <div class="p-8 border-t border-border bg-surface flex justify-end">
                    {{-- Main Save Attendance Button with Thin Border --}}
                    <button
                        type="submit"
                        class="bg-accent hover:bg-accentHover text-white px-8 py-4 rounded-2xl text-base font-semibold border border-accent/60 transition-all cursor-pointer shadow-md"
                    >
                        ✓ Save Attendance
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>

<style>
.active-present {
    background: #10B981 !important;
    border-color: #10B981 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.active-absent {
    background: #EF4444 !important;
    border-color: #EF4444 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}
</style>

<script>
    let currentZoom = 1.0;

    function changeZoom(delta) {
        currentZoom = Math.round((currentZoom + delta) * 10) / 10;
        if (currentZoom < 0.7) currentZoom = 0.7;
        if (currentZoom > 1.5) currentZoom = 1.5;
        applyZoom();
    }

    function resetZoom() {
        currentZoom = 1.0;
        applyZoom();
    }

    function applyZoom() {
        const container = document.getElementById('zoomable-container');
        if (container) {
            container.style.transform = `scale(${currentZoom})`;
            container.style.transformOrigin = 'top center';
        }
        const text = document.getElementById('zoomLevelText');
        if (text) {
            text.textContent = Math.round(currentZoom * 100) + '%';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const flashAlert = document.getElementById('flashAlert');
        if (flashAlert) {
            setTimeout(() => {
                flashAlert.style.opacity = '0';
                setTimeout(() => flashAlert.remove(), 300);
            }, 3000);
            document.addEventListener('click', () => flashAlert.remove(), { once: true });
        }

        const searchInput = document.getElementById('studentSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.student-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    if (searchData.includes(query)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                let noSearchRow = document.getElementById('noSearchDataRow');
                const tbody = document.getElementById('studentTableBody');
                if (visibleCount === 0 && rows.length > 0) {
                    if (!noSearchRow) {
                        noSearchRow = document.createElement('tr');
                        noSearchRow.id = 'noSearchDataRow';
                        noSearchRow.innerHTML = `
                            <td colspan="6" class="p-16 text-center text-secondary font-semibold">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="text-4xl">🔍</span>
                                    <span class="text-lg text-primary font-bold">No matching records found</span>
                                    <span class="text-xs text-secondary">Please check the spelling or search with a different roll number / mobile.</span>
                                </div>
                            </td>`;
                        tbody.appendChild(noSearchRow);
                    }
                } else if (noSearchRow) {
                    noSearchRow.remove();
                }
            });
        }
    });

    function enableEdit(studentId) {
        document.getElementById('display-box-' + studentId).classList.add('hidden');
        document.getElementById('edit-save-box-' + studentId).classList.remove('hidden');
    }

    function setAttendanceDirect(button) {
        const studentId = button.getAttribute('data-student');
        const status = button.getAttribute('data-status');
        const parentTd = button.closest('td');

        const input = document.getElementById('status-' + studentId);
        if (!input) return;
        input.value = status;

        parentTd.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('active-present', 'active-absent');
            btn.classList.add('bg-bg', 'text-secondary');
        });

        parentTd.querySelectorAll(`.status-btn[data-student="${studentId}"][data-status="${status}"]`).forEach(btn => {
            btn.classList.remove('bg-bg', 'text-secondary');
            if (status === 'present') {
                btn.classList.add('active-present');
            } else {
                btn.classList.add('active-absent');
            }
        });

        updateLiveCounts();
    }

    function updateLiveCounts() {
        let present = 0;
        let absent = 0;
        document.querySelectorAll('.attendance-input').forEach(input => {
            if (input.value === 'present') present++;
            else if (input.value === 'absent') absent++;
        });
        document.getElementById('presentCountDisplay').textContent = present;
        document.getElementById('absentCountDisplay').textContent = absent;
    }

    function validateAttendanceSubmit(event) {
        let missingStudents = [];
        document.querySelectorAll('.attendance-input').forEach(input => {
            if (!input.value || (input.value !== 'present' && input.value !== 'absent')) {
                const name = input.getAttribute('data-name');
                const roll = input.getAttribute('data-roll');
                missingStudents.push(`• Roll: ${roll} (${name})`);
            }
        });

        if (missingStudents.length > 0) {
            const message = "⚠️ ATTENDANCE SUBMISSION BLOCKED\n\n" +
                            "Please ensure attendance status (Present or Absent) is marked for all students before saving.\n\n" +
                            "Pending Students:\n" + missingStudents.join('\n');
            alert(message);
            event.preventDefault();
            return false;
        }
        return true;
    }

    function saveSingleStudent(studentId) {
        const statusInput = document.getElementById('status-' + studentId);
        if (!statusInput || !statusInput.value) {
            alert('⚠️ Please select Present or Absent before saving this student.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('staff.attendance.submit', $folder->id) }}";

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";
        form.appendChild(csrf);

        const subjectField = document.createElement('input');
        subjectField.type = 'hidden';
        subjectField.name = 'is_subject';
        subjectField.value = "{{ $isSubject ? '1' : '0' }}";
        form.appendChild(subjectField);

        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'student_' + studentId;
        statusField.value = statusInput.value;
        form.appendChild(statusField);

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection