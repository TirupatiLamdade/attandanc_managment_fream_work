@extends('layouts.app')

@section('title', 'My Attendance Report - ' . $folder->name)

@section('content')

@php
    $hasAttendance = $hasAttendance ?? false;
    $date = $date ?? now()->format('Y-m-d');
    $month = $month ?? now()->format('Y-m');
    $start = $start ?? now()->subDays(7)->format('Y-m-d');
    $end = $end ?? now()->format('Y-m-d');
    $type = $type ?? 'daily';
@endphp

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ===================================================== --}}
    {{-- CLEAN HEADER: BACK BUTTON, FOLDER ICON & NAME --}}
    {{-- ===================================================== --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <a
                    href="{{ route('student.folders') }}"
                    class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all shrink-0 w-fit"
                >
                    &larr; Back to Folders
                </a>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight break-words flex items-center gap-3">
                    <span class="text-2xl">📁</span>
                    <span>{{ $folder->name }} <span class="text-textMuted font-medium text-xl sm:text-2xl">/ My Attendance Report</span></span>
                </h1>
            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- REPORT TYPE SUB-TABS (Daily / Monthly / Custom / All) --}}
    {{-- ===================================================== --}}
    <div class="bg-surface border border-borderCol p-3 rounded-2xl mb-8 shadow-darkCard flex flex-wrap items-center gap-3">
        <a
            href="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'daily', 'date' => $date]) }}"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all {{ $type === 'daily' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover' }}"
        >
            Daily Report
        </a>
        <a
            href="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'monthly', 'month' => $month]) }}"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all {{ $type === 'monthly' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover' }}"
        >
            Monthly Report
        </a>
        <a
            href="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'custom', 'start' => $start, 'end' => $end]) }}"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all {{ $type === 'custom' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover' }}"
        >
            Custom Range Report
        </a>
        <a
            href="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'all']) }}"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all {{ $type === 'all' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover' }}"
        >
            All Days Report
        </a>
    </div>

    {{-- ===================================================== --}}
    {{-- DAILY FILTER FORM --}}
    {{-- ===================================================== --}}
    @if($type === 'daily')
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'daily']) }}" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-5">
            <input type="hidden" name="type" value="daily">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Select Date</label>
                    <input
                        type="date"
                        name="date"
                        value="{{ $date }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
                <div class="pt-5">
                    <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow w-full sm:w-auto">
                        View Report
                    </button>
                </div>
            </div>

            @if($hasAttendance)
            <div>
                <a
                    href="{{ route('student.report.pdf', ['id' => $folder->id, 'type' => 'daily', 'date' => $date]) }}"
                    target="_blank"
                    class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md w-full sm:w-auto justify-center"
                >
                    <span>📄</span> Download PDF
                </a>
            </div>
            @endif
        </form>
    </div>

    @if(!$hasAttendance)
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance marked for any student on this date.</div>
    </div>
    @endif
    @endif

    {{-- ===================================================== --}}
    {{-- MONTHLY FILTER FORM --}}
    {{-- ===================================================== --}}
    @if($type === 'monthly')
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'monthly']) }}" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-5">
            <input type="hidden" name="type" value="monthly">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Select Month</label>
                    <input
                        type="month"
                        name="month"
                        value="{{ $month }}"
                        max="{{ now()->format('Y-m') }}"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
                <div class="pt-5">
                    <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow w-full sm:w-auto">
                        View Report
                    </button>
                </div>
            </div>

            @if($hasAttendance)
            <div>
                <a
                    href="{{ route('student.report.pdf', ['id' => $folder->id, 'type' => 'monthly', 'month' => $month]) }}"
                    target="_blank"
                    class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md w-full sm:w-auto justify-center"
                >
                    <span>📄</span> Download PDF
                </a>
            </div>
            @endif
        </form>
    </div>

    @if(!$hasAttendance)
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance marked for any student in this month.</div>
    </div>
    @endif
    @endif

    {{-- ===================================================== --}}
    {{-- CUSTOM FILTER FORM --}}
    {{-- ===================================================== --}}
    @if($type === 'custom')
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'custom']) }}" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-5" id="customReportForm">
            <input type="hidden" name="type" value="custom">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full lg:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">From Date</label>
                    <input
                        type="date"
                        name="start"
                        id="startDate"
                        value="{{ $start }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">To Date</label>
                    <input
                        type="date"
                        name="end"
                        id="endDate"
                        value="{{ $end }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
            </div>

            <div class="flex items-center gap-3 w-full lg:w-auto justify-end pt-2">
                <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow">
                    View Report
                </button>

                @if($hasAttendance)
                <a
                    href="{{ route('student.report.pdf', ['id' => $folder->id, 'type' => 'custom', 'start' => $start, 'end' => $end]) }}"
                    target="_blank"
                    class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md justify-center"
                >
                    <span>📄</span> PDF
                </a>
                @endif
            </div>
        </form>
    </div>

    @if(!$hasAttendance)
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance marked between <strong>{{ $start }}</strong> and <strong>{{ $end }}</strong>.</div>
    </div>
    @endif

    <script>
    document.getElementById('customReportForm')?.addEventListener('submit', function(event) {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        if (start && end && start > end) {
            event.preventDefault();
            alert('From Date cannot be after To Date.');
            return false;
        }
    });
    </script>
    @endif

    {{-- ===================================================== --}}
    {{-- ALL DAYS INFO HEADER --}}
    {{-- ===================================================== --}}
    @if($type === 'all')
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-textPrimary">All Days Attendance Record</h3>
            <p class="text-textSecondary text-sm mt-1">Showing aggregated records from each student's joining date up to the current date.</p>
        </div>
        @if($hasAttendance)
        <div>
            <a
                href="{{ route('student.report.pdf', ['id' => $folder->id, 'type' => 'all']) }}"
                target="_blank"
                class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md justify-center"
            >
                <span>📄</span> Download PDF
            </a>
        </div>
        @endif
    </div>

    @if(!$hasAttendance)
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance records exist for this folder.</div>
    </div>
    @endif
    @endif

    {{-- ===================================================== --}}
    {{-- SEARCH FILTER BAR --}}
    {{-- ===================================================== --}}
    @if($hasAttendance || $type === 'all')
    <div class="mb-8 flex justify-start">
        <div class="relative w-full max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-textMuted">
                🔍
            </span>
            <input
                type="text"
                id="reportSearch"
                placeholder="Search students..."
                class="input-dark rounded-2xl pl-11 pr-5 py-3 text-sm w-full shadow-inner"
                onkeyup="searchReport()"
            >
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- REPORT TABLE --}}
    {{-- ===================================================== --}}
    <div class="bg-surface border border-borderCol rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <div class="overflow-x-auto">
            <table class="table-dark text-left text-base" id="studentsTable">
                <thead>
                    <tr class="text-sm uppercase tracking-wider text-textSecondary">
                        <th class="w-20 py-5 px-6">S.No</th>
                        <th class="py-5 px-6">Name</th>
                        <th class="py-5 px-6">Roll Number</th>
                        <th class="py-5 px-6">Branch</th>
                        <th class="py-5 px-6">Mobile Number</th>

                        @if($type === 'daily')
                            <th class="text-center py-5 px-6">Status</th>
                        @else
                            <th class="text-center py-5 px-6">Total Days</th>
                            <th class="text-center py-5 px-6">Present</th>
                            <th class="text-center py-5 px-6">Absent</th>
                            <th class="text-center py-5 px-6">Percentage</th>
                        @endif
                    </tr>
                </thead>

                <tbody id="reportTableBody">
                    @forelse($studentsData ?? [] as $data)
                    @php
                        $studentObj = $data['student'] ?? null;
                        $name = $studentObj->name ?? $data['name'] ?? '';
                        $roll = $studentObj->roll_number ?? $data['roll'] ?? '';
                        $branch = $studentObj->branch ?? $data['branch'] ?? '';
                        $phone = $studentObj->phone ?? $data['phone'] ?? '';
                        $serno = $studentObj->serno ?? $data['serno'] ?? $loop->iteration;
                    @endphp
                    <tr
                        class="report-row"
                        data-search="{{ strtolower($name . ' ' . $roll . ' ' . $branch . ' ' . $phone) }}"
                    >
                        <td class="font-semibold text-textSecondary py-5 px-6">
                            {{ $serno }}
                        </td>
                        <td class="font-bold text-textPrimary py-5 px-6 text-lg">
                            {{ $name }}
                        </td>
                        <td class="py-5 px-6">
                            <span class="bg-card px-3 py-1.5 rounded-xl border border-borderCol text-sm font-mono font-semibold">
                                {{ $roll }}
                            </span>
                        </td>
                        <td class="text-textSecondary py-5 px-6">
                            {{ $branch }}
                        </td>
                        <td class="text-textSecondary font-mono py-5 px-6">
                            {{ $phone }}
                        </td>

                        @if($type === 'daily')
                            <td class="py-5 px-6 text-center">
                                @if(($data['daily_status'] ?? '') === 'Present')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> ✅ Present
                                    </span>
                                @elseif(($data['daily_status'] ?? '') === 'Absent')
                                    <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/40 text-red-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                        <span class="w-2 h-2 rounded-full bg-red-400"></span> ❌ Absent
                                    </span>
                                @else
                                    <span class="text-textMuted font-medium">-</span>
                                @endif
                            </td>
                        @else
                            <td class="py-5 px-6 text-center font-semibold text-textPrimary">
                                {{ $data['total_days'] ?? 0 }}
                            </td>
                            <td class="py-5 px-6 text-center text-emerald-400 font-bold">
                                {{ $data['present'] ?? 0 }}
                            </td>
                            <td class="py-5 px-6 text-center text-red-400 font-bold">
                                {{ $data['absent'] ?? 0 }}
                            </td>
                            <td class="py-5 px-6 text-center font-bold text-textPrimary">
                                {{ $data['percentage'] ?? 0 }}%
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr id="noSearchDataRow">
                        <td colspan="{{ $type === 'daily' ? 6 : 9 }}" class="p-16 text-center text-textMuted">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-5xl mb-3">🎓</span>
                                <p class="font-bold text-lg text-textSecondary">No Records Found</p>
                                <p class="text-sm text-textMuted mt-1">No attendance records found for this criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<script>
function searchReport() {
    const input = document.getElementById('reportSearch')?.value.toLowerCase().trim();
    if (input === undefined) return;

    const rows = document.querySelectorAll('.report-row');
    let visibleCount = 0;

    rows.forEach(function(row) {
        const text = row.getAttribute('data-search') || '';
        if (text.includes(input)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    let noDataRow = document.getElementById('noSearchDataRow');
    const tbody = document.getElementById('reportTableBody');

    if (visibleCount === 0 && rows.length > 0) {
        if (!noDataRow) {
            noDataRow = document.createElement('tr');
            noDataRow.id = 'noSearchDataRow';
            noDataRow.innerHTML = `
                <td colspan="9" class="p-16 text-center text-textMuted">
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-5xl mb-3">🔍</span>
                        <p class="font-bold text-lg text-textSecondary">No Matching Student Found</p>
                        <p class="text-sm text-textMuted mt-1">No student record matched your search query.</p>
                    </div>
                </td>
            `;
            tbody.appendChild(noDataRow);
        }
    } else {
        if (noDataRow) {
            noDataRow.remove();
        }
    }
}
</script>

@endsection