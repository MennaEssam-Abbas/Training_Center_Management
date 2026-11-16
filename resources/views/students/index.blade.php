@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-600">Training Center</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Students</h1>
            <p class="mt-2 text-slate-600">Search, filter, and manage student registrations.</p>
        </div>
        <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Register student</a>
    </div>

    <form method="GET" action="{{ route('students.index') }}" class="mb-6 grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-[1fr_1fr_auto_auto] sm:items-end">
        <div>
            <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">Search by name</label>
            <input id="search" name="search" type="search" value="{{ $search }}" placeholder="Enter a student name" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        </div>
        <div>
            <label for="course_id" class="mb-2 block text-sm font-semibold text-slate-700">Filter by course</label>
            <select id="course_id" name="course_id" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                <option value="">All courses</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected($courseId === $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">Apply filters</button>
        <a href="{{ route('students.index') }}" class="rounded-lg px-4 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-100">Clear</a>
    </form>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-4">Name</th><th class="px-5 py-4">Email</th><th class="px-5 py-4">Phone</th><th class="px-5 py-4">Course</th><th class="px-5 py-4">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $student->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $student->email }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $student->phone ?: '-' }}</td>
                            <td class="px-5 py-4"><a href="{{ route('courses.show', $student->course) }}" class="font-medium text-indigo-700 hover:text-indigo-900">{{ $student->course->title }}</a></td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 whitespace-nowrap">
                                    <a href="{{ route('students.show', $student) }}" class="font-semibold text-slate-600 hover:text-slate-900">View</a>
                                    <a href="{{ route('students.edit', $student) }}" class="font-semibold text-indigo-700 hover:text-indigo-900">Edit</a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">No students match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $students->links() }}</div>
        @endif
    </section>
@endsection