@extends('layouts.app')

@section('title', $announcement->title . ' · Waktu Solat API')
@section('description', $announcement->summary)

@section('content')
    <section class="border-b border-base-300 bg-base-200/50 px-6 py-10 md:py-14">
        <div class="mx-auto flex max-w-4xl flex-col gap-4">
            <h1 class="text-3xl font-light leading-tight tracking-tight text-base-content sm:text-4xl md:text-5xl">
                {{ $announcement->title }}
            </h1>
            <time datetime="{{ $announcement->date->toDateString() }}" class="text-base-content/60">
                {{ $announcement->displayDate() }}
            </time>
        </div>
    </section>

    <article class="flex-1 px-6 py-10 md:py-12">
        <div class="mx-auto max-w-3xl">
            <div class="announcement-body">
                {!! $announcement->html() !!}
            </div>

            <div class="mt-12 border-t border-base-300 pt-6">
                <a href="{{ route('announcements') }}"
                    class="inline-flex items-center gap-2 text-tile-blue hover:underline underline-offset-4">
                    <x-ionicon-arrow-back-outline class="h-4 w-4" aria-hidden="true" />
                    <span>All announcements</span>
                </a>
            </div>
        </div>
    </article>

    <x-footer />
@endsection
