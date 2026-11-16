@extends('layouts.app')

@section('title', 'Register Student')

@section('content')
    <div class="mb-6">
        <a href="{{ route('students.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">&larr; Back to students</a>
        <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">Register a student</h1>
        <p class="mt-2 text-slate-600">Add the student details and select an existing course.</p>
    </div>
    @include('students._form', ['student' => $student, 'courses' => $courses])
@endsection