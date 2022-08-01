<?php

namespace App\Helpers;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Helper function for guest user.
 *
 * @package App\Helpers
 */
trait GuestHelper
{
    /**
     * Create and login guest user.
     *
     * @param $guestSettings
     * @return User
     */
    public function createGuestUser($guestSettings): User
    {
        $user = User::firstOrCreate(['email' => session()->getId()."@thehotmeal.com"], [
            'name' => $guestSettings['user']['name'],
            'last_name' => 'Guest',
            'weight' => $guestSettings['user']['weight'],
            'height' => $guestSettings['user']['height'],
            'age' => $guestSettings['user']['age'],
            'gender' => $guestSettings['user']['gender'],
            'phone' => 'guest',
            'goal' => -50,
            'initial_goal' => 0,
            'initial_calories_goal' => $guestSettings['user']['initial_calories_goal'],
            'avatar' => null,
            'is_activated' => true,
            'finished_setup' => false,
            'is_spam_wanted' => true,
            'password' => Hash::make(session()->getId()),
        ]);

        if ($user->activated_at === null) {
            $user->activated_at = now();
            $user->save();
        }

        return $user;
    }
}
