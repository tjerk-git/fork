<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Scenario;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Mail\MagicLoginLink;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    public function scenarios()
    {
        return $this->hasMany(Scenario::class);
    }

    public function loginTokens()
    {
        return $this->hasMany(LoginToken::class);
    }

    public function generateLoginToken()
    {
        // Invalidate any existing tokens
        $this->loginTokens()->delete();
        
        // Generate a simple 6-digit token
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->loginTokens()->create([
            'token' => hash('sha256', $token),
            'expires_at' => now()->addDays(7), // Tokens valid for 7 days
        ]);

        return $token;
    }
}
