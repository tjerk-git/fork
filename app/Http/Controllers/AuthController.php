<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginToken;
use Illuminate\Support\Facades\Auth;
use App\Mail\LoginToken as LoginTokenMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::whereEmail($data['email'])->first();
        $token = $user->generateLoginToken();
        
        Mail::to($user->email)->send(new LoginTokenMail($token));
        
        session()->flash('success', true);
        session()->put('email', $data['email']);
        return redirect()->back();
    }

    public function showVerifyToken()
    {
        return view('auth.verify-token');
    }

    public function verifyToken(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'size:6'],
        ]);

        $email = session('email');
        $user = User::whereEmail($email)->firstOrFail();
        $token = $user->loginTokens()->where('token', hash('sha256', $data['token']))->first();

        if (!$token || !$token->isValid()) {
            return back()->withErrors(['token' => 'Invalid or expired token']);
        }

        Auth::login($user, true);
        $token->delete();

        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
