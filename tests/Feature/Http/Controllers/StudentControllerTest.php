<?php

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Sequence;

test('shows the course choices on the student registration form', function () {
    $course = Course::factory()->create(['title' => 'Laravel Basics']);

    $this->get(route('students.create'))
        ->assertOk()
        ->assertSeeText('Laravel Basics');
});

test('registers a student without an optional phone number', function () {
    $course = Course::factory()->create(['title' => 'PHP Basics']);

    $response = $this->post(route('students.store'), [
        'name' => 'Mona Ali',
        'email' => 'mona@example.com',
        'course_id' => $course->id,
    ]);

    $response->assertRedirect(route('students.index'))
        ->assertSessionHas('success', 'Student registered successfully.');

    $student = Student::query()->where('email', 'mona@example.com')->firstOrFail();
    expect($student->phone)->toBeNull();
    expect($student->course_id)->toBe($course->id);
});

test('requires a student name', function () {
    $course = Course::factory()->create();

    $this->post(route('students.store'), [
        'email' => 'learner@example.com',
        'course_id' => $course->id,
    ])->assertSessionHasErrors(['name' => 'The name field is required.']);
});

test('requires a valid student email', function () {
    $course = Course::factory()->create();

    $this->post(route('students.store'), [
        'name' => 'Invalid Email',
        'email' => 'not-an-email',
        'course_id' => $course->id,
    ])->assertSessionHasErrors(['email' => 'The email field must be a valid email address.']);
});

test('requires the selected course to exist', function () {
    $this->post(route('students.store'), [
        'name' => 'No Course Learner',
        'email' => 'no-course@example.com',
        'course_id' => 999,
    ])->assertSessionHasErrors(['course_id' => 'The selected course id is invalid.']);
});

test('rejects an email already used by another student', function () {
    $course = Course::factory()->create();
    Student::factory()->for($course)->create(['email' => 'duplicate@example.com']);

    $this->post(route('students.store'), [
        'name' => 'Another Learner',
        'email' => 'duplicate@example.com',
        'course_id' => $course->id,
    ])->assertSessionHasErrors(['email' => 'The email has already been taken.']);
});

test('allows a student to keep their own email while updating', function () {
    $course = Course::factory()->create();
    $student = Student::factory()->for($course)->create([
        'name' => 'Before Update',
        'email' => 'same@example.com',
    ]);

    $this->put(route('students.update', $student), [
        'name' => 'After Update',
        'email' => 'same@example.com',
        'phone' => '',
        'course_id' => $course->id,
    ])->assertRedirect(route('students.index'))
        ->assertSessionHas('success', 'Student updated successfully.');

    $this->assertDatabaseHas('students', [
        'id' => $student->id,
        'name' => 'After Update',
        'email' => 'same@example.com',
    ]);
});

test('rejects changing a student to another students email', function () {
    $course = Course::factory()->create();
    $student = Student::factory()->for($course)->create(['email' => 'first@example.com']);
    Student::factory()->for($course)->create(['email' => 'second@example.com']);

    $this->put(route('students.update', $student), [
        'name' => $student->name,
        'email' => 'second@example.com',
        'phone' => $student->phone,
        'course_id' => $course->id,
    ])->assertSessionHasErrors(['email' => 'The email has already been taken.']);
});

test('updates a students course assignment', function () {
    $firstCourse = Course::factory()->create();
    $secondCourse = Course::factory()->create();
    $student = Student::factory()->for($firstCourse)->create();

    $this->put(route('students.update', $student), [
        'name' => $student->name,
        'email' => $student->email,
        'phone' => $student->phone,
        'course_id' => $secondCourse->id,
    ])->assertRedirect(route('students.index'));

    expect($student->fresh()->course_id)->toBe($secondCourse->id);
});

test('renders a students details page with the related course', function () {
    $course = Course::factory()->create(['title' => 'Student Course']);
    $student = Student::factory()->for($course)->create(['name' => 'Student Details']);

    $this->get(route('students.show', $student))
        ->assertOk()
        ->assertSeeText('Student Details')
        ->assertSeeText($student->email)
        ->assertSeeText('Student Course');
});

test('renders a students edit form with its current course selected', function () {
    $course = Course::factory()->create(['title' => 'Selected Course']);
    $student = Student::factory()->for($course)->create(['name' => 'Student To Edit']);

    $this->get(route('students.edit', $student))
        ->assertOk()
        ->assertSeeText('Student To Edit')
        ->assertSeeText('Selected Course')
        ->assertSee('value="'.$course->id.'" selected', false);
});

test('searches students by name', function () {
    $course = Course::factory()->create();
    Student::factory()->for($course)->create(['name' => 'Sara Mansour']);
    Student::factory()->for($course)->create(['name' => 'Omar Nabil']);

    $this->get(route('students.index', ['search' => 'Sara']))
        ->assertOk()
        ->assertSeeText('Sara Mansour')
        ->assertDontSeeText('Omar Nabil');
});

test('filters students by course', function () {
    $firstCourse = Course::factory()->create(['title' => 'Course Alpha']);
    $secondCourse = Course::factory()->create(['title' => 'Course Beta']);
    Student::factory()->for($firstCourse)->create(['name' => 'Alpha Student']);
    Student::factory()->for($secondCourse)->create(['name' => 'Beta Student']);

    $this->get(route('students.index', ['course_id' => $firstCourse->id]))
        ->assertOk()
        ->assertSeeText('Alpha Student')
        ->assertDontSeeText('Beta Student');
});

test('shows ten students per page and retains a second page', function () {
    $course = Course::factory()->create();
    Student::factory()
        ->count(11)
        ->sequence(fn (Sequence $sequence) => [
            'name' => sprintf('Student %02d', $sequence->index + 1),
        ])
        ->for($course)
        ->create();

    $this->get(route('students.index', ['page' => 2]))
        ->assertOk()
        ->assertSeeText('Student 11')
        ->assertDontSeeText('Student 01');
});

test('deletes a student and keeps the course', function () {
    $course = Course::factory()->create();
    $student = Student::factory()->for($course)->create();

    $this->delete(route('students.destroy', $student))
        ->assertRedirect(route('students.index'))
        ->assertSessionHas('success', 'Student deleted successfully.');

    $this->assertModelMissing($student);
    $this->assertModelExists($course);
});

test('escapes student names before rendering them in HTML', function () {
    $course = Course::factory()->create();
    $payload = '<script>alert(1)</script>';
    Student::factory()->for($course)->create(['name' => $payload]);

    $this->get(route('students.index'))
        ->assertOk()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
        ->assertDontSee($payload, false);
});
