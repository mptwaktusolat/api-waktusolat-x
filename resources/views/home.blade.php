@extends('layouts.app')

@section('content')
    {{-- Hero section --}}
    <section class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-light tracking-tight text-base-content mb-6">
                Waktu Solat Malaysia<br>
                <span class="text-primary">Web API</span>
            </h1>
            <p class="text-xl text-base-content/60 max-w-2xl leading-relaxed mb-10">
                Free and fast API to get prayer times across Malaysia. Official data from JAKIM, reliable for your
                applications.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/docs"
                    class="flex items-center gap-2 px-6 py-3 bg-tile-green text-white font-medium hover:brightness-110 transition-all">
                    <x-ionicon-code-slash-outline class="h-5 w-5" />
                    View Documentation
                </a>
                <a href="/health"
                    class="flex items-center gap-2 px-6 py-3 bg-base-200 dark:bg-[#222] text-base-content font-medium hover:bg-base-300 dark:hover:bg-[#2e2e2e] transition-all">
                    Data Health
                </a>
            </div>
        </div>
    </section>

    {{-- Feature cards --}}
    <section class="px-6 py-16 bg-base-200 border-y border-base-300">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-tile-green flex items-center justify-center shrink-0">
                        <x-ionicon-flash-outline class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-2">Fast &amp; Free</h3>
                        <p class="text-base-content/60 text-sm">No cost, no rate limits, ready for public use</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-tile-blue flex items-center justify-center shrink-0">
                        <x-ionicon-shield-checkmark-outline class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-2">Official JAKIM Data</h3>
                        <p class="text-base-content/60 text-sm">Trusted and accurate data source from Malaysia's
                            government body</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-tile-orange flex items-center justify-center shrink-0">
                        <x-ionicon-location-outline class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-2">All Malaysia Zones</h3>
                        <p class="text-base-content/60 text-sm">Complete coverage for Peninsular, Sabah &amp;
                            Sarawak</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Companies section --}}
    <section class="px-6 py-16 border-b border-base-300">
        <div class="max-w-7xl mx-auto flex flex-col items-center space-y-8">
            <p class="text-sm text-base-content/50 uppercase tracking-widest">Trusted by</p>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 max-w-4xl">
                <div
                    class="flex items-center justify-center opacity-60 hover:opacity-100 transition-opacity dark:invert dark:opacity-50 dark:hover:opacity-100">
                    <img src="{{ asset('images/companies/asar-logo.png') }}" alt="asar"
                        class="h-8 md:h-12 w-auto object-contain">
                </div>
                <div
                    class="flex items-center justify-center opacity-60 hover:opacity-100 transition-opacity dark:invert dark:opacity-50 dark:hover:opacity-100">
                    <img src="{{ asset('images/companies/masjid-buntal.png') }}" alt="Masjid Buntal"
                        class="h-8 md:h-12 w-auto object-contain">
                </div>
                <div
                    class="flex items-center justify-center opacity-60 hover:opacity-100 transition-opacity dark:invert dark:opacity-50 dark:hover:opacity-100">
                    <img src="{{ asset('images/companies/masjid-pangkor-terapung.png') }}" alt="Masjid Pangkor Terapung"
                        class="h-8 md:h-12 w-auto object-contain">
                </div>
                <div
                    class="flex items-center justify-center opacity-60 hover:opacity-100 transition-opacity dark:invert dark:opacity-50 dark:hover:opacity-100">
                    <img src="{{ asset('images/companies/pahanggo.png') }}" alt="PahangGo"
                        class="h-8 md:h-12 w-auto object-contain">
                </div>
            </div>
            <p class="text-sm text-base-content/40">and many more...</p>
        </div>
    </section>

    {{-- API Example --}}
    <section id="example" class="px-6 py-20">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-light text-base-content mb-2">Usage Example</h2>
            <p class="text-base-content/60 mb-10">
                See all available endpoints on the <a class="font-semibold underline" href="/docs">Swagger page</a>.
            </p>

            <div class="space-y-6">

                {{-- Code Block: Request --}}
                <div class="bg-base-200 border border-base-300">
                    <div class="px-4 py-2 border-b border-base-300 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <x-ionicon-terminal-outline class="h-4 w-4 text-base-content/60" />
                            <span class="text-sm font-medium text-base-content/60">Get current month prayer times</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="api-reset-btn"
                                class="btn btn-xs bg-base-300 text-base-content border-0 hover:bg-base-content/20 gap-1">
                                <x-ionicon-refresh-outline class="h-4 w-4" />
                                Clear
                            </button>
                            <button id="api-try-btn"
                                class="btn btn-xs bg-tile-green text-white border-0 hover:brightness-110 gap-1">
                                <x-ionicon-play-outline class="h-4 w-4" />
                                Try it
                            </button>
                        </div>
                    </div>
                    <pre class="p-4 overflow-x-auto"><code id="api-curl-display" class="text-sm font-mono text-base-content/90">curl -X GET "{{ url('/v2/solat/WLY01') }}" -H "Accept: application/json"</code></pre>
                </div>

                {{-- Code Block: Response --}}
                <div class="bg-base-200 border border-base-300">
                    <div class="px-4 py-2 border-b border-base-300 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <x-ionicon-terminal-outline class="h-4 w-4 text-base-content/60" />
                            <span class="text-sm font-medium text-base-content/60">Response (JSON)</span>
                        </div>
                        <span id="api-status-badge" class="hidden text-xs font-mono px-2 py-0.5 rounded-full"></span>
                    </div>
                    <pre class="p-4 overflow-x-auto"><code id="api-response-display" class="text-sm font-mono text-base-content/90">// Response will be shown here</code></pre>
                </div>

            </div>
        </div>
    </section>

    {{-- Logic for the interactive usage example --}}
    <script>
        const baseUrl = '{{ url('') }}';
        const tryBtn = document.getElementById('api-try-btn');
        const resetBtn = document.getElementById('api-reset-btn');
        const responseDisplay = document.getElementById('api-response-display');
        const statusBadge = document.getElementById('api-status-badge');
        const zone = 'WLY01';

        tryBtn.addEventListener('click', async function() {
            const url = `${baseUrl}/v2/solat/${zone}`;

            tryBtn.disabled = true;
            tryBtn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Loading';
            statusBadge.className = 'hidden text-xs font-mono px-2 py-0.5 rounded-full';
            responseDisplay.textContent = '// Fetching...';

            try {
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();

                statusBadge.textContent = res.status + ' ' + res.statusText;
                statusBadge.className =
                    `text-xs font-mono px-2 py-0.5 rounded-full ${res.ok ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'}`;
                statusBadge.classList.remove('hidden');

                responseDisplay.textContent = JSON.stringify(data, null, 2);
            } catch (err) {
                statusBadge.textContent = 'Error';
                statusBadge.className =
                    'text-xs font-mono px-2 py-0.5 rounded-full bg-red-500/20 text-red-400';
                statusBadge.classList.remove('hidden');
                responseDisplay.textContent = '// Failed to fetch. Check the zone code and try again.';
            } finally {
                tryBtn.disabled = false;
                tryBtn.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 512 512"><polygon points="80 32 432 256 80 480 80 32" fill="currentColor"/></svg> Try it';
            }
        });

        resetBtn.addEventListener('click', function() {
            responseDisplay.textContent = '// Response will be shown here';
            statusBadge.className = 'hidden text-xs font-mono px-2 py-0.5 rounded-full';
        });
    </script>

    {{-- FAQ Accordian section --}}
    <section id="faq" class="bg-base-200 border-y border-base-300">
        <div class="px-6 py-20">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl font-light text-base-content mb-2">Frequently Asked Questions</h2>
                <p class="text-base-content/60 mb-10">Important information about the Waktu Solat Malaysia API</p>

                <div class="w-full">

                    <div class="collapse collapse-arrow border-b border-base-300">
                        <input type="checkbox" />
                        <div class="collapse-title font-medium text-base-content px-0!">
                            What is the data source for prayer times?
                        </div>
                        <div class="collapse-content text-sm text-base-content/70 px-0!">
                            Our prayer time data is sourced directly from JAKIM (Jabatan Kemajuan Islam Malaysia), the
                            official government body responsible for Islamic affairs in Malaysia. Data is updated
                            regularly to ensure accuracy.
                        </div>
                    </div>

                    <div class="collapse collapse-arrow border-b border-base-300">
                        <input type="checkbox" />
                        <div class="collapse-title font-medium text-base-content px-0!">
                            Is this API free to use?
                        </div>
                        <div class="collapse-content text-sm text-base-content/70 px-0!">
                            Yes, this API is completely free for public use. There are no rate limits imposed at this
                            time. We provide this service as a contribution to the Muslim community in Malaysia.
                        </div>
                    </div>

                    <div class="collapse collapse-arrow border-b border-base-300">
                        <input type="checkbox" />
                        <div class="collapse-title font-medium text-base-content px-0!">
                            Which zones are supported?
                        </div>
                        <div class="collapse-content text-sm text-base-content/70 px-0!">
                            We support all prayer time zones in Malaysia including all states in Peninsular Malaysia,
                            Sabah, Sarawak, and Federal Territories. Each state has its own zone codes as defined by
                            JAKIM.
                        </div>
                    </div>

                    <div class="collapse collapse-arrow border-b border-base-300">
                        <input type="checkbox" />
                        <div class="collapse-title font-medium text-base-content px-0!">
                            How do I get zone codes?
                        </div>
                        <div class="collapse-content text-sm text-base-content/70 px-0!">
                            Use the <code class="text-primary font-mono">GET /api/zones</code> endpoint to get a
                            complete list of all supported zones. Example codes: SGR01 (Gombak, Petaling), JHR01
                            (Pulau Aur, Pemanggil), etc.
                        </div>
                    </div>

                    <div class="collapse collapse-arrow border-b border-base-300">
                        <input type="checkbox" />
                        <div class="collapse-title font-medium text-base-content px-0!">
                            Does this API support specific dates?
                        </div>
                        <div class="collapse-content text-sm text-base-content/70 px-0!">
                            Yes, you can request prayer times for any month using the
                            <code class="text-primary font-mono">year</code> and
                            <code class="text-primary font-mono">month</code> query parameters. We support data for
                            the current year and the following year.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <x-footer />
@endsection
