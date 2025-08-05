@extends('layouts.app')

@section('body')

    <body class="dark:bg-zinc-900 min-h-screen flex flex-col">

        <main class="flex items-center justify-center flex-1 flex-col relative overflow-hidden">
            {{-- Dark mode: Subtle glowing object --}}
            <div
                class="hidden dark:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-pink-500/20 rounded-full blur-[100px] opacity-50">
            </div>
            {{-- Light mode: Blue gradient from bottom to top --}}
            <div class="dark:hidden block absolute top-0 left-0 w-full h-full bg-gradient-to-t from-blue-50 to-white">
            </div>
            <form class="z-10" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                    <input id="email"
                        class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                        type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" />
                    @if ($errors->has('email'))
                        <span class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Password') }}</label>
                    <input id="password"
                        class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                        type="password" name="password" required autocomplete="current-password" />
                    @if ($errors->has('password'))
                        <span class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit"
                        class="ms-3 inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </main>
    </body>
@endsection
