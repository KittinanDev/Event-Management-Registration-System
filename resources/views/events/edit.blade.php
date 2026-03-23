<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-xl font-bold text-white">Edit Event</h2>
            <a href="{{ route('events.show', $event) }}" class="btn-slate text-xs">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        @if($errors->any())<div class="alert-error">{{ $errors->first() }}</div>@endif

        <div class="card p-6 sm:p-8">
            <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="label" for="title">Event Title</label>
                    <input class="input-field @error('title') border-red-500/60 @enderror" id="title" type="text" name="title" value="{{ old('title', $event->title) }}" required>
                    @error('title')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="description">Description</label>
                    <textarea class="input-field @error('description') border-red-500/60 @enderror" id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="location">Location</label>
                    <input class="input-field @error('location') border-red-500/60 @enderror" id="location" type="text" name="location" value="{{ old('location', $event->location) }}" required>
                    @error('location')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label" for="start_datetime">Start Date/Time</label>
                        <input class="input-field @error('start_datetime') border-red-500/60 @enderror" id="start_datetime" type="datetime-local" name="start_datetime" value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}" required>
                        @error('start_datetime')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="end_datetime">End Date/Time</label>
                        <input class="input-field @error('end_datetime') border-red-500/60 @enderror" id="end_datetime" type="datetime-local" name="end_datetime" value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}" required>
                        @error('end_datetime')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="label" for="max_attendees">Max Attendees <span class="text-slate-600">(leave empty for unlimited)</span></label>
                    <input class="input-field @error('max_attendees') border-red-500/60 @enderror" id="max_attendees" type="number" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees ?? $event->max_participants ?? '') }}" min="1" placeholder="Unlimited">
                    @error('max_attendees')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="status">Status</label>
                    <select class="input-field @error('status') border-red-500/60 @enderror" id="status" name="status" required>
                        <option value="open" {{ old('status', $event->status) === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status', $event->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="ongoing" {{ old('status', $event->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between border-t border-white/8 pt-5">
                    <button type="submit" class="btn-green">Update Event</button>
                    <a href="{{ route('events.show', $event) }}" class="text-sm text-slate-400 hover:text-white transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
