<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keyword extends Model
{
    protected $fillable = ['word', 'step_id'];

    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }
}
