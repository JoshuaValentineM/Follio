<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            // dd($user);
            $finduser = User::where('google_id', $user->getId())->first();
            if ($finduser) {
                auth()->login($finduser);
                return redirect()->route('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'password' => bcrypt('12345678'),
                    'google_id' => $user->getId()
                ]);
                auth()->login($newUser);
                return redirect()->route('dashboard');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
