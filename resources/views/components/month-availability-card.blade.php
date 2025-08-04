<div
    class="p-4 bg-white/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm dark:shadow-gray-900/20 hover:shadow-md dark:hover:shadow-gray-900/30 transition-colors">
    <div class="flex flex-col">
        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $monthName }}</h3>
        <p
            class="text-sm font-medium {{ $isAvailable ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            {{ $isAvailable ? 'Data Available' : 'No Data' }}
        </p>
    </div>
</div>
