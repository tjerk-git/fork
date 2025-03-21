<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ResultLine;
use App\Models\Keyword;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'condition',
        'description',
        'fork_to_step',
        'scenario_id',
        'attachment',
        'open_question',
        'question_type',
        'multiple_choice_question',
        'multiple_choice_option_1',
        'multiple_choice_option_2',
        'multiple_choice_option_3',
        'hidden',
        'fork_condition',
    ];

    // belongs to scenario
    public function scenario(): BelongsTo
    {
        return $this->belongsTo(Scenario::class);
    }

    public function resultLines(): HasMany
    {
        return $this->hasMany(ResultLine::class);
    }

    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }
}
