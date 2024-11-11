@extends('layouts.app')

@section('content')
    <div class="pt-5 pb-20 max-w-screen-sm mx-auto min-h-[83.9vh]">
        <h1 class="text-center text-primary-text-color text-4xl py-8">Gebruikersinstellingen</h1>
        <form
            class="flex flex-col items-center justify-center border-bordercolor border-2 rounded-xl bg-foreground p-5"
            method="post"
            action="{{ route('updateProfile') }}"
        >
            @csrf
            <input type="hidden" name="addressId" value="{{ $userDetails->Address->AddressId ?? old('addressId') }}"> <!-- Include AddressId -->

            <fieldset class="w-full mb-6">
                <legend class="text-lg font-semibold mb-2 text-secondary-text-color">Persoonlijke Informatie</legend>

                <label for="firstname" class="text-sm font-medium leading-6 text-secondary-text-color">Firstname:</label>
                <input
                    type="text"
                    name="firstname"
                    id="firstname"
                    value="{{ $user->firstname ?? old('firstname') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="lastname" class="text-sm font-medium leading-6 text-secondary-text-color">Lastname:</label>
                <input
                    type="text"
                    name="lastname"
                    id="lastname"
                    value="{{ $user->lastname ?? old('lastname') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="email" class="text-sm font-medium leading-6 text-secondary-text-color">Email:</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ $user->email ?? old('email') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="gender" class="text-sm font-medium leading-6 text-secondary-text-color">Geslacht:</label>
                <select id="gender" name="gender"
                        class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color">
                    <option value="" disabled {{ !isset($userDetails) || $userDetails->Gender === null ? 'selected' : '' }}>Kies een optie</option>
                    <option value="Male" {{ isset($userDetails) && $userDetails->Gender === 'Male' ? 'selected' : '' }}>Man</option>
                    <option value="Female" {{ isset($userDetails) && $userDetails->Gender === 'Female' ? 'selected' : '' }}>Vrouw</option>
                    <option value="Other" {{ isset($userDetails) && $userDetails->Gender === 'Other' ? 'selected' : '' }}>Anders</option>
                </select>

                <label for="birthday" class="text-sm font-medium leading-6 text-secondary-text-color">Geboortedatum:</label>
                <input
                    type="date"
                    name="birthday"
                    id="birthday"
                    value="{{ \Carbon\Carbon::parse($userDetails->Birthday)->format('Y-m-d') ?? old('birthday') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="nationality" class="text-sm font-medium leading-6 text-secondary-text-color">Nationaliteit:</label>
                <input
                    type="text"
                    name="nationality"
                    id="nationality"
                    placeholder="Enter nationality.."
                    value="{{ $userDetails->Nationality ?? old('nationality') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="phoneNumber" class="text-sm font-medium leading-6 text-secondary-text-color">Telefoonnummer:</label>
                <input
                    type="tel"
                    name="phoneNumber"
                    id="phoneNumber"
                    value="{{ $userDetails->PhoneNumber ?? old('phoneNumber') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />
            </fieldset>

            <fieldset class="w-full mb-6">
                <legend class="text-lg font-semibold mb-2 text-secondary-text-color">Adres</legend>

                <label for="street" class="text-sm font-medium leading-6 text-secondary-text-color">Straat:</label>
                <input
                    type="text"
                    name="street"
                    id="street"
                    placeholder="Enter street.."
                    value="{{ $userDetails->Address->Street ?? old('street') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="city" class="text-sm font-medium leading-6 text-secondary-text-color">Stad:</label>
                <input
                    type="text"
                    name="city"
                    id="city"
                    placeholder="Enter city.."
                    value="{{ $userDetails->Address->City ?? old('city') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="state" class="text-sm font-medium leading-6 text-secondary-text-color">Provincie:</label>
                <input
                    type="text"
                    name="state"
                    id="state"
                    placeholder="Enter state.."
                    value="{{ $userDetails->Address->State ?? old('state') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="postalCode" class="text-sm font-medium leading-6 text-secondary-text-color">Postcode:</label>
                <input
                    type="text"
                    name="postalCode"
                    id="postalCode"
                    placeholder="Enter postal code.."
                    value="{{ $userDetails->Address->PostalCode ?? old('postalCode') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />

                <label for="country" class="text-sm font-medium leading-6 text-secondary-text-color">Land:</label>
                <input
                    type="text"
                    name="country"
                    id="country"
                    placeholder="Enter country.."
                    value="{{ $userDetails->Address->Country ?? old('country') }}"
                    class="w-full border-2 rounded-lg px-2 mb-4 py-1 border-bordercolor bg-transparent outline-none text-primary-text-color"
                />
            </fieldset>

            <div class="mb-5 text-secondary-text-color">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br />
                    @endforeach
                @endif
            </div>

            <button type="submit" class="btn btn-primary flex w-2/4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition duration-300">
                Opslaan
            </button>
        </form>
    </div>
@endsection
