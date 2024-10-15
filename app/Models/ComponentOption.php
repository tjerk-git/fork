<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentOption extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'component_id'];

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
