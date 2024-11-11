@extends('layouts.app')
@section('content')
    <div class="pt-5 pb-20 max-w-screen-sm ml-auto mr-auto min-h-[83.9vh]">
        <h1 class="text-center text-primary-text-color text-4xl py-8">Create account</h1>
        <form
            class="flex flex-col items-center justify-center border-bordercolor border-2 rounded-xl bg-foreground p-5"
            method="post"
            action="/register">
            @csrf
            <section class="flex w-full gap-6">
                <div class="w-full">
                    <label for="firstname" class="text-sm font-medium leading-6 text-secondary-text-color">Firstname</label>
                    <input
                        type="text"
                        name="firstname"
                        id="firstname"
                        placeholder="Enter firstname.."
                        class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color {{ $errors->has('firstname') ? 'border-red-400' : '' }}"
                        value="{{old('firstname')}}"
                    />
                </div>
                <div class="w-full">
                    <label for="lastname" class="text-sm font-medium leading-6 text-secondary-text-color">Lastname</label>
                    <input
                        type="text"
                        name="lastname"
                        id="lastname"
                        placeholder="Enter lastname.."
                        class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color {{ $errors->has('lastname') ? 'border-red-400' : '' }}"
                        value="{{old('lastname')}}"
                    />
                </div>
            </section>
            <div class="w-full">
                <label for="email" class="text-sm font-medium leading-6 text-secondary-text-color">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter email.."
                    class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color {{ $errors->has('email') ? 'border-red-400' : '' }}"
                    value="{{old('email')}}"
                />
            </div>
            <div class="w-full">
                <label for="password" class="text-sm font-medium leading-6 text-secondary-text-color">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter password.."
                    class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color {{ $errors->has('password') ? 'border-red-400' : '' }}"
                />
            </div>
            <div class="w-full">
                <label for="password_confirmation" class="text-sm font-medium leading-6 text-secondary-text-color">Verify Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    placeholder="Verify password.."
                    class="w-full border-2 rounded-lg px-2 mb-9 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color @error('password_confirmation') border-red-400 @enderror"
                />
            </div>
            <div class="mb-5 text-secondary-text-color">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br />
                    @endforeach
                @endif
            </div>
            <button type="submit" class="btn btn-primary flex w-2/4">{{ __('REGISTER') }}</button>
        </form>
    </div>
@endsection
