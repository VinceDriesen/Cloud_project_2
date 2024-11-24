@extends('layouts.app')

@section('content')
    <div id="home" class="flex flex-col items-center min-h-[83.9vh]">
        <h1 class="text-white text-4xl text-center font-extrabold mt-10">
            Welkom bij de restaurant website van HealthSphere
        </h1>

        <div class="text-white text-lg text-center mt-5 mb-10">
            Hier kun je allerlei gerechten bestellen en laten bezorgen op je kamer.<br />
            En zelfs tafels reserveren om hier te eten in ons ziekenhuisrestaurant. <br/>
            Als het goed weer is, kan je ook buiten tafels reserveren!
        </div>

        <div class="flex justify-center gap-4">
            <!-- Reserveer een tafel knop met link -->
            <a href="/restaurant/reserveren" class="py-3 px-8 bg-blue-500 hover:bg-blue-700 text-white rounded-full font-semibold transition duration-300 ease-in-out">
                Reserveer een tafel
            </a>

            <!-- Menu knop met link -->
            <a href="/restaurant/menu" class="py-3 px-8 bg-yellow-500 hover:bg-yellow-700 text-white rounded-full font-semibold transition duration-300 ease-in-out">
                Menu
            </a>
        </div>
    </div>
@endsection
