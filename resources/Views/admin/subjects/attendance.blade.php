@extends('layouts.app')

@section('title', 'Mark Attendance - ' . $subject->name)

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.subjects.show', $subject->id) }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Subject
    </a>

    <h1 class="text-3xl font-bold mt-2">
        {{ $subject->name }}
    </h1>

    <p class="text-secondary mt-1">
        Mark Subject-wise Attendance
    </p>

</div>


@if(session('success'))

<div class="bg-success/10 border border-success text-success px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>

@endif


@if($errors->any())

<div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">

    <ul class="list-disc ml-5">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


<div class="bg-surface border border-border rounded-xl overflow-hidden glow-accent">

    <div class="p-5 border-b border-border flex justify-between items-center">

        <div>

            <h2 class="text-xl font-bold">
                {{ $subject->name }}
            </h2>

            <p class="text-secondary text-sm mt-1">
                Date: {{ \Carbon\Carbon::parse($today)->format('d M Y') }}
            </p>

        </div>

        <div class="text-sm text-secondary">

            Total Students:
            <span class="font-bold text-white">
                {{ $subject->students->count() }}
            </span>

        </div>

    </div>


    @if($subject->students->count() > 0)

    <form
        method="POST"
        action="{{ route('admin.subjects.attendance.submit', $subject->id) }}"
    >

        @csrf


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-bg border-b border-border">

                    <tr>

                        <th class="text-left p-4 text-secondary font-medium">
                            Serno
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Student Name
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Roll Number
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Branch
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Attendance
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($subject->students as $student)

                    @php
                        $currentStatus = $attendances[$student->id] ?? null;
                    @endphp

                    <tr class="border-b border-border hover:bg-bg/50">

                        <td class="p-4">
                            {{ $student->serno }}
                        </td>

                        <td class="p-4 font-medium">
                            {{ $student->name }}
                        </td>

                        <td class="p-4">
                            {{ $student->roll_number }}
                        </td>

                        <td class="p-4 text-secondary">
                            {{ $student->branch }}
                        </td>

                        <td class="p-4">

                            <div class="flex justify-center gap-4">

                                <label class="flex items-center gap-2 cursor-pointer">

                                    <input
                                        type="radio"
                                        name="student_{{ $student->id }}"
                                        value="present"
                                        {{ $currentStatus === 'present' ? 'checked' : '' }}
                                    >

                                    <span class="text-green-500 font-medium">
                                        Present
                                    </span>

                                </label>


                                <label class="flex items-center gap-2 cursor-pointer">

                                    <input
                                        type="radio"
                                        name="student_{{ $student->id }}"
                                        value="absent"
                                        {{ $currentStatus === 'absent' ? 'checked' : '' }}
                                    >

                                    <span class="text-red-500 font-medium">
                                        Absent
                                    </span>

                                </label>

                            </div>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>


        <div class="p-5 border-t border-border flex justify-end">

            <button
                type="submit"
                class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold"
            >
                Save Attendance
            </button>

        </div>

    </form>

    @else

    <div class="p-10 text-center">

        <div class="text-5xl mb-4">
            👨‍🎓
        </div>

        <h3 class="text-xl font-bold">
            No Students Added
        </h3>

        <p class="text-secondary mt-2">
            First select students from folders.
        </p>

        <a
            href="{{ route('admin.subjects.show', $subject->id) }}"
            class="inline-block mt-5 bg-accent text-white px-5 py-3 rounded-lg"
        >
            Select Students
        </a>

    </div>

    @endif

</div>

@endsection