<?php

namespace App\Http\Controllers;

use App\Announcements\Announcement;
use App\Announcements\AnnouncementRepository;

class SitemapController extends Controller
{
    public function __construct(private readonly AnnouncementRepository $announcements) {}

    public function __invoke()
    {
        $pages = collect(['home', 'scribe', 'announcements', 'data-health', 'feedback', 'submit-your-app'])
            ->map(fn (string $name) => ['loc' => route($name), 'lastmod' => null]);

        $posts = $this->announcements->all()
            ->map(fn (Announcement $announcement) => [
                'loc' => route('announcement', $announcement->slug),
                'lastmod' => $announcement->date->toDateString(),
            ]);

        return response()
            ->view('sitemap', ['urls' => $pages->concat($posts)])
            ->header('Content-Type', 'application/xml');
    }
}
