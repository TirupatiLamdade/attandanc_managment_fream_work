@extends('layouts.app')
@section('title', 'Staff - Folders')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold">Select Folder for Attendance</h1>
    <form method="POST" action="{{ route('staff.logout') }}">
        @csrf
        <button class="bg-danger hover:bg-danger/90 text-white px-4 py-2 rounded-lg">Logout</button>
    </form>
</div>

@if(session('success'))
    <div class="bg-success/10 border border-success text-success px-4 py-2 rounded mb-4">{{ session('success') }}</div>
@endif

<div class="mb-8">
    <h2 class="text-2xl font-semibold mb-4">Class Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($folders as $folder)
        <a href="{{ route('staff.attendance.show', $folder->id) }}" class="bg-surface border border-border rounded-xl p-6 card-hover transition-all block">
            <h3 class="font-semibold text-lg">{{ $folder->name }}</h3>
            <p class="text-secondary text-sm mt-2">Click to mark attendance →</p>
        </a>
        @endforeach
    </div>
</div>

<div>
    <h2 class="text-2xl font-semibold mb-4">Subject Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($subjects as $subject)
        <a href="{{ route('staff.attendance.show', $subject->id) }}" class="bg-surface border border-border rounded-xl p-6 card-hover transition-all block">
            <h3 class="font-semibold text-lg">{{ $subject->name }}</h3>
            <p class="text-secondary text-sm mt-2">Click to mark attendance →</p>
        </a>
        @endforeach
    </div>
</div>
@endsection