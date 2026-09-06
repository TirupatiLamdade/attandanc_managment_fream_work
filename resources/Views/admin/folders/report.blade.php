@extends('layouts.app')

@section('title', 'Report - ' . $folder->name)

@section('content')

<div class="mb-6">

    <a
        href="{{ route('admin.folders.show', $folder->id) }}"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Folder
    </a>

    <h1 class="text-3xl font-bold mt-3">
        Attendance Report
    </h1>

    <p class="text-secondary">
        {{ $folder->name }}
    </p>

</div>


@if(session('error'))

<div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-xl mb-5">
    {{ session('error') }}
</div>

@endif


{{-- Report type --}}

<div class="bg-surface border border-border rounded-xl p-5 mb-6">

    <div class="flex gap-3 flex-wrap">

        <a
            href="{{ route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'daily',
                'date' => now()->format('Y-m-d')
            ]) }}"
            class="px-5 py-3 rounded-lg font-semibold
            {{ $type === 'daily'
                ? 'bg-accent text-white'
                : 'bg-bg text-secondary' }}"
        >
            Daily
        </a>


        <a
            href="{{ route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'monthly',
                'month' => now()->format('Y-m')
            ]) }}"
            class="px-5 py-3 rounded-lg font-semibold
            {{ $type === 'monthly'
                ? 'bg-accent text-white'
                : 'bg-bg text-secondary' }}"
        >
            Monthly
        </a>


        <a
            href="{{ route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'custom'
            ]) }}"
            class="px-5 py-3 rounded-lg font-semibold
            {{ $type === 'custom'
                ? 'bg-accent text-white'
                : 'bg-bg text-secondary' }}"
        >
            Custom
        </a>

    </div>

</div>


{{-- DAILY --}}

@if($type === 'daily')

<form
    method="GET"
    class="bg-surface border border-border rounded-xl p-5 mb-6"
>

    <input
        type="hidden"
        name="type"
        value="daily"
    >

    <div class="flex flex-col md:flex-row gap-4 items-end">

        <div>

            <label class="block text-sm font-semibold mb-2">
                Select Date
            </label>

            <input
                type="date"
                name="date"
                value="{{ $date }}"
                max="{{ now()->format('Y-m-d') }}"
                class="border border-border rounded-lg px-4 py-3 bg-bg"
            >

        </div>


        <button
            class="bg-accent text-white px-6 py-3 rounded-lg font-semibold"
        >
            View Report
        </button>


        <a
            href="{{ route('admin.report.pdf', [
                'id' => $folder->id,
                'type' => 'daily',
                'date' => $date
            ]) }}"
            class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
        >
            📄 PDF
        </a>

    </div>

</form>

@endif


{{-- MONTHLY --}}

@if($type === 'monthly')

<form
    method="GET"
    class="bg-surface border border-border rounded-xl p-5 mb-6"
>

    <input
        type="hidden"
        name="type"
        value="monthly"
    >

    <div class="flex flex-col md:flex-row gap-4 items-end">

        <div>

            <label class="block text-sm font-semibold mb-2">
                Select Month
            </label>

            <input
                type="month"
                name="month"
                value="{{ $month }}"
                class="border border-border rounded-lg px-4 py-3 bg-bg"
            >

        </div>


        <button
            class="bg-accent text-white px-6 py-3 rounded-lg font-semibold"
        >
            View Report
        </button>


        <a
            href="{{ route('admin.report.pdf', [
                'id' => $folder->id,
                'type' => 'monthly',
                'month' => $month
            ]) }}"
            class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
        >
            📄 PDF
        </a>

    </div>

</form>

@endif


{{-- CUSTOM --}}

@if($type === 'custom')

<form
    method="GET"
    class="bg-surface border border-border rounded-xl p-5 mb-6"
>

    <input
        type="hidden"
        name="type"
        value="custom"
    >

    <div class="grid md:grid-cols-3 gap-4 items-end">

        <div>

            <label class="block text-sm font-semibold mb-2">
                From Date
            </label>

            <input
                type="date"
                name="start"
                id="startDate"
                value="{{ $start }}"
                max="{{ now()->format('Y-m-d') }}"
                class="w-full border border-border rounded-lg px-4 py-3 bg-bg"
                required
            >

        </div>


        <div>

            <label class="block text-sm font-semibold mb-2">
                To Date
            </label>

            <input
                type="date"
                name="end"
                id="endDate"
                value="{{ $end }}"
                max="{{ now()->format('Y-m-d') }}"
                class="w-full border border-border rounded-lg px-4 py-3 bg-bg"
                required
            >

        </div>


        <div class="flex gap-2">

            <button
                class="bg-accent text-white px-6 py-3 rounded-lg font-semibold"
            >
                View Report
            </button>

            <a
                href="{{ route('admin.report.pdf', [
                    'id' => $folder->id,
                    'type' => 'custom',
                    'start' => $start,
                    'end' => $end
                ]) }}"
                class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
            >
                PDF
            </a>

        </div>

    </div>

</form>

<script>

document
    .querySelector('form')
    ?.addEventListener(
        'submit',
        function(event) {

            const start =
                document.getElementById(
                    'startDate'
                )?.value;

            const end =
                document.getElementById(
                    'endDate'
                )?.value;

            if (
                start &&
                end &&
                start > end
            ) {

                event.preventDefault();

                alert(
                    'From Date cannot be after To Date.'
                );

            }

        }
    );

</script>

@endif


{{-- Search --}}

<div class="bg-surface border border-border rounded-xl p-5 mb-5">

    <label class="block text-sm font-semibold mb-2">
        Search Student
    </label>

    <input
        type="text"
        id="reportSearch"
        placeholder="Search name, roll, branch, mobile..."
        class="w-full md:w-1/2 border border-border rounded-lg px-4 py-3 bg-bg"
        onkeyup="searchReport()"
    >

</div>


{{-- REPORT TABLE --}}

<div class="bg-surface border border-border rounded-xl overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-bg border-b border-border">

                <tr>

                    <th class="p-4 text-left">
                        S.No
                    </th>

                    <th class="p-4 text-left">
                        Name
                    </th>

                    <th class="p-4 text-left">
                        Roll
                    </th>

                    <th class="p-4 text-left">
                        Branch
                    </th>

                    <th class="p-4 text-left">
                        Mobile
                    </th>

                    @if($type === 'daily')

                        <th class="p-4 text-center">
                            Status
                        </th>

                    @else

                        <th class="p-4 text-center">
                            Total Days
                        </th>

                        <th class="p-4 text-center">
                            Present
                        </th>

                        <th class="p-4 text-center">
                            Absent
                        </th>

                        <th class="p-4 text-center">
                            Not Marked
                        </th>

                        <th class="p-4 text-center">
                            Percentage
                        </th>

                    @endif

                </tr>

            </thead>


            <tbody id="reportTableBody">

            @foreach($studentsData as $data)

                <tr
                    class="report-row border-b border-border"
                    data-search="{{ strtolower(
                        $data['name'] . ' ' .
                        $data['roll'] . ' ' .
                        $data['branch'] . ' ' .
                        $data['phone']
                    ) }}"
                >

                    <td class="p-4">
                        {{ $data['serno'] }}
                    </td>

                    <td class="p-4 font-semibold">
                        {{ $data['name'] }}
                    </td>

                    <td class="p-4">
                        {{ $data['roll'] }}
                    </td>

                    <td class="p-4">
                        {{ $data['branch'] }}
                    </td>

                    <td class="p-4">
                        {{ $data['phone'] }}
                    </td>


                    @if($type === 'daily')

                        <td class="p-4 text-center">

                            @if($data['daily_status'] === 'Present')

                                <span class="text-success font-bold">
                                    ✅ Present
                                </span>

                            @elseif($data['daily_status'] === 'Absent')

                                <span class="text-danger font-bold">
                                    ❌ Absent
                                </span>

                            @elseif($data['daily_status'] === 'Not Applicable')

                                <span class="text-secondary font-semibold">
                                    Not Applicable
                                </span>

                            @else

                                <span class="text-secondary font-semibold">
                                    Not Attendance Marked
                                </span>

                            @endif

                        </td>

                    @else

                        <td class="p-4 text-center">
                            {{ $data['total_days'] }}
                        </td>

                        <td class="p-4 text-center text-success font-bold">
                            {{ $data['present'] }}
                        </td>

                        <td class="p-4 text-center text-danger font-bold">
                            {{ $data['absent'] }}
                        </td>

                        <td class="p-4 text-center text-secondary">
                            {{ $data['not_marked'] }}
                        </td>

                        <td class="p-4 text-center font-bold">
                            {{ $data['percentage'] }}%
                        </td>

                    @endif

                </tr>

            @endforeach


            @if(count($studentsData) === 0)

                <tr>

                    <td
                        colspan="10"
                        class="p-10 text-center text-secondary"
                    >
                        No students found.
                    </td>

                </tr>

            @endif

            </tbody>

        </table>

    </div>

</div>


@if($type === 'daily' && count($studentsData) > 0)

@php

    $hasAttendance =
        collect($studentsData)
            ->contains(function ($item) {

                return in_array(
                    $item['daily_status'],
                    ['Present', 'Absent'],
                    true
                );

            });

@endphp


@if(!$hasAttendance)

<div class="mt-5 bg-danger/10 border border-danger text-danger px-5 py-4 rounded-xl">

    ⚠️ No attendance marked for any student on this date.

</div>

@endif

@endif


<script>

function searchReport() {

    const input =
        document
            .getElementById('reportSearch')
            .value
            .toLowerCase()
            .trim();

    document
        .querySelectorAll('.report-row')
        .forEach(row => {

            const text =
                row
                    .getAttribute('data-search')
                    .toLowerCase();

            row.style.display =
                text.includes(input)
                    ? ''
                    : 'none';

        });

}

</script>

@endsection