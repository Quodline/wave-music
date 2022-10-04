<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $tracks = Track::with('user:id,name')
            ->where('user_id', '=', Auth::id())
            ->latest()->get();

        $playlists = Playlist::with('user:id,name')
            ->with('tracks:id,seconds,title')
            ->where('user_id', '=', Auth::id())
            ->latest()->get();

        return Inertia::render('Dashboard', [
            'tracks' => $tracks,
            'playlists' => $playlists,
        ]);
    }
}
