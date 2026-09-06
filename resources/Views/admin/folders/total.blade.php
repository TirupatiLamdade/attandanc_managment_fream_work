@extends('layouts.app')
@section('title', 'Total - ' . $folder->name)
@section('content')
<div class="mb-6">
    <a href="{{ route('admin.folders.show', $folder->id) }}" class="text-accent hover:underline text-sm">← Back to Folder</a>
    <h1 class="text-3xl font-bold mt-2">Today's Summary - {{ $folder->name }}</h1>
    <p class="text-secondary mt-1">{{ \Carbon\Carbon::today()->format('d M, Y') }}</p>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="bg-surface border border-border rounded-xl p-8 text-center glow-accent">
        <div class="text-4xl mb-2">👥</div>
        <div class="text-3xl font-bold text-primary">{{ $total }}</div>
        <div class="text-secondary mt-1">Total Students</div>
    </div>
    <div class="bg-surface border border-success rounded-xl p-8 text-center glow-accent">
        <div class="text-4xl mb-2">✅</div>
        <div class="text-3xl font-bold text-success">{{ $present }}</div>
        <div class="text-secondary mt-1">Present</div>
    </div>
    <div class="bg-surface border border-danger rounded-xl p-8 text-center glow-accent">
        <div class="text-4xl mb-2">❌</div>
        <div class="text-3xl font-bold text-danger">{{ $absent }}</div>
        <div class="text-secondary mt-1">Absent</div>
    </div>
</div>

@if($total > 0)
<div class="mt-8 bg-surface border border-border rounded-xl p-6">
    <h3 class="font-semibold mb-4">Present Percentage</h3>
    <div class="w-full bg-bg rounded-full h-4">
        <div class="bg-success h-4 rounded-full" style="width: {{ ($present / $total) * 100 }}%"></div>
    </div>
    <p class="text-center mt-2 text-secondary">{{ round(($present / $total) * 100, 2) }}% Present</p>
</div>
@endif
@endsection