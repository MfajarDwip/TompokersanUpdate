<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visit extends Model
{
    protected $table = 'visits';

    protected $fillable = ['*'];
    protected $appends = ['duration'];

    public function getDurationAttribute()
    {
        if ($this->attributes['visited_at']) {
            $lastVisitTime = Carbon::parse($this->attributes['visited_at']);
            $currentTime = now();
            return $lastVisitTime->diffInSeconds($currentTime);
        }

        return null;
    }
}
