@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <section class="py-16 px-6 border-b border-base-300">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-light tracking-tight text-base-content mb-4">Data Health</h1>
            <p class="text-base-content/60 text-lg">Prayer time data availability by month across all zones.</p>
        </div>
    </section>

    {{-- Filters --}}
    <section class="px-6 py-6 bg-base-200 border-b border-base-300">
        <div class="max-w-7xl mx-auto">
            <form id="filter-form" method="GET" class="flex flex-wrap items-end gap-6">
                <div class="flex flex-col gap-1.5">
                    <label for="year-select"
                        class="text-xs font-medium text-base-content/50 uppercase tracking-widest">Year</label>
                    <select id="year-select" name="year" class="select select-bordered w-28 text-sm"
                        onchange="this.form.submit()">
                        @php
                            $currentYear = now()->year;
                            $years = range(2023, $currentYear + 2);
                        @endphp
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ (int) $selectedYear === $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </section>

    {{-- Month grid --}}
    <section class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 gap-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 mb-10">
                @foreach ($months as $month)
                    <x-month-availability-card :month-name="$month['monthName']" :status="$month['status']" :missing-coverage="$month['missingCoverage']" />
                @endforeach
            </div>

            <div class="mb-8 flex flex-wrap gap-x-6 gap-y-3 text-sm text-base-content/70">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 bg-tile-green"></span>
                    <span>All zones available</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 bg-tile-yellow"></span>
                    <span>Incomplete data</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 bg-base-300 ring-1 ring-base-content/10"></span>
                    <span>Unavailable</span>
                </div>
            </div>

            <ul class="border-t border-base-300 pt-6 text-sm text-base-content/50 space-y-1 list-disc pl-5">
                <li>Showing overall availability for all prayer time zones in Malaysia.</li>
                <li>'Incomplete data' indicates that at least one zone is missing data for that month. Hover to see the
                    missing states.</li>
                <li>Records prior to May 2023 are expected to be unavailable because collection started in 2023.</li>
                <li>Prayer time database is updated periodically from the e-solat JAKIM portal.</li>
            </ul>
        </div>
    </section>

    <x-footer />
@endsection
