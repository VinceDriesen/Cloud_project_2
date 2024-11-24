@extends('layouts.app')

@section('content')
    <div id="menu" class="flex flex-col items-center min-h-[83.9vh]">
        <h1 class="text-white text-4xl text-center font-extrabold mt-10">
            Ons Menu
        </h1>

        <div class="text-white text-lg text-center mt-5 mb-10">
            Verken ons heerlijke aanbod aan gerechten, desserts en drankjes.
        </div>

        <!-- Food Items Section -->
        <div class="w-full max-w-6xl mb-10">
            <h2 class="text-white text-2xl font-bold mb-5">Gerechten</h2>
            <div id="menuItems" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($foodItems as $foodItem)
                    <div class="bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $foodItem['name'] }}</h3>
                            <span class="bg-blue-500 text-white text-xs font-semibold py-1 px-3 rounded-full">
                                {{ $foodItem['type']['name'] }}
                            </span>
                        </div>
                        <p class="text-gray-300 mb-4">{{ $foodItem['description'] }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-500 font-bold text-lg">€{{ number_format($foodItem['price'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Desert Items Section -->
        <div class="w-full max-w-6xl mb-10">
            <h2 class="text-white text-2xl font-bold mb-5">Desserts</h2>
            <div id="desertItems" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($desertItems as $desertItem)
                    <div class="bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $desertItem['name'] }}</h3>
                            <span class="bg-blue-500 text-white text-xs font-semibold py-1 px-3 rounded-full">
                                {{ $desertItem['type']['name'] }}
                            </span>
                        </div>
                        <p class="text-gray-300 mb-4">{{ $desertItem['description'] }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-500 font-bold text-lg">€{{ number_format($desertItem['price'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Drink Items Section -->
        <div class="w-full max-w-6xl">
            <h2 class="text-white text-2xl font-bold mb-5">Drankjes</h2>
            <div id="drinkItems" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($drinkItems as $drinkItem)
                    <div class="bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold">{{ $drinkItem['name'] }}</h3>
                            <span class="bg-blue-500 text-white text-xs font-semibold py-1 px-3 rounded-full">
                                {{ $drinkItem['type']['name'] }}
                            </span>
                        </div>
                        <p class="text-gray-300 mb-4">{{ $drinkItem['description'] }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-500 font-bold text-lg">€{{ number_format($drinkItem['price'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
