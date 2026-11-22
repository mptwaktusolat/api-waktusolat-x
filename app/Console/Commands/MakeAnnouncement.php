<?php

namespace App\Console\Commands;

use App\Announcements\AnnouncementRepository;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use RuntimeException;

use function Laravel\Prompts\text;

#[Signature('app:make-announcement')]
#[Description('Create a new announcement markdown file dated today')]
class MakeAnnouncement extends Command
{
    public function handle(AnnouncementRepository $announcements): int
    {
        $title = text(
            label: 'Announcement title',
            required: true,
            validate: fn (string $value) => Str::slug($value) === ''
                ? 'The title needs at least one letter or number.'
                : null,
        );

        try {
            $path = $announcements->create(trim($title), Date::today());
        } catch (RuntimeException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->components->info("Announcement created at [{$path}].");

        return self::SUCCESS;
    }
}
