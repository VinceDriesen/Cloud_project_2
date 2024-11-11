@extends('layouts.app')
@section('content')
    <div class="w-fit min-h-[83.9vh] mt-20 mb-20 max-w-screen-sm ml-auto mr-auto">
        <div class="bg-foreground rounded-xl p-3">
            <h1 class="text-center text-primary-text-color text-4xl py-8">Sign Out</h1>
            <h3 class="text-center text-primary-text-color text-2xl pb-8">
                Are you sure you want to sign out?
            </h3>
            <div class="w-full justify-around flex" >
                <form class="w-1/3" method="post" action="signout">
                    @csrf
                    <button type="submit" class="btn w-full btn-error">{{ __('logout') }}</button>
                </form>
                <a type="button" class="btn w-1/3 btn-success" href="{{url()->previous()}}">Go back</a>
            </div>
        </div>
    </div>
@endsection
