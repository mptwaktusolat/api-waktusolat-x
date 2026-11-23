@props(['href', 'linkText' => 'Read announcement'])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'top-banner group block border-b border-base-300 bg-base-100 text-base-content']) }}>
    <div class="max-w-7xl mx-auto flex items-center justify-center gap-3 px-4 py-3 sm:px-6">
        <span
            class="min-w-0 flex-1 text-left text-[13px] leading-[18px] sm:flex-none sm:text-center sm:text-sm sm:leading-5">
            {{ $slot }}
        </span>
        <span
            class="inline-flex shrink-0 items-center gap-1 text-sm font-semibold text-[#0058b0] underline-offset-4 group-hover:underline dark:text-[#8cc4ff]">
            <span class="hidden sm:inline">{{ $linkText }}</span>
            <x-ionicon-arrow-forward-outline aria-hidden="true"
                class="h-4 w-4 transition-transform group-hover:translate-x-0.5 motion-reduce:transition-none" />
        </span>
    </div>
</a>
