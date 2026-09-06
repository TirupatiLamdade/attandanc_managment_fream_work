@extends('layouts.app')

@section('title', 'Total Attendance - ' . $subject->name)

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.subjects.show', $subject->id) }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Subject
    </a>

    <h1 class="text-3xl font-bold mt-2">
        {{ $subject->name }} - Total Attendance
    </h1>

</div>


<div class="bg-surface border border-border rounded-xl overflow-hidden">

    <div class="p-5 border-b border-border">

        <h2 class="text-xl font-bold">
            Overall Subject Attendance
        </h2>

        <p class="text-secondary text-sm mt-1">
            Complete attendance summary
        </p>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-bg">

                <tr>

                    <th class="p-4 text-left">
                        Serno
                    </th>

                    <th class="p-4 text-left">
                        Student
                    </th>

                    <th class="p-4 text-left">
                        Roll
                    </th>

                    <th class="p-4 text-center">
                        Present
                    </th>

                    <th class="p-4 text-center">
                        Absent
                    </th>

                    <th class="p-4 text-center">
                        Total
                    </th>

                    <th class="p-4 text-center">
                        Percentage
                    </th>

                </tr>

            </thead>


            <tbody>

            @forelse($studentsData as $data)

                <tr class="border-b border-border">

                    <td class="p-4">
                        {{ $data['student']->serno }}
                    </td>

                    <td class="p-4 font-medium">
                        {{ $data['student']->name }}
                    </td>

                    <td class="p-4">
                        {{ $data['student']->roll_number }}
                    </td>

                    <td class="p-4 text-center text-green-500 font-semibold">
                        {{ $data['present'] }}
                    </td>

                    <td class="p-4 text-center text-red-500 font-semibold">
                        {{ $data['absent'] }}
                    </td>

                    <td class="p-4 text-center font-semibold">
                        {{ $data['total'] }}
                    </td>

                    <td class="p-4 text-center font-bold">
                        {{ $data['percentage'] }}%
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="p-10 text-center text-secondary"
                    >
                        No students found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection