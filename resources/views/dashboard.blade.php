@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Overview</p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Training center dashboard</h1>
        <p class="mt-2 text-slate-600">A quick view of your courses and student registrations.</p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <a href="{{ route('courses.index') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <p class="text-sm font-medium text-slate-500">Total courses</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $courseCount }}</p>
            <p class="mt-2 text-sm font-semibold text-indigo-700">Manage courses</p>
        </a>
        <a href="{{ route('students.index') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
            <p class="text-sm font-medium text-slate-500">Registered students</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $studentCount }}</p>
            <p class="mt-2 text-sm font-semibold text-indigo-700">Manage students</p>
        </a>
    </div>

    <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Recent students</h2>
                <p class="mt-1 text-sm text-slate-500">The latest five registrations.</p>
            </div>
            <a href="{{ route('students.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">View all</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($recentStudents as $student)
                <a href="{{ route('students.show', $student) }}" class="flex flex-col justify-between gap-1 px-6 py-4 hover:bg-slate-50 sm:flex-row sm:items-center">
                    <span class="font-medium text-slate-900">{{ $student->name }}</span>
                    <span class="text-sm text-slate-500">{{ $student->course->title }} - {{ $student->email }}</span>
                </a>
            @empty
                <p class="px-6 py-10 text-center text-sm text-slate-500">No students have been registered yet.</p>
            @endforelse
        </div>
    </section>
@endsection