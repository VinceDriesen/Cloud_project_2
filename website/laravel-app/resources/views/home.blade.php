@extends('layouts.app')
@section('content')
    <div id="home" class="flex flex-col items-center min-h-[83.9vh]">
        <h1 class="text-primary-text-color text-4xl text-center mt-10 font-bold">
            HealthSphere is the easiest way to manage everything about your health and hospitals <br />
        </h1>
        <h2 class="text-secondary-text-color text-2xl text-center my-5">
            See all your document, everything you want <br />
            Search everything you want to know about your health <br />
            Manage all your hospital appointments as also the hospital restaurant <br />
            As easy as it can be!! <br />
        </h2>
        <a
            href="/register"
            class="py-3 px-6 rounded-3xl bg-action hover:bg-action-hover text-primary-text-color mt-10"
        >Get Started Now! &nbsp <span>&gt;</span></a>
    </div>
@endsection
