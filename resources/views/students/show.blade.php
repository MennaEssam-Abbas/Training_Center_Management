@extends('layouts.app')

@section('title', $student->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('students.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">&larr; Back to students</a>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ $student->name }}</h1>
        <p class="mt-2 text-slate-600">Student registration details.</p>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <dl class="grid gap-6 sm:grid-cols-2">
            <div><dt class="text-sm font-medium text-slate-500">Email</dt><dd class="mt-1 font-semibold text-slate-900">{{ $student->email }}</dd></div>
            <div><dt class="text-sm font-medium text-slate-500">Phone</dt><dd class="mt-1 font-semibold text-slate-900">{{ $student->phone ?: 'Not provided' }}</dd></div>
            <div><dt class="text-sm font-medium text-slate-500">Course</dt><dd class="mt-1"><a href="{{ route('courses.show', $student->course) }}" class="font-semibold text-indigo-700 hover:text-indigo-900">{{ $student->course->title }}</a></dd></div>
            <div><dt class="text-sm font-medium text-slate-500">Registered on</dt><dd class="mt-1 font-semibold text-slate-900">{{ $student->created_at->format('M j, Y') }}</dd></div>
        </dl>
        <div class="mt-6 border-t border-slate-100 pt-5">
            <a href="{{ route('students.edit', $student) }}" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Edit student</a>
        </div>
    </section>
@endsection