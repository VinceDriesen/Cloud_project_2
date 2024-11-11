@extends('layouts.app')

@section('content')
<div class="w-fit mx-auto mt-20 min-h-[83.9vh]">
    {{-- Event Form --}}
    <form class="w-fit flex flex-col gap-5 border-2 border-component-border p-5 bg-foreground rounded-2xl"
          method="POST"
          action="/calendar/newevent">
        @csrf

        <input type="hidden" name="eventId" value="{{ old('eventId', $event->id ?? '') }}" />
        <input type="hidden" name="agenda_id" value="{{ old('agenda_id', $agenda_id ?? '') }}" />

        {{-- Form Header with Title and Buttons --}}
        <header class="flex flex-row gap-20">
            <input
                class="text-primary-text-color p-1 outline-none bg-component w-full rounded-lg text-xl"
                type="text"
                name="title"
                placeholder="Title..."
                autocomplete="off"
                required
                value="{{ old('title', $event->eventname ?? '') }}"
            />
            <div class="flex flex-row gap-5">
                <button type="button" class="btn btn-sm bg-component btn-error border-none text-white"
                       >Cancel</button>
                <button class="btn btn-sm bg-action hover:bg-action-hover border-none text-white" type="submit">Save</button>
            </div>
        </header>

        {{-- Date and Time Pickers --}}
        <div class="flex flex-row gap-2 items-center">
            <input type="datetime-local" name="startDateTime" required
                   class="p-2 text-primary-text-color outline-none bg-component rounded-lg"
                   value="{{ old('startDateTime', $start ?? '') }}" />

            <span class="text-secondary-text-color text-lg">Until</span>

            <input type="datetime-local" name="endDateTime" required
                   class="p-2 text-primary-text-color outline-none bg-component rounded-lg"
                   value="{{ old('endDateTime', $end ?? '') }}" />
        </div>

        {{-- Description Field --}}
        <textarea
            class="p-2 outline-none text-primary-text-color bg-component w-full rounded-lg resize-none h-24"
            name="description"
            placeholder="Description...">{{ old('description', $event->description ?? '') }}</textarea>
    </form>
</div>

@endsection
