@extends('layouts.app')

@section('title', 'Attendance History - ' . $student->name)

@section('content')

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- ===================================================== --}}
    {{-- TOP BAR & NAVIGATION --}}
    {{-- ===================================================== --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <a
            href="{{ route('admin.attendance.show', $folder->id) }}"
            class="btn-danger inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all"
        >
            &larr; Back to Attendance
        </a>

        <form method="POST" action="{{ route('admin.attendance.history.lock', [$folder->id, $student->id]) }}">
            @csrf
            <button
                type="submit"
                class="bg-surface border border-borderCol text-textSecondary hover:text-textPrimary hover:bg-cardHover px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md"
                onclick="return confirm('Lock this attendance history?')"
            >
                🔒 Lock History
            </button>
        </form>
    </div>

    {{-- ===================================================== --}}
    {{-- MESSAGES --}}
    {{-- ===================================================== --}}
    @if(session('success'))
        <div class="mb-8 bg-emerald-950/80 border border-emerald-500/50 text-emerald-200 px-6 py-5 rounded-2xl flex items-center justify-between shadow-lg text-base">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-xs font-bold uppercase">Dismiss</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl flex items-center justify-between shadow-lg text-base">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-red-400 animate-pulse"></span>
                <span class="font-medium">⚠️ {{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-200 text-xs font-bold uppercase">Dismiss</button>
        </div>
    @endif

    {{-- ===================================================== --}}
    {{-- STUDENT HEADER CARD --}}
    {{-- ===================================================== --}}
    <div class="bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl mb-8 shadow-darkCard">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-orangeAccent/10 border border-orangeAccent/30 flex items-center justify-center text-orangeAccent font-extrabold text-2xl shrink-0">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight">
                        {{ $student->name }}
                    </h1>
                    <p class="text-textSecondary text-sm sm:text-base mt-1">
                        Complete Attendance History & Calendar View
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="bg-card px-4 py-2 rounded-xl border border-borderCol text-sm font-semibold text-textSecondary">
                    Roll: <strong class="text-textPrimary font-mono">{{ $student->roll_number }}</strong>
                </span>
                <span class="bg-card px-4 py-2 rounded-xl border border-borderCol text-sm font-semibold text-textSecondary">
                    Branch: <strong class="text-textPrimary">{{ $student->branch }}</strong>
                </span>
                <span class="bg-card px-4 py-2 rounded-xl border border-borderCol text-sm font-semibold text-textSecondary">
                    Mobile: <strong class="text-textPrimary font-mono">{{ $student->phone }}</strong>
                </span>
                <span class="bg-card px-4 py-2 rounded-xl border border-borderCol text-sm font-semibold text-textSecondary">
                    Added: <strong class="text-textPrimary">{{ optional($student->created_at)->format('d M Y') }}</strong>
                </span>
            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- STATISTICS CALCULATION --}}
    {{-- ===================================================== --}}
    @php
        $addedDate = $student->created_at ? \Carbon\Carbon::parse($student->created_at)->startOfDay() : \Carbon\Carbon::today();
        $today = \Carbon\Carbon::today();

        $totalApplicableDays = 0;
        $presentCount = 0;
        $absentCount = 0;

        $historyRecords = $student->attendances->keyBy(function ($attendance) {
            return \Carbon\Carbon::parse($attendance->date)->format('Y-m-d');
        });

        $cursor = $addedDate->copy();
        while ($cursor->lte($today)) {
            $dateKey = $cursor->format('Y-m-d');
            $totalApplicableDays++;

            if ($historyRecords->has($dateKey)) {
                $statusVal = strtolower($historyRecords[$dateKey]->status);
                if ($statusVal === 'present') {
                    $presentCount++;
                } elseif ($statusVal === 'absent') {
                    $absentCount++;
                }
            }
            $cursor->addDay();
        }

        $percentage = $totalApplicableDays > 0 ? round(($presentCount / $totalApplicableDays) * 100, 2) : 0;
    @endphp

    {{-- ===================================================== --}}
    {{-- STATS CARDS --}}
    {{-- ===================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard text-center">
            <div class="text-textSecondary text-xs font-semibold uppercase tracking-wider mb-2">Applicable Days</div>
            <div class="text-3xl font-extrabold text-textPrimary">{{ $totalApplicableDays }}</div>
        </div>
        <div class="bg-surface border border-emerald-500/40 p-6 rounded-3xl shadow-darkCard text-center">
            <div class="text-emerald-400 text-xs font-semibold uppercase tracking-wider mb-2">Present</div>
            <div class="text-3xl font-extrabold text-emerald-400">{{ $presentCount }}</div>
        </div>
        <div class="bg-surface border border-red-500/40 p-6 rounded-3xl shadow-darkCard text-center">
            <div class="text-red-400 text-xs font-semibold uppercase tracking-wider mb-2">Absent</div>
            <div class="text-3xl font-extrabold text-red-400">{{ $absentCount }}</div>
        </div>
        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard text-center">
            <div class="text-textSecondary text-xs font-semibold uppercase tracking-wider mb-2">Attendance %</div>
            <div class="text-3xl font-extrabold text-textPrimary">{{ $percentage }}%</div>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- HISTORY TABLE --}}
    {{-- ===================================================== --}}
    <div class="bg-surface border border-borderCol rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <div class="p-8 border-b border-borderCol flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-textPrimary">Daily Attendance Records</h2>
                <p class="text-textSecondary text-sm mt-1">Showing all records from joining date up to current date.</p>
            </div>
            <div class="bg-card px-4 py-2 rounded-xl border border-borderCol text-xs font-mono font-semibold text-textSecondary">
                {{ $addedDate->format('d M Y') }} &rarr; {{ $today->format('d M Y') }}
            </div>
        </div>

        @if($totalApplicableDays > 0)
            <div class="overflow-x-auto">
                <table class="table-dark text-left text-base">
                    <thead>
                        <tr class="text-sm uppercase tracking-wider text-textSecondary">
                            <th class="w-16 py-5 px-6">#</th>
                            <th class="py-5 px-6">Date</th>
                            <th class="py-5 px-6">Day</th>
                            <th class="py-5 px-6 text-center">Status</th>
                            <th class="py-5 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rowNumber = 1;
                            $cursor = $addedDate->copy();
                        @endphp

                        @while($cursor->lte($today))
                            @php
                                $dateKey = $cursor->format('Y-m-d');
                                $attendance = $historyRecords->get($dateKey);
                                $status = $attendance ? ucfirst(strtolower($attendance->status)) : null;
                            @endphp
                            <tr class="report-row">
                                <td class="font-semibold text-textSecondary py-5 px-6">
                                    {{ $rowNumber }}
                                </td>
                                <td class="font-bold text-textPrimary py-5 px-6">
                                    {{ $cursor->format('d M Y') }}
                                </td>
                                <td class="text-textSecondary py-5 px-6">
                                    {{ $cursor->format('l') }}
                                </td>
                                <td class="py-5 px-6 text-center">
                                    @if($status === 'Present')
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span> ✅ Present
                                        </span>
                                    @elseif($status === 'Absent')
                                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/40 text-red-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                            <span class="w-2 h-2 rounded-full bg-red-400"></span> ❌ Absent
                                        </span>
                                    @else
                                        <span class="text-textMuted font-medium">— Not Marked</span>
                                    @endif
                                </td>
                                <td class="py-5 px-6 text-center">
                                    <button
                                        type="button"
                                        class="btn-primary px-4 py-2 rounded-xl text-xs font-semibold shadow-md"
                                        onclick="openEditPanel('{{ $dateKey }}', '{{ $status ?? '' }}')"
                                    >
                                        Edit / Change
                                    </button>
                                </td>
                            </tr>
                            @php
                                $rowNumber++;
                                $cursor->addDay();
                            @endphp
                        @endwhile
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-16 text-center text-textMuted">
                <p class="font-bold text-lg text-textSecondary">No Applicable Dates Found</p>
            </div>
        @endif
    </div>

    {{-- ===================================================== --}}
    {{-- EDIT PANEL --}}
    {{-- ===================================================== --}}
    <div id="editPanel" class="bg-surface border border-borderCol p-8 rounded-3xl shadow-darkCard hidden mb-10">
        <h3 class="text-xl font-bold text-textPrimary mb-2">Update Attendance Record</h3>
        <div id="selectedEditDate" class="text-textSecondary text-sm mb-6">Select a date from the table above.</div>

        <form method="POST" action="{{ route('admin.attendance.history.save', [$folder->id, $student->id]) }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            @csrf
            <input type="hidden" name="date" id="editDate" value="">

            <div class="flex items-center gap-4">
                <label class="cursor-pointer inline-flex items-center gap-2 bg-card border border-borderCol px-5 py-3 rounded-xl font-semibold text-sm text-textPrimary hover:border-emerald-500 transition-all">
                    <input type="radio" name="status" id="statusPresent" value="present" class="accent-emerald-500">
                    <span>✓ Present</span>
                </label>
                <label class="cursor-pointer inline-flex items-center gap-2 bg-card border border-borderCol px-5 py-3 rounded-xl font-semibold text-sm text-textPrimary hover:border-red-500 transition-all">
                    <input type="radio" name="status" id="statusAbsent" value="absent" class="accent-red-500">
                    <span>✕ Absent</span>
                </label>
            </div>

            <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow">
                Save Changes to Database
            </button>
        </form>
    </div>

</div>

<script>
function openEditPanel(date, status) {
    const panel = document.getElementById('editPanel');
    const dateInput = document.getElementById('editDate');
    const dateText = document.getElementById('selectedEditDate');
    const presentRadio = document.getElementById('statusPresent');
    const absentRadio = document.getElementById('statusAbsent');

    dateInput.value = date;

    const dateObject = new Date(date + 'T00:00:00');
    const formattedDate = dateObject.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    dateText.innerHTML = '<strong>Selected Date:</strong> ' + formattedDate;

    presentRadio.checked = false;
    absentRadio.checked = false;

    if (status.toLowerCase() === 'present') {
        presentRadio.checked = true;
    } else if (status.toLowerCase() === 'absent') {
        absentRadio.checked = true;
    }

    panel.classList.remove('hidden');
    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

@endsection