@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <section class="py-16 px-6 border-b border-base-300">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-light tracking-tight text-base-content mb-4">Data Health</h1>
            <p class="text-base-content/60 text-lg">Prayer time data availability by month and zone.</p>
        </div>
    </section>

    {{-- Filters --}}
    <section class="px-6 py-6 bg-base-200 border-b border-base-300">
        <div class="max-w-7xl mx-auto">
            @php
                $currentYear = date('Y');
                $years = range(2023, $currentYear + 1);
            @endphp
            <form id="filter-form" method="GET" class="flex flex-wrap items-end gap-6">
                <div class="flex flex-col gap-1.5">
                    <label for="year-select"
                        class="text-xs font-medium text-base-content/50 uppercase tracking-widest">Year</label>
                    <select id="year-select" name="year" class="select select-bordered w-28 text-sm">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ request('year', $currentYear) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="zone-select"
                        class="text-xs font-medium text-base-content/50 uppercase tracking-widest">Zone</label>
                    <select id="zone-select" name="zone" class="select select-bordered w-36 text-sm">
                        @foreach ($zones as $z)
                            <option value="{{ $z['jakim_code'] }}"
                                {{ request('zone', $zones[0]['jakim_code']) == $z['jakim_code'] ? 'selected' : '' }}>
                                {{ $z['jakim_code'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <script>
                document.getElementById('year-select').addEventListener('change', function() {
                    document.getElementById('filter-form').submit();
                });
                document.getElementById('zone-select').addEventListener('change', function() {
                    document.getElementById('filter-form').submit();
                });
            </script>
        </div>
    </section>

    {{-- Month grid --}}
    <section class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            @php
                $months = range(1, 12);
                $selectedYear = request('year', $currentYear);
                $selectedZone = request('zone', $zones[0]['jakim_code']);
            @endphp

            <div class="grid grid-cols-2 gap-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 mb-10">
                @foreach ($months as $monthNumber)
                    <x-month-availability-card :year="$selectedYear" :monthNumber="$monthNumber" :zoneCode="$selectedZone" />
                @endforeach
            </div>

            <div class="border-t border-base-300 pt-6 text-sm text-base-content/50 space-y-1">
                <p>Showing availability for <span class="text-base-content/80 font-medium">{{ $selectedZone }}</span> zone.
                </p>
                <p>Records prior to May 2023 are expected to be unavailable (as I start collecting data since 2023).</p>
                <p>Prayer time database is updated periodically from the e-solat JAKIM portal.</p>
            </div>
        </div>
    </section>

    <x-footer />
@endsection
