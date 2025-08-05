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

            <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 lg:px-24 pt-12">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <a href="/" class="text-blue-600 hover:underline dark:text-blue-400">
                            Home
                        </a>
                        / About
                    </h1>
                    <div class="flex gap-2">
                        <button onclick="setLanguage('bm')" id="btn-bm"
                            class="rounded-md px-4 py-2 text-sm font-medium transition-colors bg-pink-500 text-white dark:bg-pink-600">
                            Bahasa Melayu
                        </button>
                        <button onclick="setLanguage('en')" id="btn-en"
                            class="rounded-md px-4 py-2 text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            English
                        </button>
                    </div>
                </div>

                <div class="space-y-2 pt-12">
                    {{-- Bahasa Melayu Section --}}
                    <section id="section-bm">
                        <div class="space-y-6">
                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Pengenalan</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    Malaysia Waktu Solat API menyediakan data waktu solat yang tepat untuk kesemua tempat di
                                    Malaysia. Kami
                                    menyediakan maklumat seperti waktu solat bulanan, pengesanan zon berdasarkan koordinat
                                    dan lain-lain.
                                </p>
                            </div>
                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">
                                    Bagaimana data waktu solat dikumpulkan
                                </h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    Kami mengumpulkan data dengan mengakses API dari laman e-solat JAKIM dalam beberapa sela
                                    masa yang
                                    ditetapkan. Untuk mengekalkan kestabilan dan ketersediaan data sepanjang masa, kami
                                    menyimpan data
                                    tersebut ke dalam pangkalan data kami. <strong>Penafian:</strong> Jakim e-solat API
                                    tidak didokumenkan
                                    secara rasmi, mungkin ia tidak direka untuk kegunaan luar, jadi terdapat risiko untuk
                                    struktur data
                                    berubah tanpa sebarang pemberitahuan. Kami berusaha sedaya upaya kami untuk mengadaptasi
                                    supaya qualiti
                                    data dapat dikekalkan sepanjang masa. Untuk maklumat teknikal, sila lawati repository
                                    kami di
                                    <a href="https://github.com/mptwaktusolat/api-waktusolat-x" target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-pink-500 underline hover:text-pink-400 dark:text-pink-300 dark:hover:text-pink-200">
                                        https://github.com/mptwaktusolat/api-waktusolat-x
                                    </a>
                                </p>
                            </div>
                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Penafian</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    Perkhidmatan Malaysia Waktu Solat API ini disediakan tanpa waranti, dan pengguna
                                    mengaksesnya atas risiko
                                    sendiri, mengakui bahawa perkhidmatan mungkin tidak bebas dari kesilapan, tidak sentiasa
                                    tersedia, atau
                                    selamat, dan penyedia tidak bertanggungjawab atas sebarang kerosakan yang timbul
                                    daripada penggunaannya.
                                </p>
                            </div>
                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Polisi Penggunaan
                                    Adil</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    Malaysia Waktu Solat API ini dibernakan untuk kegunaan peribadi dan komersial.
                                    Penggunaan yang berlebihan
                                    atau penyalahgunaan API ini boleh mengakibatkan penggantungan akses sementara atau
                                    kekal. Pengguna
                                    digalakkan untuk menyimpan data secara 'local' untuk mengurangkan permintaan berulang.
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- English Section --}}
                    <section id="section-en" class="hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Introduction</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    The Malaysia Waktu Solat API provides accurate prayer times (waktu solat) for various
                                    locations in
                                    Malaysia. It offers information on monthly prayer timings, zone detection based on
                                    coordinates and more.
                                </p>
                            </div>

                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">How prayer time data
                                    is collected</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    We collect the data by tapping inside JAKIM's e-solat API in some pre-determined
                                    frequency. To maintain
                                    stability and availability of this API, we store the data snapshot on our own
                                    database.
                                    <strong>Disclaimer:</strong> JAKIM API is undocumented and probably not meant to be used
                                    by the public, as
                                    it can change without prior notice. Hence, we try our best to adapt for every new
                                    changes with the API to
                                    keep the data remains up to date every year. For more technical details, visit our
                                    repository at
                                    <a href="https://github.com/mptwaktusolat/api-waktusolat-x" target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-pink-500 underline hover:text-pink-400 dark:text-pink-300 dark:hover:text-pink-200">
                                        https://github.com/mptwaktusolat/api-waktusolat-x
                                    </a>
                                </p>
                            </div>

                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Disclaimer</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    The Malaysia Waktu Solat API service is provided without warranty, and users access it
                                    at their own risk,
                                    acknowledging that the service may not be error-free, available at all times, or secure,
                                    and the provider
                                    is not liable for any damages arising from its use.
                                </p>
                            </div>

                            <div>
                                <h3 class="mb-3 text-xl font-medium text-gray-900 dark:text-white">Fair Use Policy</h3>
                                <p class="leading-relaxed text-gray-700 dark:text-gray-300">
                                    The Malaysia Waktu Solat API is allowed for personal and commercial use. However,
                                    excessive use or abuse
                                    of the API may result in temporary or permanent suspension of access. Users are
                                    encouraged to cache data
                                    locally to minimize repeated requests.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
                {{-- Bento links --}}
                <div class="mt-12 pt-8">
                    <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                        <x-about-card :gradient="'from-blue-500 to-blue-600'" :href="'https://waktusolat.app'">
                            <x-slot:icon>
                                <x-ionicon-time class="h-5 w-5" />
                            </x-slot>
                            <h3 class="text-lg font-semibold">Waktu Solat Project</h3>
                            <p class="text-sm text-gray-300">Try our prayer time app!</p>
                        </x-about-card>

                        <x-about-card :gradient="'from-green-500 to-green-600'" :href="'https://docs.google.com/forms/d/e/1FAIpQLSe-zlZBW-8hO9XPDlLf-K7AUxtgupmD6bo4iouyLXFPAMnxFA/viewform'">
                            <x-slot:icon>
                                <x-ionicon-chatbubble-ellipses class="h-5 w-5" />
                            </x-slot>
                            <h3 class="text-lg font-semibold">Feedback</h3>
                            <p class="text-sm text-green-100">Share your thoughts or report issues</p>
                        </x-about-card>

                        <x-about-card :gradient="'from-gray-800 to-gray-900'" :href="'https://github.com/mptwaktusolat/api-waktusolat-x'">
                            <x-slot:icon>
                                <x-ionicon-logo-github class="h-5 w-5" />
                            </x-slot>
                            <h3 class="text-lg font-semibold">GitHub</h3>
                            <p class="text-sm text-gray-300">View source code or send patches</p>
                        </x-about-card>

                        <x-about-card :gradient="'from-purple-500 to-purple-600'" :href="'https://umami.iqfareez.com/share/dQGLdz7BivSE54it/api.waktusolat.app'">
                            <x-slot:icon>
                                <x-ionicon-pie-chart-sharp class="h-5 w-5" />
                            </x-slot>
                            <h3 class="text-lg font-semibold">Analytics</h3>
                            <p class="text-sm text-purple-100">Site statistics</p>
                        </x-about-card>
                    </div>
                </div>
            </div>
            {{-- Add some space to the bottom --}}
            <div class="h-24"></div>
        </main>

        <script>
            let currentLanguage = 'bm';

            function setLanguage(language) {
                currentLanguage = language;

                // Hide all sections
                document.getElementById('section-bm').classList.add('hidden');
                document.getElementById('section-en').classList.add('hidden');

                // Show selected section
                document.getElementById('section-' + language).classList.remove('hidden');

                // Update button styles
                const btnBm = document.getElementById('btn-bm');
                const btnEn = document.getElementById('btn-en');

                if (language === 'bm') {
                    btnBm.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-pink-500 text-white dark:bg-pink-600';
                    btnEn.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700';
                } else {
                    btnEn.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-pink-500 text-white dark:bg-pink-600';
                    btnBm.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700';
                }
            }
        </script>
    </body>
@endsection
