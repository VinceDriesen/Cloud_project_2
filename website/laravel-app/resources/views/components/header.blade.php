

<header class="for-desktop w-full bg-header-footer rounded-b-xl p-1 mb-1 static z-50">
    <nav
        class="flex flex-row justify-between items-center text-primary-text-color text-2xl mx-4"
    >
        <x-logo />

        @if (!Auth::check())
            <div class="flex flex-row gap-4">
                <a href="/" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Home</a>
                <a href="/contact" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Contact</a>
            </div>
        @else
            <div class="flex flex-row gap-4 items-center">
                <a href="/dashboard" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Home</a>
                <div class="dropdown dropdown-hover cursor-pointer">
                    <div class="text-primary-text-color pr-2 p-1 bg-transparent border-none hover:bg-active-footer-header hover:rounded-2xl">
                        Calendar
                    </div>
                    <ul class="dropdown-content z-50 menu p-2 shadow bg-foreground rounded-box text-xl w-40">
                        <li><a href="/calendar">My calendar</a></li>
                        @if (Auth::user()->isDoctor())
                            <li><a href="/doctor/calendar/newAppointment">Add appointments</a></li>
                        @else
                            <li><a href="/calendar/scheduleAppointment">Create appointment</a></li>
                        @endif
                    </ul>
                </div>
                <a href="/restaurant" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Restaurant</a>
                <a href="/hospital-data" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Data</a>
            </div>
        @endif
        <div class="flex gap-4 items-center w-80 flex-row justify-end">
            @if (!Auth::check())
                <a href="/login" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Sign In</a>
                <a id="signUp" href="/register" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">SignUp</a>
            @else
                <a href="/profileSettings" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Profile</a>
                <a href="/signout" class="pl-2 pr-2 p-1 hover:bg-active-footer-header hover:rounded-2xl">Log Out</a>
            @endif
            <form action="/change-theme" method="POST">
                @csrf
                <label class="swap swap-rotate text-primary-text-color w-8 h-8">
                    <input
                        @if(session('theme') === 'dark')
                            checked
                        @endif
                        type="checkbox"
                        class="theme-controller"
                        onchange="this.form.submit()"
                    />
                    <svg
                        class="swap-off fill-current w-full h-full"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24">
                        <path
                            d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"
                        />
                    </svg>
                    <svg
                        class="swap-on fill-current w-full h-full"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24">
                        <path
                            d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"
                        /></svg>
                </label>
            </form>
        </div>
    </nav>
</header>

<header class="for-mobile w-full bg-header-footer rounded-xl py-1 mb-1 static" x-data="{clickedDropdown : false}">
    <nav
        class="flex flex-row justify-between items-center text-primary-text-color text-2xl mx-4"
    >
        <div>
            <x-logo />
        </div>
        <div class="flex flex-row items-center gap-4">
            <div>
                <button @click="clickedDropdown = !clickedDropdown">
                    <i class="fa-solid fa-bars" x-show="!clickedDropdown"></i>
                    <i class="fa-solid fa-xmark" x-show="clickedDropdown"></i>
                </button>
            </div>
            <form action="/change-theme" method="POST">
                @csrf
                <label class="swap swap-rotate text-primary-text-color w-8 h-8">
                    <input
                        @if(session('theme') === 'dark')
                            checked
                        @endif
                        type="checkbox"
                        class="theme-controller"
                        onchange="this.form.submit()"
                    />
                    <svg
                        class="swap-off fill-current w-full h-full"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24">
                        <path
                            d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z"
                        />
                    </svg>
                    <svg
                        class="swap-on fill-current w-full h-full"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24">
                        <path
                            d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"
                        /></svg>
                </label>
            </form>
        </div>
    </nav>
    {{--    <section x-cloak x-show="clickedDropdown" @click.away="clickedDropdown = false">--}}
    {{--        <x-headerFooter.mobile-navbar/>--}}
    {{--    </section>--}}
</header>
