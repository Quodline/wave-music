<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistTrack extends Pivot
{
    protected $fillable = ['order'];
}
