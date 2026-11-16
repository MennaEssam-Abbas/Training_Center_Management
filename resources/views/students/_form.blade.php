<form action="{{ $student->exists ? route('students.update', $student) : route('students.store') }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($student->exists)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Full name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $student->name) }}" maxlength="255" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('name') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $student->email) }}" maxlength="255" required class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('email') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Phone <span class="font-normal text-slate-400">(optional)</span></label>
        <input id="phone" name="phone" type="tel" value="{{ old('phone', $student->phone) }}" maxlength="30" class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('phone') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="course_id" class="mb-2 block text-sm font-semibold text-slate-700">Course</label>
        <select id="course_id" name="course_id" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            <option value="">Select a course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $student->course_id) === (string) $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
        @error('course_id') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
        @if ($courses->isEmpty())
            <p class="mt-2 text-sm text-amber-700">Create a course before registering a student.</p>
        @endif
    </div>

    <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
        <button type="submit" @disabled($courses->isEmpty()) class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">{{ $student->exists ? 'Save changes' : 'Register student' }}</button>
        <a href="{{ route('students.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">Cancel</a>
    </div>
</form>