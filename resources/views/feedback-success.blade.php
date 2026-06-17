@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <section class="border-b border-base-300 bg-base-200/50 px-6 py-14">
        <div class="mx-auto max-w-7xl">
            <h1 class="mb-4 text-5xl font-light tracking-tight text-base-content md:text-6xl">{{ __('feedback.title') }}</h1>
            <p class="max-w-3xl text-base text-base-content/70 md:text-lg">{{ __('feedback.subtitle') }}</p>
        </div>
    </section>

    <section class="px-6 py-12 md:py-16">
        <div class="mx-auto w-full max-w-4xl">
            <div class="grid grid-cols-3 overflow-hidden">
                <div class="bg-tile-blue px-4 py-2"></div>
                <div class="bg-tile-green px-4 py-2"></div>
                <div class="bg-tile-orange px-4 py-2"></div>
            </div>

            <div class="card border border-base-300 bg-base-100">
                <div class="card-body items-center p-8 text-center">
                    <h2 class="text-3xl font-light text-base-content">{{ __('feedback.success_title') }}</h2>
                    <p class="max-w-xl text-base-content/70">{{ __('feedback.success_message') }}</p>
                    <a href="{{ route('home') }}" class="btn mt-3 border-0 bg-tile-blue text-white hover:brightness-110">
                        {{ __('feedback.back_home') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
