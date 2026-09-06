@extends('layouts.app')

@section('title', 'Subject Report - ' . $subject->name)

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.subjects.show', $subject->id) }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Subject
    </a>

    <h1 class="text-3xl font-bold mt-2">
        {{ $subject->name }} - Attendance Report
    </h1>

</div>


<div class="bg-surface border border-border rounded-xl p-5 mb-6">

    <form method="GET">

        <div class="grid md:grid-cols-4 gap-4">

            <div>

                <label class="block text-sm text-secondary mb-2">
                    Report Type
                </label>

                <select
                    name="type"
                    class="w-full bg-bg border border-border rounded-lg px-3 py-2"
                >

                    <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>
                        Daily
                    </option>

                    <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>
                        Monthly
                    </option>

                    <option value="custom" {{ $type === 'custom' ? 'selected' : '' }}>
                        Custom Range
                    </option>

                </select>

            </div>


            <div>

                <label class="block text-sm text-secondary mb-2">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ $date }}"
                    class="w-full bg-bg border border-border rounded-lg px-3 py-2"
                >

            </div>


            <div>

                <label class="block text-sm text-secondary mb-2">
                    Month
                </label>

                <input
                    type="month"
                    name="month"
                    value="{{ $month }}"
                    class="w-full bg-bg border border-border rounded-lg px-3 py-2"
                >

            </div>


            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full bg-accent hover:bg-accentHover text-white px-4 py-2 rounded-lg font-semibold"
                >
                    Generate Report
                </button>

            </div>

        </div>


        <div class="grid md:grid-cols-2 gap-4 mt-4">

            <div>

                <label class="block text-sm text-secondary mb-2">
                    Start Date
                </label>

                <input
                    type="date"
                    name="start"
                    value="{{ $start }}"
                    class="w-full bg-bg border border-border rounded-lg px-3 py-2"
                >

            </div>


            <div>

                <label class="block text-sm text-secondary mb-2">
                    End Date
                </label>

                <input
                    type="date"
                    name="end"
                    value="{{ $end }}"
                    class="w-full bg-bg border border-border rounded-lg px-3 py-2"
                >

            </div>

        </div>

    </form>

</div>


<div class="bg-surface border border-border rounded-xl overflow-hidden">

    <div class="p-5 border-b border-border">

        <h2 class="text-xl font-bold">
            {{ $subject->name }}
        </h2>

        <p class="text-secondary text-sm mt-1">
            {{ ucfirst($type) }} Attendance Report
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

                    <td class="p-4 text-center text-green-500">
                        {{ $data['present'] }}
                    </td>

                    <td class="p-4 text-center text-red-500">
                        {{ $data['absent'] }}
                    </td>

                    <td class="p-4 text-center">
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
                        No attendance records found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection