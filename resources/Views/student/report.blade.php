@extends('layouts.app')

@section('title', 'My Attendance Report')

@section('content')

<div class="mb-6">
    <a href="{{ route('student.folders') }}"
       class="text-accent hover:underline text-sm">
        ← Back to Folders
    </a>

    <h1 class="text-3xl font-bold mt-2">
        {{ $folder->name }} - My Attendance
    </h1>
</div>


<!-- Search -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6">

    <form method="GET"
          action="{{ route('student.report.show', ['id' => $folder->id]) }}"
          class="flex gap-4">

        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="start" value="{{ $start }}">
        <input type="hidden" name="end" value="{{ $end }}">

        <input
            type="text"
            name="search"
            placeholder="Search your name, roll, branch..."
            value="{{ request('search') }}"
            class="flex-1 border border-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent outline-none"
        >

        <button
            type="submit"
            class="bg-accent hover:bg-accentHover text-white px-6 py-2 rounded-lg">
            Search
        </button>

    </form>

</div>


<!-- Filter Tabs -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6 flex gap-4 flex-wrap">

    <!-- Daily -->
    <a
        href="{{ route('student.report.show', [
            'id' => $folder->id,
            'type' => 'daily',
            'date' => \Carbon\Carbon::today()->toDateString()
        ]) }}"
        class="px-4 py-2 rounded-lg
        {{ $type === 'daily'
            ? 'bg-accent text-white'
            : 'bg-bg text-secondary hover:bg-bg/70' }}">
        Daily
    </a>


    <!-- Monthly -->
    <a
        href="{{ route('student.report.show', [
            'id' => $folder->id,
            'type' => 'monthly',
            'month' => \Carbon\Carbon::today()->format('Y-m')
        ]) }}"
        class="px-4 py-2 rounded-lg
        {{ $type === 'monthly'
            ? 'bg-accent text-white'
            : 'bg-bg text-secondary hover:bg-bg/70' }}">
        Monthly
    </a>


    <!-- Custom -->
    <a
        href="{{ route('student.report.show', [
            'id' => $folder->id,
            'type' => 'custom',
            'start' => \Carbon\Carbon::today()->startOfMonth()->toDateString(),
            'end' => \Carbon\Carbon::today()->endOfMonth()->toDateString()
        ]) }}"
        class="px-4 py-2 rounded-lg
        {{ $type === 'custom'
            ? 'bg-accent text-white'
            : 'bg-bg text-secondary hover:bg-bg/70' }}">
        Custom
    </a>


    <!-- PDF -->
    <a
        href="{{ route('student.report.pdf', [
            'id' => $folder->id,
            'type' => $type,
            'date' => $date,
            'month' => $month,
            'start' => $start,
            'end' => $end
        ]) }}"
        class="ml-auto px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger/90">
        📄 Download PDF
    </a>

</div>


<!-- No Records -->
@if(count($studentsData) === 0)

    <div class="bg-surface border border-border rounded-xl p-10 text-center">

        <div class="text-5xl mb-4">
            📊
        </div>

        <h2 class="text-xl font-semibold mb-2">
            No Attendance Records
        </h2>

        <p class="text-secondary">
            No records found. Try a different search or date range.
        </p>

    </div>

@else


    <!-- Report Table -->
    <div class="bg-surface border border-border rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-bg border-b border-border">

                    <tr>

                        <th class="text-left p-4 text-secondary font-medium">
                            Name
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Roll
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Branch
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Present
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Absent
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            This Period %
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Overall Avg %
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($studentsData as $data)

                        <tr class="border-b border-border">

                            <td class="p-4">
                                {{ $data['student']->name }}
                            </td>

                            <td class="p-4">
                                {{ $data['student']->roll_number }}
                            </td>

                            <td class="p-4">
                                {{ $data['student']->branch }}
                            </td>

                            <td class="p-4 text-center text-success">
                                {{ $data['present'] }}
                            </td>

                            <td class="p-4 text-center text-danger">
                                {{ $data['absent'] }}
                            </td>

                            <td class="p-4 text-center font-semibold">
                                {{ $data['percentage'] }}%
                            </td>

                            <td class="p-4 text-center font-semibold text-accent">
                                {{ $data['avg_percentage'] }}%
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endif

@endsection