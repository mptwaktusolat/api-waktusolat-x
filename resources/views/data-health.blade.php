@extends('layouts.app')

@section('body')

    <body class="dark:bg-zinc-900 min-h-screen flex flex-col">

        <main class="flex-1 relative overflow-hidden">
            {{-- Dark mode: Subtle glowing object --}}
            <div
                class="hidden dark:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-pink-500/20 rounded-full blur-[100px] opacity-50">
            </div>
            {{-- Light mode: Blue gradient from bottom to top --}}
            <div class="dark:hidden block absolute top-0 left-0 w-full h-full bg-gradient-to-t from-blue-50 to-white">
            </div>

            <!-- Header Section -->
            <div
                class="relative z-10 border-b border-gray-200 dark:border-zinc-700 bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-6 lg:px-24 py-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <a href="/" class="text-blue-600 hover:underline dark:text-blue-400">
                                Home
                            </a>
                            / Health
                        </h1>
                        @php
                            $currentYear = date('Y');
                            $years = range(2023, $currentYear + 1);
                        @endphp
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <form id="filter-form" method="GET" class="flex flex-col gap-3 sm:flex-row">
                                <div class="flex flex-col gap-1.5">
                                    <label for="year-select" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Year
                                    </label>
                                    <select id="year-select" name="year"
                                        class="w-[110px] px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-white text-sm">
                                        @foreach ($years as $y)
                                            <option value="{{ $y }}"
                                                {{ request('year', $currentYear) == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="zone-select" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Zone
                                    </label>
                                    <select id="zone-select" name="zone"
                                        class="w-[140px] px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-white text-sm">
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
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-24 py-8">
                @php
                    $months = range(1, 12);
                @endphp

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-6 mb-8">
                    @php
                        $selectedYear = request('year', $currentYear);
                        $selectedZone = request('zone', $zones[0]['jakim_code']);
                    @endphp
                    @foreach ($months as $monthNumber)
                        <x-month-availability-card :year="$selectedYear" :monthNumber="$monthNumber" :zoneCode="$selectedZone" />
                    @endforeach
                </div>

                <div
                    class="rounded-lg border border-sidebar-border/70 dark:border-sidebar-border p-4 text-sm text-gray-600 dark:text-gray-400 space-y-2">
                    <p>All data is checked:</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>based on {{ $selectedZone }} zone</li>
                        <li>against v2 solat API. Hence, data prior May 2023 is expected to be not available
                        </li>
                    </ul>
                    <p>
                        Prayer time database is updated periodically from e-solat JAKIM portal.
                        See fetcher implementation on <a href="https://github.com/mptwaktusolat/waktusolat-fetcher"
                            class="text-blue-600 dark:text-blue-400 hover:underline">
                            GitHub
                        </a>.
                    </p>
                </div>
            </div>
        </main>

    </body>
@endsection
