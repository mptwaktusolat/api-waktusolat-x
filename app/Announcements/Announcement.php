<?php

namespace App\Announcements;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;

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
        return Str::markdown($this->body, extensions: [new HeadingPermalinkExtension], options: [
            'heading_permalink' => [
                'html_class' => 'heading-anchor',
                'id_prefix' => '',
                'fragment_prefix' => '',
                'insert' => 'after',
                'min_heading_level' => 2,
                'max_heading_level' => 6,
                'symbol' => '#',
                'title' => 'Permalink',
            ],
        ]);
    }
}
