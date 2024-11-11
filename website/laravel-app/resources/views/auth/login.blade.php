@extends('layouts.app')
@section('content')
    <div class="min-h-[83.9vh] pt-20 pb-20 max-w-screen-sm ml-auto mr-auto">
        <h1 class="text-center text-primary-text-color text-4xl py-8">Sign in</h1>
        <form
            class="flex flex-col items-center justify-center border-bordercolor border-2 rounded-xl bg-foreground p-5"
            method="post"
            action="/login">
            @csrf
            <div class="w-full">
                <label for="email" class="text-sm font-medium leading-6 text-secondary-text-color">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter email.."
                    class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color {{ $errors->has('email') ? 'border-red-400' : '' }}"
                />
            </div>
            <div class="w-full">
                <label for="password" class="text-sm font-medium leading- text-secondary-text-color">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter password.."
                    class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color {{ $errors->has('password') ? 'border-red-400' : '' }}"
                />
            </div>
            <div class="mb-5 text-secondary-text-color">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br />
                    @endforeach
                @endif
            </div>
            <button type="submit" class="btn btn-primary flex w-2/4">{{ __('LOGIN') }}</button>
        </form>
    </div>
@endsection
