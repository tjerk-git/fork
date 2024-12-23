<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Step;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'user_id', 'attachment', 'slug', 'access_code', 'is_public', 'ask_for_name'];

    protected $casts = [
        'ask_for_name' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($scenario) {
            if ($scenario->attachment) {
                Storage::disk('public')->delete($scenario->attachment);
            }
        });

        static::creating(function ($scenario) {
            $slug = Str::slug($scenario->name) . '-' . Str::random(8);

            while (Scenario::where('slug', $slug)->exists()) {
            $slug = Str::slug($scenario->name) . '-' . Str::random(8);
            }

            $scenario->slug = $slug;
        });

        static::updating(function ($scenario) {
        if ($scenario->isDirty('name')) {
            $slug = Str::slug($scenario->name) . '-' . Str::random(8);

            while (Scenario::where('slug', $slug)->exists()) {
                $slug = Str::slug($scenario->name) . '-' . Str::random(8);
            }

            $scenario->slug = $slug;
        }
        });
    }
}
