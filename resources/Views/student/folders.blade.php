@extends('layouts.app')
@section('title', 'Student - Folders')
@section('content')
<div class="mb-6">
    <a href="{{ route('landing') }}" class="text-accent hover:underline text-sm">← Back to Home</a>
    <h1 class="text-3xl font-bold mt-2">Select Your Folder</h1>
</div>

<div class="mb-8">
    <h2 class="text-2xl font-semibold mb-4">Class Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($folders as $folder)
        <a href="{{ route('student.report.show', ['id' => $folder->id, 'type' => 'daily']) }}" class="bg-surface border border-border rounded-xl p-6 card-hover transition-all block">
            <h3 class="font-semibold text-lg">{{ $folder->name }}</h3>
            <p class="text-secondary text-sm mt-2">Click to view your attendance →</p>
        </a>
        @endforeach
    </div>
</div>

<div>
    <h2 class="text-2xl font-semibold mb-4">Subject Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($subjects as $subject)
        <a href="{{ route('student.report.show', ['id' => $subject->id, 'type' => 'daily']) }}" class="bg-surface border border-border rounded-xl p-6 card-hover transition-all block">
            <h3 class="font-semibold text-lg">{{ $subject->name }}</h3>
            <p class="text-secondary text-sm mt-2">Click to view your attendance →</p>
        </a>
        @endforeach
    </div>
</div>
@endsection