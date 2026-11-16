<?php

use App\Models\Course;
use App\Models\Student;

test('lists courses with their registered student counts', function () {
    $course = Course::factory()->create(['title' => 'Laravel Fundamentals']);
    Student::factory()->count(2)->for($course)->create();

    $this->get(route('courses.index'))
        ->assertOk()
        ->assertSeeText('Laravel Fundamentals')
        ->assertSeeText('2');
});

test('renders the course creation form', function () {
    $this->get(route('courses.create'))
        ->assertOk()
        ->assertSeeText('Add a course')
        ->assertSee('name="title"', false)
        ->assertSee('name="duration"', false);
});

test('creates a course and redirects with a success message', function () {
    $response = $this->post(route('courses.store'), [
        'title' => 'PHP Fundamentals',
        'description' => 'A short introduction to PHP.',
        'price' => '250.50',
        'duration' => '24',
    ]);

    $response->assertRedirect(route('courses.index'))
        ->assertSessionHas('success', 'Course created successfully.');

    $this->assertDatabaseHas('courses', [
        'title' => 'PHP Fundamentals',
        'description' => 'A short introduction to PHP.',
        'duration' => 24,
    ]);
});

test('allows a course price of zero', function () {
    $this->post(route('courses.store'), [
        'title' => 'Free Orientation',
        'price' => '0',
        'duration' => '1',
    ])->assertRedirect(route('courses.index'));

    $this->assertDatabaseHas('courses', ['title' => 'Free Orientation']);
});

test('requires a course title', function () {
    $this->post(route('courses.store'), [
        'price' => '100',
        'duration' => '4',
    ])->assertSessionHasErrors(['title' => 'The title field is required.']);
});

test('rejects a negative course price', function () {
    $this->post(route('courses.store'), [
        'title' => 'Invalid Price Course',
        'price' => '-1',
        'duration' => '4',
    ])->assertSessionHasErrors(['price' => 'The price field must be at least 0.']);
});

test('requires course duration to be a positive integer', function () {
    $this->post(route('courses.store'), [
        'title' => 'Invalid Duration Course',
        'price' => '100',
        'duration' => '0',
    ])->assertSessionHasErrors(['duration' => 'The duration field must be at least 1.']);
});

test('rejects a fractional course duration', function () {
    $this->post(route('courses.store'), [
        'title' => 'Fractional Duration Course',
        'price' => '100',
        'duration' => '1.5',
    ])->assertSessionHasErrors(['duration' => 'The duration field must be an integer.']);
});

test('updates course information', function () {
    $course = Course::factory()->create(['title' => 'Old Title']);

    $response = $this->put(route('courses.update', $course), [
        'title' => 'Updated Title',
        'description' => 'Updated description.',
        'price' => '375.00',
        'duration' => '30',
    ]);

    $response->assertRedirect(route('courses.index'))
        ->assertSessionHas('success', 'Course updated successfully.');

    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'title' => 'Updated Title',
        'duration' => 30,
    ]);
});

test('renders an existing course on its edit form', function () {
    $course = Course::factory()->create([
        'title' => 'Course To Edit',
        'description' => 'Existing details.',
    ]);

    $this->get(route('courses.edit', $course))
        ->assertOk()
        ->assertSeeText('Course To Edit')
        ->assertSee('value="Course To Edit"', false)
        ->assertSee('Existing details.', false);
});

test('shows course details and its registered students', function () {
    $course = Course::factory()->create(['title' => 'Web Development']);
    Student::factory()->for($course)->create([
        'name' => 'Sara Hassan',
        'email' => 'sara@example.com',
    ]);

    $this->get(route('courses.show', $course))
        ->assertOk()
        ->assertSeeText('Web Development')
        ->assertSeeText('Sara Hassan')
        ->assertSeeText('sara@example.com');
});

test('prevents deleting a course that has registered students', function () {
    $course = Course::factory()->create();
    Student::factory()->for($course)->create();

    $this->delete(route('courses.destroy', $course))
        ->assertRedirect(route('courses.index'))
        ->assertSessionHas('error', 'This course cannot be deleted while students are registered in it.');

    $this->assertModelExists($course);
});

test('deletes a course without registered students', function () {
    $course = Course::factory()->create();

    $this->delete(route('courses.destroy', $course))
        ->assertRedirect(route('courses.index'))
        ->assertSessionHas('success', 'Course deleted successfully.');

    $this->assertModelMissing($course);
});

test('escapes course titles before rendering them in HTML', function () {
    $payload = '<script>alert(1)</script>';
    Course::factory()->create(['title' => $payload]);

    $this->get(route('courses.index'))
        ->assertOk()
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
        ->assertDontSee($payload, false);
});
