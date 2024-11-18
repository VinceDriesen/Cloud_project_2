@extends('layouts.app')

@section('content')
<div class="w-fit mx-auto mt-20 min-h-[83.9vh]">
    {{-- Sensor Data Section --}}
    <div class="w-full flex flex-col gap-5 p-5 bg-foreground border-2 border-component-border rounded-2xl">
        <h1 class="text-2xl font-semibold text-primary-text-color">Latest Sensor Data</h1>

        {{-- Sensor Data Details --}}
        <ul class="flex flex-col gap-3 text-lg text-primary-text-color">
            <li class="flex justify-between">
                <span class="font-medium">Humidity:</span>
                <span>{{ $data['humidity'] }}%</span>
            </li>
            <li class="flex justify-between">
                <span class="font-medium">Oxygen Level:</span>
                <span>{{ $data['oxygenLevel'] }}%</span>
            </li>
            <li class="flex justify-between">
                <span class="font-medium">Available Parking Spaces:</span>
                <span>{{ $data['availableParkingSpaces'] }}</span>
            </li>
            <li class="flex justify-between">
                <span class="font-medium">Temperature:</span>
                <span>{{ $data['temperature'] }}°C</span>
            </li>
        </ul>
    </div>
</div>

@endsection
