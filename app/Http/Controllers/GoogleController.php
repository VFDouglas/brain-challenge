<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            $findUser = User::query()->where('email', '=', $user->email)->first();

            if ($findUser) {
                Auth::login($findUser);
                session(['user_id' => $findUser->id]);

                return redirect('/');
            } else {
                $newUser = User::query()->create([
                    'name'     => $user->name,
                    'email'    => $user->email,
                    'password' => Hash::make(Str::random(8))
                ]);
                session(['user_id' => $newUser->id]);

                Auth::login($newUser);

                return redirect()->intended();
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
