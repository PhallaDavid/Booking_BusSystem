<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google.');
        }

        $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->save();
            }
            Auth::login($user);
            return redirect('/dashboard');
        }

        $newUser = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'password' => Hash::make(Str::random(24)) // Or null if you handled that
        ]);

        Auth::login($newUser);
        return redirect('/dashboard');
    }

    public function handleApiCallback(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        try {
            $googleUser = Socialite::driver('google')->userFromToken($request->token);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->save();
            }
        } else {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(24))
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
