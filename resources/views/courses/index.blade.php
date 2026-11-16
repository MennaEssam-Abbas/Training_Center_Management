@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Training Center</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Courses</h1>
            <p class="mt-2 text-slate-600">Manage the courses offered by your training center.</p>
        </div>
        <a href="{{ route('courses.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Add course</a>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="font-semibold text-slate-900">All courses</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $courses->count() }} {{ $courses->count() === 1 ? 'course' : 'courses' }} in total</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Course</th>
                        <th class="px-5 py-4">Price</th>
                        <th class="px-5 py-4">Duration</th>
                        <th class="px-5 py-4">Students</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($courses as $course)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <a href="{{ route('courses.show', $course) }}" class="font-semibold text-slate-900 hover:text-indigo-700">{{ $course->title }}</a>
                                <p class="mt-1 max-w-sm text-slate-500">{{ $course->description ?: 'No description provided.' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ number_format((float) $course->price, 2) }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $course->duration }} hours</td>
                            <td class="px-5 py-4 text-slate-600">{{ $course->students_count }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 whitespace-nowrap">
                                    <a href="{{ route('courses.edit', $course) }}" class="font-semibold text-indigo-700 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="font-semibold text-slate-700">No courses yet</p>
                                <p class="mt-1 text-sm text-slate-500">Add your first course to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection