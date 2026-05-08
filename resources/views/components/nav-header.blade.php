<header class="border-b border-base-300 bg-base-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-tile-green flex items-center justify-center shrink-0">
                <x-ionicon-sunny-outline class="h-6 w-6 text-white" />
            </div>
            <span class="text-2xl font-light tracking-tight text-base-content">apiwaktusolat</span>
        </a>

        <nav class="flex items-center gap-4">
            @if (Route::is('home'))
                <a href="#example"
                    class="text-sm text-base-content/60 hover:text-base-content transition-colors hidden sm:block">
                    Example
                </a>
                <a href="#faq"
                    class="text-sm text-base-content/60 hover:text-base-content transition-colors hidden sm:block">
                    FAQ
                </a>
            @endif

            {{-- Theme toggle button --}}
            <button id="theme-toggle"
                class="w-10 h-10 flex items-center justify-center bg-base-200 hover:bg-base-300 transition-all"
                aria-label="Toggle theme">
                {{-- System --}}
                <x-ionicon-desktop-outline data-theme-icon="system" class="h-5 w-5 text-base-content" />
                {{-- Light --}}
                <x-ionicon-sunny-outline data-theme-icon="light" class="h-5 w-5 text-base-content hidden" />
                {{-- Dark --}}
                <x-ionicon-moon-outline data-theme-icon="dark" class="h-5 w-5 text-base-content hidden" />
            </button>
        </nav>
    </div>
</header>
