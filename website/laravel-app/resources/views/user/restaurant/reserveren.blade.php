@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="bg-green-500 text-white text-center py-3 mb-6 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    <div id="home" class="flex flex-col items-center min-h-[83.9vh]">
        <h1 class="text-white text-4xl text-center font-extrabold mt-10">
            Reserveer een tafel bij HealthSphere Restaurant
        </h1>

        <div class="text-white text-lg text-center mt-5 mb-10">
            Kies hieronder of je een tafel binnen of buiten wilt reserveren, en bekijk de beschikbare tafels.
        </div>

        <form action="{{ route('fetchTables') }}" method="GET" class="flex flex-col items-center gap-6">
            <div class="flex flex-col items-center">
                <label for="tableLocation" class="text-white text-lg mb-2">Kies een locatie voor je tafel:</label>
                <select id="tableLocation" name="tableLocation" class="py-2 px-4 rounded-md border bg-white text-black">
                    <option value="inside">Binnen</option>
                    <option value="outside">Buiten</option>
                </select>
                <label for="datetime" class="text-white text-lg mb-2">Kies een tijdstip en dag voor de reservering:</label>
                <input
                    type="datetime-local"
                    id="datetime"
                    name="datetime"
                    class="py-2 px-4 rounded-md border bg-white text-black"
                    required
                >
                <label for="duration" class="text-white text-lg mb-2">Kies de duur van de reservering:</label>
                <select id="duration" name="duration" class="py-2 px-4 rounded-md border bg-white text-black">
                    <option value="1">1 uur</option>
                    <option value="2">2 uur</option>
                    <option value="3">3 uur</option>
                    <option value="4">4 uur</option>
                </select>
            </div>

            <button type="submit" class="py-3 px-8 bg-blue-500 hover:bg-blue-700 text-white rounded-full font-semibold transition duration-300 ease-in-out">
                Zoek beschikbare tafels
            </button>
        </form>

        <div id="tablesList" class="mt-10 w-full max-w-4xl">
            @if(isset($availableTables) || isset($unavailableTables))
                <h2 class="text-white text-2xl mb-6 text-center">Tafels:</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @foreach($availableTables as $table)
                        <div class="bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col justify-between">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold">Tafel {{ $table['tableNumber'] }}</h3>
                                <span class="bg-green-500 text-white text-xs font-semibold py-1 px-3 rounded-full">Beschikbaar</span>
                            </div>
                            <p class="text-gray-300 mb-4">Aantal Personen: {{ $table['numberOfPeople'] }}</p>
                            <form action="{{ route('reserveTable', ['tableId' => $table['id']]) }}" method="POST" class="flex flex-col gap-4">
                                @csrf
                                {{-- Verborgen velden voor locatie, tijd en duur --}}
                                <input type="hidden" name="tableLocation" value="{{ request('tableLocation') }}">
                                <input type="hidden" name="datetime" value="{{ request('datetime') }}">
                                <input type="hidden" name="duration" value="{{ request('duration') }}">

                                {{-- Invoerveld voor naam --}}
                                <label for="name_{{ $table['id'] }}" class="text-sm text-gray-400">Uw naam:</label>
                                <input
                                    type="text"
                                    id="name_{{ $table['id'] }}"
                                    name="name"
                                    class="py-2 px-4 rounded-md border bg-white text-black"
                                    placeholder="Voer uw naam in"
                                    required
                                >

                                <button type="submit" class="w-full py-2 px-4 bg-blue-500 hover:bg-blue-700 text-white rounded-lg font-semibold transition duration-300 ease-in-out">
                                    Reserveer
                                </button>
                            </form>
                        </div>
                    @endforeach

                    {{-- Gereserveerde Tafels --}}
                    @foreach($unavailableTables as $table)
                        <div class="bg-gray-800 text-white rounded-lg shadow-md p-6 flex flex-col justify-between">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold">Tafel {{ $table['tableNumber'] }}</h3>
                                <span class="bg-red-500 text-white text-xs font-semibold py-1 px-3 rounded-full">Gereserveerd</span>
                            </div>
                            <p class="text-gray-300 mb-4">Aantal Personen: {{ $table['numberOfPeople'] }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white text-lg text-center">Geen tafels gevonden. Probeer een andere locatie.</p>
            @endif
        </div>
    </div>
@endsection
