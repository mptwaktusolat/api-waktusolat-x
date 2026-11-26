<?php

namespace App\Announcements;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;

final readonly class Announcement
{
    private const DISPLAY_DATE_FORMAT = 'j F Y';

    public function __construct(public string $slug, public string $title, public string $summary, public CarbonImmutable $date, public string $body, public ?string $image = null) {}

    /**
     * Absolute URL of the social share (og:image / twitter:image) cover, if one is set.
     */
    public function imageUrl(): ?string
    {
        if ($this->image === null || $this->image === '') {
            return null;
        }

        return Str::startsWith($this->image, ['http://', 'https://'])
            ? $this->image
            : asset(ltrim($this->image, '/'));
    }

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
            ],
        ]);
    }
}
