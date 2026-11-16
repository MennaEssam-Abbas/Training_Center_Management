@extends('layouts.app')

@section('title', $course->title)

@section('content')
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">&larr; Back to courses</a>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">{{ $course->title }}</h1>
            <p class="mt-2 text-slate-600">{{ $course->description ?: 'No description provided.' }}</p>
        </div>
        <a href="{{ route('courses.edit', $course) }}" class="rounded-lg bg-indigo-600 px-5 py-3 text-center text-sm font-semibold text-white hover:bg-indigo-700">Edit course</a>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5"><p class="text-sm text-slate-500">Price</p><p class="mt-2 text-xl font-bold">{{ number_format((float) $course->price, 2) }}</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-5"><p class="text-sm text-slate-500">Duration</p><p class="mt-2 text-xl font-bold">{{ $course->duration }} hours</p></div>
        <div class="rounded-xl border border-slate-200 bg-white p-5"><p class="text-sm text-slate-500">Registered students</p><p class="mt-2 text-xl font-bold">{{ $course->students->count() }}</p></div>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4"><h2 class="font-semibold text-slate-900">Students in this course</h2></div>
        <div class="divide-y divide-slate-100">
            @forelse ($course->students as $student)
                <a href="{{ route('students.show', $student) }}" class="flex flex-col justify-between gap-1 px-6 py-4 hover:bg-slate-50 sm:flex-row sm:items-center">
                    <span class="font-medium text-slate-900">{{ $student->name }}</span>
                    <span class="text-sm text-slate-500">{{ $student->email }}{{ $student->phone ? ' - '.$student->phone : '' }}</span>
                </a>
            @empty
                <p class="px-6 py-10 text-center text-sm text-slate-500">No students are registered in this course yet.</p>
            @endforelse
        </div>
    </section>
@endsection