<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Step;
use App\Models\Result;

class ResultLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'step_id',
        'value',
        'type',
    ];

    // belongs to a step 
    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    // belongs to a result
    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}
