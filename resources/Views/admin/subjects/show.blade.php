@extends('layouts.app')

@section('title', $subject->name)

@section('content')

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER WITH LEFT BACK BUTTON & CENTERED SUBJECT TITLE --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard relative">
            
            {{-- Left Side: Back to Dashboard Button --}}
            <div class="shrink-0 z-10">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all"
                >
                    &larr; Back to Dashboard
                </a>
            </div>

            {{-- Center: Subject Name & Subtitle --}}
            <div class="text-center absolute inset-x-0 mx-auto hidden md:block pointer-events-none">
                <h1 class="text-4xl font-extrabold text-textPrimary tracking-tight">
                    {{ $subject->name }}
                </h1>
                <p class="text-textSecondary text-sm mt-1">
                    Subject Attendance Management Folder
                </p>
            </div>

            {{-- Mobile view title alignment --}}
            <div class="text-left md:hidden mt-2">
                <h1 class="text-3xl font-extrabold text-textPrimary tracking-tight">
                    {{ $subject->name }}
                </h1>
                <p class="text-textSecondary text-sm mt-1">
                    Subject Attendance Management Folder
                </p>
            </div>

            <div></div> {{-- Spacer for flex balance --}}
        </div>
    </div>

    @if(session('success'))
        <div class="bg-success/10 border border-success text-success px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- ACTION CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Students --}}
        <a
            href="{{ route('admin.subjects.students', $subject->id) }}"
            class="bg-surface border border-borderCol hover:border-blue-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">👨‍🎓</div>
                <h2 class="text-xl font-bold text-textPrimary">Students</h2>
                <p class="text-textSecondary text-sm mt-2">Add, edit, search and manage students.</p>
            </div>
            <div class="mt-6 text-blue-400 font-semibold text-sm">
                {{ $subject->students_count ?? $subject->students->count() ?? 0 }} Students →
            </div>
        </a>

        {{-- Report --}}
        <a
            href="{{ route('admin.subjects.report', ['id' => $subject->id, 'type' => 'daily']) }}"
            class="bg-surface border border-borderCol hover:border-blue-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">📊</div>
                <h2 class="text-xl font-bold text-textPrimary">Report</h2>
                <p class="text-textSecondary text-sm mt-2">Daily, monthly and custom attendance reports.</p>
            </div>
            <div class="mt-6 text-blue-400 font-semibold text-sm">
                Open Report →
            </div>
        </a>

        {{-- Total --}}
        <a
            href="{{ route('admin.subjects.total', $subject->id) }}"
            class="bg-surface border border-borderCol hover:border-blue-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">📈</div>
                <h2 class="text-xl font-bold text-textPrimary">Total</h2>
                <p class="text-textSecondary text-sm mt-2">Today's attendance summary and percentage.</p>
            </div>
            <div class="mt-6 text-blue-400 font-semibold text-sm">
                View Total →
            </div>
        </a>

        {{-- Mark Attendance --}}
        <a
            href="{{ route('admin.subjects.attendance', $subject->id) }}"
            class="bg-surface border border-borderCol hover:border-emerald-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">✅</div>
                <h2 class="text-xl font-bold text-textPrimary">Mark Attendance</h2>
                <p class="text-textSecondary text-sm mt-2">Mark Present or Absent for students.</p>
            </div>
            <div class="mt-6 text-emerald-400 font-semibold text-sm">
                Open Attendance →
            </div>
        </a>

    </div>

</div>

@endsection