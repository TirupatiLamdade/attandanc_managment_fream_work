@extends('layouts.app')
@section('title', 'Admin Login')
@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-surface border border-border rounded-xl p-8 w-full max-w-md glow-accent">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
        @if($errors->any())
            <div class="bg-danger/10 border border-danger text-danger px-4 py-2 rounded mb-4">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-secondary text-sm mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:border-accent outline-none" required>
            </div>
            <div class="mb-6">
                <label class="block text-secondary text-sm mb-2">Password</label>
                <input type="password" name="password" class="w-full border border-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent focus:border-accent outline-none" required>
            </div>
            <button type="submit" class="w-full bg-accent hover:bg-accentHover text-white font-semibold py-3 rounded-lg glow-accent transition-all">Login</button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('landing') }}" class="text-secondary text-sm hover:text-accent">← Back to Home</a>
        </div>
    </div>
</div>
@endsection