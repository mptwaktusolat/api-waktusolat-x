<div
    class="p-5 flex flex-col justify-between aspect-square transition-all duration-200 hover:brightness-110 {{ $isAvailable ? 'bg-tile-green' : 'bg-base-300' }}">
    <x-ionicon-checkmark-outline class="h-6 w-6 {{ $isAvailable ? 'text-white/80' : 'text-base-content/30' }}" />
    <div>
        <p
            class="text-xs font-medium uppercase tracking-widest {{ $isAvailable ? 'text-white/60' : 'text-base-content/40' }} mb-0.5">
            {{ $isAvailable ? 'Available' : 'No Data' }}
        </p>
        <h3 class="text-lg font-semibold {{ $isAvailable ? 'text-white' : 'text-base-content/50' }}">{{ $monthName }}
        </h3>
    </div>
</div>
