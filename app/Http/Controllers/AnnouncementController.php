<?php

namespace App\Http\Controllers;

use App\Announcements\AnnouncementRepository;

class AnnouncementController extends Controller
{
    public function __construct(private readonly AnnouncementRepository $announcements) {}

    public function index()
    {
        return view('announcements', [
            'announcements' => $this->announcements->all(),
        ]);
    }

    public function show(string $slug)
    {
        $announcement = $this->announcements->find($slug);

        abort_if($announcement === null, 404);

        return view('announcement', compact('announcement'));
    }
}
