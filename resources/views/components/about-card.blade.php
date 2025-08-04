<a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
    class="group relative overflow-hidden rounded-xl bg-gradient-to-br {{ $gradient }} p-6 text-white transition-all duration-300 hover:scale-105 hover:shadow-lg">
    <div class="relative z-10">
        <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
            @if ($icon)
                {!! $icon !!}
            @else
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                    <circle cx="10" cy="10" r="8" stroke-width="2" fill="currentColor" />
                </svg>
            @endif
        </div>
        {{ $slot }}
    </div>
</a>
