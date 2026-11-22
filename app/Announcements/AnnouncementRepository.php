<?php

namespace App\Announcements;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Extension\FrontMatter\Data\SymfonyYamlFrontMatterParser;
use League\CommonMark\Extension\FrontMatter\FrontMatterParser;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class AnnouncementRepository
{
    public const SLUG_PATTERN = '\d{4}-\d{2}-\d{2}-[a-z0-9]+(?:-[a-z0-9]+)*';

    private const FILE_EXTENSION = '.md';

    private readonly string $directory;

    private readonly FrontMatterParser $frontMatterParser;

    public function __construct()
    {
        $this->directory = resource_path('announcements');
        $this->frontMatterParser = new FrontMatterParser(new SymfonyYamlFrontMatterParser);
    }

    /**
     * @return Collection<int, Announcement>
     */
    public function all(): Collection
    {
        $files = glob($this->directory.'/*'.self::FILE_EXTENSION) ?: [];

        return collect($files)
            ->map(fn (string $path) => basename($path, self::FILE_EXTENSION))
            ->filter(fn (string $slug) => $this->isValidSlug($slug))
            ->map(fn (string $slug) => $this->load($slug))
            ->sortByDesc(fn (Announcement $announcement) => $announcement->date)
            ->values();
    }

    public function find(string $slug): ?Announcement
    {
        if (!$this->isValidSlug($slug)) {
            return null;
        }

        if (!is_file($this->pathFor($slug))) {
            return null;
        }

        return $this->load($slug);
    }

    /**
     * @return string Path of the created markdown file.
     *
     * @throws RuntimeException When an announcement with the same slug already exists.
     */
    public function create(string $title, CarbonInterface $date): string
    {
        $slug = $date->format('Y-m-d').'-'.Str::slug($title);
        $path = $this->pathFor($slug);

        if (is_file($path)) {
            throw new RuntimeException("Announcement [{$slug}] already exists.");
        }

        File::ensureDirectoryExists($this->directory);

        $frontMatter = Yaml::dump(['title' => $title, 'summary' => '']);
        File::put($path, "---\n{$frontMatter}---\n\n");

        return $path;
    }

    private function load(string $slug): Announcement
    {
        $parsed = $this->frontMatterParser->parse(file_get_contents($this->pathFor($slug)));
        $frontMatter = $parsed->getFrontMatter() ?? [];

        return new Announcement(
            slug: $slug,
            title: $frontMatter['title'] ?? $slug,
            summary: $frontMatter['summary'] ?? '',
            date: CarbonImmutable::parse(substr($slug, 0, 10)),
            body: $parsed->getContent(),
        );
    }

    private function isValidSlug(string $slug): bool
    {
        return preg_match('/^'.self::SLUG_PATTERN.'$/', $slug) === 1;
    }

    private function pathFor(string $slug): string
    {
        return $this->directory.'/'.$slug.self::FILE_EXTENSION;
    }
}
