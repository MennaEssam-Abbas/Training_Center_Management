<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->withCount('students')
            ->orderBy('title')
            ->get();

        return view('courses.index', ['courses' => $courses]);
    }

    public function create(): View
    {
        return view('courses.create', ['course' => new Course]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course): View
    {
        $course->load(['students' => fn ($query) => $query->orderBy('name')]);

        return view('courses.show', ['course' => $course]);
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', ['course' => $course]);
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->students()->exists()) {
            return redirect()->route('courses.index')->with('error', 'This course cannot be deleted while students are registered in it.');
        }

        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
