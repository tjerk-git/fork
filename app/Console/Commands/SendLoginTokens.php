<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\LoginToken as LoginTokenMail;
use Illuminate\Support\Facades\Mail;

class SendLoginTokens extends Command
{
    protected $signature = 'auth:send-tokens';
    protected $description = 'Send new login tokens to all users';

    public function handle()
    {
        $users = User::all();
        $count = 0;

        $this->info('Sending login tokens to ' . $users->count() . ' users...');

        foreach ($users as $user) {
            $token = $user->generateLoginToken();
            Mail::to($user->email)->send(new LoginTokenMail($token));
            $count++;
            
            $this->info("Sent token to {$user->email}");
        }

        $this->info("Successfully sent {$count} login tokens");
    }
}
