<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $fillable = ['file', 'title', 'seconds'];
    protected $touches = ['playlists'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_track')
            ->using(PlaylistTrack::class)
            ->withTimestamps()
            ->withPivot('order');
    }
}
