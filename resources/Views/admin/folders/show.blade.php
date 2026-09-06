@extends('layouts.app')

@section('title', $folder->name)

@section('content')

<div class="mb-8">

    <a
        href="{{ route('admin.dashboard') }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Dashboard
    </a>

    <h1 class="text-3xl font-bold mt-3">
        {{ $folder->name }}
    </h1>

    <p class="text-secondary mt-1">
        Attendance Management Folder
    </p>

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


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">


    {{-- Students --}}

    <a
        href="{{ route('admin.students.index', $folder->id) }}"
        class="bg-surface border border-border rounded-2xl p-8 hover:border-accent hover:scale-[1.02] transition"
    >

        <div class="text-5xl mb-5">
            👨‍🎓
        </div>

        <h2 class="text-xl font-bold">
            Students
        </h2>

        <p class="text-secondary mt-2">
            Add, edit, search and manage students.
        </p>

        <div class="mt-5 text-accent font-semibold">
            {{ $folder->students_count ?? 0 }}
            Students →
        </div>

    </a>


    {{-- Report --}}

    <a
        href="{{ route('admin.report.show', [
            'id' => $folder->id,
            'type' => 'daily'
        ]) }}"
        class="bg-surface border border-border rounded-2xl p-8 hover:border-accent hover:scale-[1.02] transition"
    >

        <div class="text-5xl mb-5">
            📊
        </div>

        <h2 class="text-xl font-bold">
            Report
        </h2>

        <p class="text-secondary mt-2">
            Daily, monthly and custom attendance reports.
        </p>

        <div class="mt-5 text-accent font-semibold">
            Open Report →
        </div>

    </a>


    {{-- Total --}}

    <a
        href="{{ route('admin.report.total', $folder->id) }}"
        class="bg-surface border border-border rounded-2xl p-8 hover:border-accent hover:scale-[1.02] transition"
    >

        <div class="text-5xl mb-5">
            📈
        </div>

        <h2 class="text-xl font-bold">
            Total
        </h2>

        <p class="text-secondary mt-2">
            Today's attendance summary and percentage.
        </p>

        <div class="mt-5 text-accent font-semibold">
            View Total →
        </div>

    </a>


    {{-- Attendance --}}

    <a
        href="{{ route('admin.attendance.show', $folder->id) }}"
        class="bg-surface border border-border rounded-2xl p-8 hover:border-accent hover:scale-[1.02] transition"
    >

        <div class="text-5xl mb-5">
            ✅
        </div>

        <h2 class="text-xl font-bold">
            Mark Attendance
        </h2>

        <p class="text-secondary mt-2">
            Mark Present or Absent for students.
        </p>

        <div class="mt-5 text-accent font-semibold">
            Open Attendance →
        </div>

    </a>

</div>

@endsection