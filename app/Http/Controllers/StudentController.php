<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $courseId = $request->integer('course_id');

        $query = Student::query()->with('course');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($courseId > 0) {
            $query->where('course_id', $courseId);
        }

        $students = $query->orderBy('name')->paginate(10)->withQueryString();
        $courses = Course::query()->orderBy('title')->get();

        return view('students.index', [
            'students' => $students,
            'courses' => $courses,
            'search' => $search,
            'courseId' => $courseId,
        ]);
    }

    public function create(): View
    {
        return view('students.create', [
            'student' => new Student,
            'courses' => Course::query()->orderBy('title')->get(),
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        Student::create($request->validated());

        return redirect()->route('students.index')->with('success', 'Student registered successfully.');
    }

    public function show(Student $student): View
    {
        $student->load('course');

        return view('students.show', ['student' => $student]);
    }

    public function edit(Student $student): View
    {
        return view('students.edit', [
            'student' => $student,
            'courses' => Course::query()->orderBy('title')->get(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
