<?php

namespace App\View\Components;

use App\Models\PrayerTime;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;

class MonthAvailabilityCard extends Component
{
    public bool $isAvailable;
    public string $monthName;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public int $year,
        public int $monthNumber,
        public string $zoneCode,
    ) {
        $this->monthName = Carbon::create($this->year, $this->monthNumber)->format('F Y');
        $this->isAvailable = $this->checkDataAvailability();
    }

    /**
     * Check if prayer time data is available for the given month & year
     */
    private function checkDataAvailability(): bool
    {
        return PrayerTime::hasDataForMonth($this->zoneCode, $this->monthNumber, $this->year);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.month-availability-card');
    }
}
