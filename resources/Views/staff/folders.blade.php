@extends('layouts.app')

@section('title', 'Staff Folders - AMS 2026')

@section('content')

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- FLOATING TOP NOTIFICATION MESSAGE --}}
    @if(session('success'))
        <div
            id="flashAlert"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-emerald-950/95 border border-emerald-500 text-emerald-200 px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer"
        >
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER WITH TITLE & PROPER LOGOUT BUTTON --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-orangeAccent/10 border border-orangeAccent/30 text-orangeAccent text-xs font-bold uppercase tracking-wider mb-3">
                Staff Portal Login
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight">
                Select Folder for Attendance
            </h1>
            <p class="text-textSecondary text-base mt-2">
                Choose a class or subject folder to manage and mark daily attendance records.
            </p>
        </div>

        <div>
            <form method="POST" action="{{ route('staff.logout') }}">
                @csrf
                <button
                    type="submit"
                    class="px-6 py-3 rounded-2xl bg-red-600/20 border border-red-500/40 text-red-400 hover:bg-red-600 hover:text-white text-sm font-bold transition-all shadow-sm cursor-pointer"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- CLASS FOLDERS SECTION --}}
    <div class="mb-12">
        <h2 class="text-xl font-bold text-textPrimary mb-6 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-orangeAccent inline-block"></span>
            Class Folders
        </h2>

        @if(isset($folders) && $folders->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($folders as $folder)
                    @php
                        $totalStudents = $folder->students()->count();
                        $markedToday = $folder->marked_today ?? false;
                    @endphp

                    <a
                        href="{{ route('staff.attendance.show', $folder->id) }}"
                        class="group relative bg-surface border border-borderCol hover:border-orangeAccent p-7 rounded-3xl transition-all duration-300 shadow-darkCard hover:-translate-y-1 block"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-bold text-textPrimary group-hover:text-orangeAccent transition-colors">
                                    {{ $folder->name }}
                                </h3>
                                @if(!empty($folder->branch))
                                    <p class="text-textSecondary text-sm mt-1">Branch: {{ $folder->branch }}</p>
                                @endif
                            </div>

                            @if($markedToday)
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 text-xs font-bold inline-flex items-center gap-1 shadow-sm shrink-0">
                                    ✓ Marked
                                </span>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center justify-between pt-4 border-t border-borderCol text-sm">
                            <span class="text-textMuted font-medium">
                                Total Students: <strong class="text-textPrimary">{{ $totalStudents }}</strong>
                            </span>
                            
                            @if($totalStudents > 0)
                                @if($markedToday)
                                    <span class="text-emerald-400 font-bold group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                        Attendance Marked &rarr;
                                    </span>
                                @else
                                    <span class="text-amber-500 font-bold group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                        Not Marked &rarr;
                                    </span>
                                @endif
                            @else
                                <span class="text-textMuted text-xs italic">
                                    No students
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-surface border border-borderCol p-12 rounded-3xl text-center text-textMuted">
                No class folders available.
            </div>
        @endif
    </div>

    {{-- SUBJECT FOLDERS SECTION --}}
    @if(isset($subjects) && $subjects->count() > 0)
        <div class="mb-12">
            <h2 class="text-xl font-bold text-textPrimary mb-6 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                Subject Folders
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subjects as $subject)
                    @php
                        $totalStudents = $subject->students()->count();
                        $markedToday = $subject->marked_today ?? false;
                    @endphp

                    <a
                        href="{{ route('staff.attendance.show', $subject->id) }}"
                        class="group relative bg-surface border border-borderCol hover:border-blue-500 p-7 rounded-3xl transition-all duration-300 shadow-darkCard hover:-translate-y-1 block"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-bold text-textPrimary group-hover:text-blue-400 transition-colors">
                                    {{ $subject->name }}
                                </h3>
                            </div>

                            @if($markedToday)
                                <span class="px-3 py-1 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 text-xs font-bold inline-flex items-center gap-1 shadow-sm shrink-0">
                                    ✓ Marked
                                </span>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center justify-between pt-4 border-t border-borderCol text-sm">
                            <span class="text-textMuted font-medium">
                                Total Students: <strong class="text-textPrimary">{{ $totalStudents }}</strong>
                            </span>

                            @if($totalStudents > 0)
                                @if($markedToday)
                                    <span class="text-emerald-400 font-bold group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                        Attendance Marked &rarr;
                                    </span>
                                @else
                                    <span class="text-amber-500 font-bold group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                                        Not Marked &rarr;
                                    </span>
                                @endif
                            @else
                                <span class="text-textMuted text-xs italic">
                                    No students
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flashAlert = document.getElementById('flashAlert');
        if (flashAlert) {
            setTimeout(() => {
                flashAlert.style.opacity = '0';
                setTimeout(() => flashAlert.remove(), 300);
            }, 3000);
            document.addEventListener('click', () => flashAlert.remove(), { once: true });
        }
    });
</script>
@endsection