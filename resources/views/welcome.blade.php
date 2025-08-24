@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="flex items-center justify-center min-h-full">
        <div class="text-center px-4">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                Welcome to {{ config('app.name', 'XPTrackr') }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                Track your habits and level up!
            </p>
            @guest
                <a href="/login" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Get Started
                </a>
            @endguest
        </div>
    </div>
@endsection
