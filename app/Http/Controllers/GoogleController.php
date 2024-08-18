<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect()->route('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'role_id' => 2,
                    'email_verified_at' => now(),
                ]);

                Wallet::create([
                    'user_id' => $user->id,
                    'earning' => 0,
                    'balance' => 0,
                ]);

                Auth::login($newUser);
                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
