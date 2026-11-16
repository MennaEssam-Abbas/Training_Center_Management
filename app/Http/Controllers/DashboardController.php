<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'courseCount' => Course::count(),
            'studentCount' => Student::count(),
            'recentStudents' => Student::query()->with('course')->latest()->limit(5)->get(),
        ]);
    }
}
