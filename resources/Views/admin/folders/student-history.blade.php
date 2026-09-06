@extends('layouts.app')

@section('title', 'Attendance History - ' . $student->name)

@section('content')

<div class="mb-6">

    <div class="flex flex-wrap gap-2 mb-5">

        <a href="{{ route('admin.folders.show', $folder->id) }}"
           class="px-4 py-2 rounded-lg bg-bg hover:bg-accent hover:text-white">
            Folder
        </a>

        <a href="{{ route('admin.students.index', $folder->id) }}"
           class="px-4 py-2 rounded-lg bg-bg hover:bg-accent hover:text-white">
            Students
        </a>

        <a href="{{ route('admin.attendance.show', $folder->id) }}"
           class="px-4 py-2 rounded-lg bg-bg hover:bg-accent hover:text-white">
            Mark Attendance
        </a>

        <a href="{{ route('admin.report.show', ['id' => $folder->id, 'type' => 'daily']) }}"
           class="px-4 py-2 rounded-lg bg-bg hover:bg-accent hover:text-white">
            Report
        </a>

    </div>


    <h1 class="text-3xl font-bold">
        Attendance History
    </h1>

    <p class="text-secondary mt-1">
        {{ $student->name }}
        | Roll: {{ $student->roll_number }}
    </p>

</div>


@if(session('success'))
    <div class="bg-success/10 border border-success text-success px-4 py-3 rounded-lg mb-5">
        {{ session('success') }}
    </div>
@endif


@if(session('error'))
    <div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-lg mb-5">
        {{ session('error') }}
    </div>
@endif


<div class="bg-surface border border-border rounded-xl p-6 mb-6">

    <h2 class="font-bold text-lg mb-4">
        Add / Change Previous Attendance
    </h2>

    <form
        method="POST"
        action="{{ route('admin.attendance.history.save', [
            'folderId' => $folder->id,
            'studentId' => $student->id
        ]) }}"
        class="flex flex-wrap gap-4 items-end">

        @csrf

        <div>

            <label class="block font-medium mb-2">
                Date
            </label>

            <input
                type="date"
                name="date"
                required
                min="{{ $student->created_at?->format('Y-m-d') }}"
                max="{{ now()->toDateString() }}"
                class="border border-border rounded-lg px-4 py-3"
            >

        </div>


        <div>

            <label class="block font-medium mb-2">
                Status
            </label>

            <select
                name="status"
                required
                class="border border-border rounded-lg px-4 py-3">

                <option value="present">
                    Present
                </option>

                <option value="absent">
                    Absent
                </option>

            </select>

        </div>


        <button
            type="submit"
            class="bg-accent text-white px-6 py-3 rounded-lg font-semibold">

            Save Attendance

        </button>

    </form>

</div>


<div class="bg-surface border border-border rounded-xl overflow-hidden">

    <div class="p-5 border-b border-border">

        <h2 class="font-bold text-lg">
            Previous Attendance Records
        </h2>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-bg">

                <tr>

                    <th class="p-4 text-left">
                        Date
                    </th>

                    <th class="p-4 text-center">
                        Status
                    </th>

                    <th class="p-4 text-left">
                        Marked At
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($attendances as $attendance)

                    <tr class="border-t border-border">

                        <td class="p-4">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                        </td>


                        <td class="p-4 text-center">

                            @if($attendance->status === 'present')

                                <span class="px-3 py-1 rounded-full bg-success/10 text-success font-semibold">
                                    Present
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-danger/10 text-danger font-semibold">
                                    Absent
                                </span>

                            @endif

                        </td>


                        <td class="p-4 text-secondary">

                            {{ $attendance->marked_at
                                ? $attendance->marked_at->format('d M Y h:i A')
                                : '-' }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="3"
                            class="p-10 text-center text-secondary">

                            No attendance records found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection