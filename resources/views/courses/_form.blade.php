<form action="{{ $course->exists ? route('courses.update', $course) : route('courses.store') }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($course->exists)
        @method('PUT')
    @endif

    <div>
        <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Course title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $course->title) }}" maxlength="255" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
        @error('title') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Short description</label>
        <textarea id="description" name="description" rows="3" maxlength="255" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">{{ old('description', $course->description) }}</textarea>
        @error('description') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label for="price" class="mb-2 block text-sm font-semibold text-slate-700">Price</label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $course->price) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            @error('price') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="duration" class="mb-2 block text-sm font-semibold text-slate-700">Duration (hours)</label>
            <input id="duration" name="duration" type="number" min="1" step="1" value="{{ old('duration', $course->duration) }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            @error('duration') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
        <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">{{ $course->exists ? 'Save changes' : 'Create course' }}</button>
        <a href="{{ route('courses.index') }}" class="rounded-lg px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100">Cancel</a>
    </div>
</form>