<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Throwable;

class FirebaseController extends Controller
{
    /**
     * Handle an incoming Firebase (e.g. Google) sign-in.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        try {
            $verified = app(FirebaseAuth::class)->verifyIdToken($request->input('id_token'));
        } catch (Throwable $e) {
            abort(401, 'Invalid Firebase token.');
        }

        $claims = $verified->claims();
        $uid = (string) $claims->get('sub');
        $email = $claims->get('email');
        $emailVerified = (bool) $claims->get('email_verified', false);
        $name = (string) $claims->get('name', '');

        $user = User::where('firebase_uid', $uid)->first();

        if (! $user && $email && $emailVerified) {
            $user = User::whereNull('firebase_uid')->where('email', $email)->first();
        }

        if ($user) {
            if ($user->firebase_uid === null) {
                $user->firebase_uid = $uid;
                $user->save();
            }
        } else {
            if ($email && User::where('email', $email)->exists()) {
                abort(409, 'An account with this email already exists. Please sign in with your password.');
            }

            $user = User::create([
                'name' => $name ?: (explode('@', (string) $email)[0] ?? 'User'),
                'email' => (string) $email,
                'password' => Hash::make(Str::random(40)),
                'firebase_uid' => $uid,
            ]);

            if ($emailVerified) {
                $user->email_verified_at = now();
                $user->save();
            }
        }

        Auth::login($user, true);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
