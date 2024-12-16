<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Board;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'order',
        'board_id',
        'priority'
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
