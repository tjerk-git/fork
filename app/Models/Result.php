<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResultLine;
use App\Models\Scenario;


class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'session',
        'ip',
        'browser',
        'time_started',
        'time_ended',
        'scenario_id',
    ];

    // belongs to a scenario
    public function scenario()
    {
        return $this->belongsTo(Scenario::class);
    }

    // has many result lines
    public function lines()
    {
        return $this->hasMany(ResultLine::class);
    }
}
