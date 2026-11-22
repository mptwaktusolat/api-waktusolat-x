<?php

namespace App\Announcements;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

final readonly class Announcement
{
    private const DISPLAY_DATE_FORMAT = 'j F Y';

    public function __construct(public string $slug, public string $title, public string $summary, public CarbonImmutable $date, public string $body) {}

    public function displayDate(): string
    {
        return $this->date->format(self::DISPLAY_DATE_FORMAT);
    }

    public function html(): string
    {
        return Str::markdown($this->body);
    }
}
