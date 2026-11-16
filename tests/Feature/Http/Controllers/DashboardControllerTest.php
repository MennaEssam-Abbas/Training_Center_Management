<?php

use App\Models\Course;
use App\Models\Student;

test('shows course and student totals on the dashboard', function () {
    $course = Course::factory()->create();
    Student::factory()->for($course)->create(['name' => 'Recent Student']);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Training center dashboard')
        ->assertSeeText('Total courses')
        ->assertSeeText('Registered students')
        ->assertSeeText('Recent Student')
        ->assertSeeText($course->title);
});
