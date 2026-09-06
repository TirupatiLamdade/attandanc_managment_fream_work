@extends('layouts.app')

@section('title', $subject->name)

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.dashboard') }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Dashboard
    </a>

    <h1 class="text-3xl font-bold mt-2">
        {{ $subject->name }}
    </h1>

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


<!-- ============================================== -->
<!-- SUBJECT ACTION CARDS -->
<!-- ============================================== -->

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">


    <!-- STUDENTS -->

    <a
        href="{{ route('admin.subjects.students', $subject->id) }}"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            👨‍🎓
        </div>

        <h3 class="font-semibold text-lg">
            Students
        </h3>

        <p class="text-secondary text-sm mt-2">
            Select & Manage Students
        </p>

    </a>


    <!-- MARK ATTENDANCE -->

    <a
        href="{{ route('admin.subjects.attendance', $subject->id) }}"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            ✅
        </div>

        <h3 class="font-semibold text-lg">
            Mark Attendance
        </h3>

        <p class="text-secondary text-sm mt-2">
            Mark subject attendance
        </p>

    </a>


    <!-- REPORT -->

    <a
        href="{{ route('admin.subjects.report', $subject->id) }}"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            📊
        </div>

        <h3 class="font-semibold text-lg">
            Report
        </h3>

        <p class="text-secondary text-sm mt-2">
            Daily / Monthly / Custom
        </p>

    </a>


    <!-- TOTAL -->

    <a
        href="{{ route('admin.subjects.total', $subject->id) }}"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            📈
        </div>

        <h3 class="font-semibold text-lg">
            Total
        </h3>

        <p class="text-secondary text-sm mt-2">
            Subject attendance stats
        </p>

    </a>

</div>

@endsection