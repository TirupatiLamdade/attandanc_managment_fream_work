@extends('layouts.app')
@section('title', 'Welcome')
@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl font-bold mb-8 text-primary">Attendance Management System</h1>
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <a href="{{ route('admin.login') }}" class="bg-surface border border-border rounded-xl p-8 card-hover transition-all glow-accent">
                <div class="text-accent text-5xl mb-4">👨‍💼</div>
                <h2 class="text-xl font-semibold mb-2">Admin Login</h2>
                <p class="text-secondary text-sm">Manage folders, students & reports</p>
            </a>
            <a href="{{ route('staff.login') }}" class="bg-surface border border-border rounded-xl p-8 card-hover transition-all glow-accent">
                <div class="text-accent text-5xl mb-4">👨‍🏫</div>
                <h2 class="text-xl font-semibold mb-2">Staff Login</h2>
                <p class="text-secondary text-sm">Mark daily attendance</p>
            </a>
            <a href="{{ route('student.folders') }}" class="bg-surface border border-border rounded-xl p-8 card-hover transition-all glow-accent">
                <div class="text-accent text-5xl mb-4">👨‍🎓</div>
                <h2 class="text-xl font-semibold mb-2">Student Access</h2>
                <p class="text-secondary text-sm">View your attendance report</p>
            </a>
        </div>
    </div>
</div>
@endsection