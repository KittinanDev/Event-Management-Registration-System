@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ isset($event) ? 'Edit Event' : 'Create Event' }}</h2>

                <form action="{{ isset($event) ? route('events.update', $event) : route('events.store') }}" method="POST">
                    @csrf
                    @if(isset($event)) @method('PUT') @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Event Title</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror" 
                            id="title" type="text" name="title" value="{{ old('title', $event->title ?? '') }}" required>
                        @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" 
                            id="description" name="description" rows="5" required>{{ old('description', $event->description ?? '') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Location</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('location') border-red-500 @enderror" 
                            id="location" type="text" name="location" value="{{ old('location', $event->location ?? '') }}" required>
                        @error('location') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="start_datetime">Start Date/Time</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_datetime') border-red-500 @enderror" 
                                id="start_datetime" type="datetime-local" name="start_datetime" value="{{ old('start_datetime', isset($event) ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}" required>
                            @error('start_datetime') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="end_datetime">End Date/Time</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_datetime') border-red-500 @enderror" 
                                id="end_datetime" type="datetime-local" name="end_datetime" value="{{ old('end_datetime', isset($event) ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}" required>
                            @error('end_datetime') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="max_participants">Max Participants (leave empty for unlimited)</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('max_participants') border-red-500 @enderror" 
                            id="max_participants" type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants ?? '') }}" min="1">
                        @error('max_participants') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" 
                            id="status" name="status" required>
                            <option value="draft" {{ old('status', $event->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $event->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="cancelled" {{ old('status', $event->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            {{ isset($event) ? 'Update Event' : 'Create Event' }}
                        </button>
                        <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-900">Back to Events</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
