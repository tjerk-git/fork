<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Step;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'body',
        'required',
        'step_id',
    ];

    // belongs to step
    public function step()
    {
        return $this->belongsTo(Step::class);
    }
    // has many options
    public function options()
    {
        return $this->hasMany(ComponentOption::class);
    }
}
