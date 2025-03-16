<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Leaderboard extends Model
{
    protected $guarded = [];

    protected $appends = [
        'logo_file_url'
    ];

    public function getLogoFileUrlAttribute()
    {
        return $this->logo_file_path ? asset(Storage::disk('public')->url($this->logo_file_path)) : null;
    }

    public function leaders(): HasMany
    {
        return $this->hasMany(Leader::class);
    }
}
