<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Playlist/Create', [
            'tracks' => Track::with('user:id,name')->orderBy('title')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $playlist = $request->user()->playlists()->create($validated);
        /*$tracks = Track::hydrate($request->get('tracks'));

        foreach ($tracks as $index => $track) {
            $playlist->tracks()->save($track, ['order' => $index]);
        }*/

        return redirect(route('dashboard'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Inertia\Response
     */
    public function show(Playlist $playlist)
    {
        return Inertia::render('Playlist/Show', [
            'playlist' => $playlist,
            'tracks' => $playlist->tracks()->with('user:id,name')->orderBy('order')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Inertia\Response
     */
    public function edit(Playlist $playlist)
    {
        return Inertia::render('Playlist/Edit', [
            'playlist' => $playlist,
            'content' => $playlist->tracks()->with('user:id,name')->get(),
            'tracks' => Track::with('user:id,name')->orderBy('title')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Playlist $playlist)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $playlist->update($validated);
        $tracks = Track::hydrate($request->get('tracks'));

        $data = [];
        foreach ($tracks as $index => $track) {
            $data[$track->id] = ['order' => $index];
        }

        $playlist->tracks()->sync($data);

        return redirect(route('playlists.show', $playlist));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Playlist  $playlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);
        $playlist->delete();

        return redirect(route('dashboard'));
    }
}
