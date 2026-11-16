@extends('layouts.app')

@section('title', 'Add Course')

@section('content')
    <div class="mb-6">
        <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">&larr; Back to courses</a>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">Add a course</h1>
        <p class="mt-2 text-slate-600">Enter the course details below.</p>
    </div>
    @include('courses._form', ['course' => $course])
@endsection