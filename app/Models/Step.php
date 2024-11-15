<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scenario;
use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'multiple_choice_question',
        'multiple_choice_option_1',
        'multiple_choice_option_2',
        'multiple_choice_option_3',
    ];

    // belongs to scenario
    public function scenario()
    {
        return $this->belongsTo(Scenario::class);
    }
}
