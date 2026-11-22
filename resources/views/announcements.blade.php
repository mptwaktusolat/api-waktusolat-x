@extends('layouts.app')

@section('title', 'Announcements · Waktu Solat API')
@section('description', 'Updates related to the API server and prayer time data.')

@section('content')
    <section class="border-b border-base-300 bg-base-200/50 px-6 py-10 md:py-14">
        <div class="mx-auto max-w-7xl">
            <h1 class="mb-4 text-4xl font-light tracking-tight text-base-content sm:text-5xl md:text-6xl">Announcements</h1>
            <p class="max-w-3xl text-base text-base-content/70 md:text-lg">@yield('description')</p>
        </div>
    </section>

    <section class="flex-1 px-6 py-10 md:py-12">
        <div class="mx-auto max-w-4xl">
            @if ($announcements->isEmpty())
                <p class="border-t border-base-300 pt-8 text-base-content/70">No announcements yet.</p>
            @else
                <ol>
                    @foreach ($announcements as $announcement)
                        <li class="flex flex-col gap-2 border-b border-base-300 py-6 md:py-8">
                            <time datetime="{{ $announcement->date->toDateString() }}"
                                class="text-sm text-base-content/60 tabular-nums">
                                {{ $announcement->displayDate() }}
                            </time>
                            <h2 class="text-2xl font-light leading-tight text-base-content md:text-3xl">
                                <a href="{{ route('announcement', $announcement->slug) }}"
                                    class="underline-offset-4 transition-colors hover:text-tile-blue hover:underline">
                                    {{ $announcement->title }}
                                </a>
                            </h2>
                            @if ($announcement->summary)
                                <p class="max-w-3xl leading-relaxed text-base-content/70">{{ $announcement->summary }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </section>

    <x-footer />
@endsection
