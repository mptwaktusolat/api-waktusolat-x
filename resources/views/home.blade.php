@extends('layouts.app')

@section('body')

    <body class="dark:bg-zinc-900 min-h-screen flex flex-col">
        <x-top-banner>
            <strong>🎉 Latest:</strong> Data for new zone: <strong>PHG07</strong> is now available!
        </x-top-banner>

        <main class="flex items-center justify-center flex-1 flex-col relative overflow-hidden">
            {{-- Dark mode: Subtle glowing object --}}
            <div
                class="hidden dark:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-pink-500/20 rounded-full blur-[100px] opacity-50">
            </div>
            {{-- Light mode: Blue gradient from bottom to top --}}
            <div class="dark:hidden block absolute top-0 left-0 w-full h-full bg-gradient-to-t from-blue-50 to-white">
            </div>

            <section class="w-full py-6 sm:py-12 md:py-24 lg:py-32 xl:py-48 relative z-10">
                <div class="px-4 md:px-6">
                    <div class="flex flex-col space-y-8 text-center">
                        <div class="space-y-6">
                            <h1
                                class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl/none text-gray-900 dark:text-gray-50">
                                <span>Welcome to </span>
                                <span class="text-pink-500 dark:text-pink-300">
                                    Waktu Solat API
                                </span>
                                <span>!</span>
                            </h1>
                            <p class="mx-auto max-w-[700px] text-gray-500 md:text-xl dark:text-gray-300">
                                This REST API provides accurate prayer times for all
                                <a class="font-semibold text-pink-500 hover:text-pink-400 dark:text-pink-300 dark:hover:text-pink-200"
                                    href="/locations">
                                    locations
                                </a>
                                in Malaysia, based on data from
                                <a class="font-semibold text-pink-500 hover:text-pink-400 dark:text-pink-300 dark:hover:text-pink-200"
                                    href="https://www.e-solat.gov.my/" target="_blank" rel="noopener noreferrer">
                                    JAKIM
                                </a>.
                            </p>
                        </div>
                        <div class="space-x-4">
                            <a href="/docs"
                                class="inline-flex h-9 items-center justify-center rounded-md bg-gray-900 px-4 py-2 sm:px-6 sm:py-4 text-sm font-medium text-gray-50 shadow transition-colors hover:bg-gray-900/80 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950 disabled:pointer-events-none disabled:opacity-50 dark:bg-pink-500 dark:text-white dark:hover:bg-pink-600 dark:focus-visible:ring-pink-300 group">
                                <span>Get Started</span>
                                <x-ionicon-chevron-forward class="h-4 w-4 ml-1" />
                            </a>
                            <a href="/health"
                                class="inline-flex h-9 items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2 sm:px-6 sm:py-4 text-sm font-medium shadow-sm transition-colors hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-gray-950 disabled:pointer-events-none disabled:opacity-50 dark:border-pink-500 dark:bg-transparent dark:text-pink-300 dark:hover:bg-pink-500/10 dark:hover:text-pink-200 dark:focus-visible:ring-pink-300">
                                Data health
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <x-footer />
    </body>
@endsection
