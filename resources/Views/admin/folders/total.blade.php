@extends('layouts.app')

@section('title', 'Total - ' . $folder->name)

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.folders.show', $folder->id) }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Folder
    </a>

    <h1 class="text-3xl font-bold mt-3">
        Today's Attendance
    </h1>

    <p class="text-secondary mt-1">
        {{ $folder->name }} • {{ now()->format('d M Y') }}
    </p>

</div>


<div class="grid md:grid-cols-2 lg:grid-cols-5 gap-5">


    <div class="bg-surface border border-border rounded-xl p-6 text-center">

        <div class="text-4xl">
            👥
        </div>

        <div class="text-3xl font-bold mt-3">
            {{ $total }}
        </div>

        <div class="text-secondary">
            Total Students
        </div>

    </div>


    <div class="bg-surface border border-success rounded-xl p-6 text-center">

        <div class="text-4xl">
            ✅
        </div>

        <div class="text-3xl font-bold text-success mt-3">
            {{ $present }}
        </div>

        <div class="text-secondary">
            Present
        </div>

    </div>


    <div class="bg-surface border border-danger rounded-xl p-6 text-center">

        <div class="text-4xl">
            ❌
        </div>

        <div class="text-3xl font-bold text-danger mt-3">
            {{ $absent }}
        </div>

        <div class="text-secondary">
            Absent
        </div>

    </div>


    <div class="bg-surface border border-border rounded-xl p-6 text-center">

        <div class="text-4xl">
            📝
        </div>

        <div class="text-3xl font-bold mt-3">
            {{ $notMarked }}
        </div>

        <div class="text-secondary">
            Not Marked
        </div>

    </div>


    <div class="bg-surface border border-border rounded-xl p-6 text-center">

        <div class="text-4xl">
            📊
        </div>

        <div class="text-3xl font-bold mt-3">
            {{ $percentage }}%
        </div>

        <div class="text-secondary">
            Present %
        </div>

    </div>

</div>


@if($total > 0)

<div class="bg-surface border border-border rounded-xl p-6 mt-7">

    <h2 class="font-bold mb-4">
        Today's Attendance Progress
    </h2>

    <div class="w-full h-5 bg-bg rounded-full overflow-hidden">

        <div
            class="bg-success h-full rounded-full"
            style="width: {{ min(100, $percentage) }}%"
        ></div>

    </div>

    <div class="text-center text-secondary mt-3">
        {{ $present }} Present out of {{ $total }} students
    </div>

</div>

@endif

@endsection