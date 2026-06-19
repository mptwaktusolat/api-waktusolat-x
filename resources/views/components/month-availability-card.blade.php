@php
    $tooltipText = match ($status) {
        'available' => 'All zones have prayer time data for this month.',
        'unavailable' => 'No prayer time data is available for this month.',
        default => 'Missing coverage: ' .
            collect($missingCoverage)
                ->map(fn(array $coverage) => $coverage['state'] . ' (' . implode(', ', $coverage['zones']) . ')')
                ->implode('; '),
    };

    $cardClass = match ($status) {
        'available' => 'bg-tile-green',
        'unavailable' => 'bg-base-300',
        default => 'bg-tile-yellow',
    };
@endphp

<div tabindex="0" aria-label="{{ $tooltipText }}" title="{{ $tooltipText }}"
    class="p-5 flex flex-col justify-between aspect-square transition-all duration-200 hover:brightness-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-base-content/20 {{ $cardClass }}">
    @if ($status === 'available')
        <x-ionicon-checkmark-outline class="h-6 w-6 text-white/80" />
    @elseif ($status === 'unavailable')
        <x-ionicon-close-circle-outline class="h-6 w-6 text-base-content/30" />
    @else
        <x-ionicon-alert-circle-outline class="h-6 w-6 text-black/70" />
    @endif

    <div>
        <p
            class="text-xs font-medium uppercase tracking-widest {{ $status === 'available' ? 'text-white/60' : ($status === 'unavailable' ? 'text-base-content/40' : 'text-black/60') }} mb-0.5">
            {{ $status === 'available' ? 'All zones' : ($status === 'unavailable' ? 'Unavailable' : 'Incomplete') }}
        </p>
        <h3
            class="text-lg font-semibold {{ $status === 'available' ? 'text-white' : ($status === 'unavailable' ? 'text-base-content/50' : 'text-black/90') }}">
            {{ $monthName }}
        </h3>
    </div>
</div>
