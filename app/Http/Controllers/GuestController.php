<?php

namespace App\Http\Controllers;

use App\Helpers\GuestHelper;
use App\Http\Requests\UpdateGuestSettings;
use App\Http\Resources\User\UserResource;
use App\Setting;
use App\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    use GuestHelper;

    public function login(Request $request)
    {
        $settings = Setting::where('name', 'guest')->first();

        $guestSettings = $settings->settings_json;

        if ($guestSettings['enabled']) {
            if (Auth::guard('sanctum')->check() === false) {
                $user = $this->createGuestUser($guestSettings);
                auth()->login($user);
            } else {
                $user = Auth::guard('sanctum')->user();
            }

            if ($user->updated_at->diffInSeconds(\Carbon\Carbon::now()) > 10) {
                $user->planners()->delete();
            }

            return response([
                'success' => Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->is_guest,
                'token' => $user->createToken($request->server('HTTP_USER_AGENT'))->plainTextToken,
                'user' => [
                    'user' => UserResource::make($user),
                    'subscription' => 0,
                ],
                'subscription' => [
                    'message' => '',
                    'subscription' => true,
                    'is_cancelled' => false,
                    'is_expired' => false,
                ]
            ]);
        }
        return response([
            'success' => false,
        ]);
    }

    public function settings()
    {
        $settings = Setting::where('name', 'guest')->first();

        if(!$settings) {
            return response([
                'success' => false,
            ], 404);
        }

        return response([
            'success' => true,
            'settings'   => $settings->settings_json
        ]);
    }

    public function updateSettings(UpdateGuestSettings $request)
    {
        $settings = Setting::where('name', 'guest')->first();

        $guestSettings = $settings->settings_json;

        $guestSettings['enabled'] = $request->get('settings')['enabled'];
        $guestSettings['user']['name'] = $request->get('settings')['user']['name'];
        $guestSettings['user']['weight'] = $request->get('settings')['user']['weight'];
        $guestSettings['user']['height'] = $request->get('settings')['user']['height'];
        $guestSettings['user']['age'] = $request->get('settings')['user']['age'];
        $guestSettings['user']['gender'] = $request->get('settings')['user']['gender'];

        $genderCorrectingValue = $guestSettings['user']['gender'] == 'male' ? 5 : -161;
        // Mifflin-St Jeor Equation
        $maintainCalories = round((10 * ($guestSettings['user']['weight'] * 0.45359237) + 6.25 * $guestSettings['user']['height'] - 5 * $guestSettings['user']['age'] + $genderCorrectingValue) * 1.2);
        $guestSettings['user']['initial_calories_goal'] = $maintainCalories;

        $settings->settings_json = $guestSettings;
        $settings->save();
        return response([
            'success' => true,
            'message' => __('guest.settings.updated'),
            'links'   => $settings->settings_json
        ], 201);
    }

    public function ping()
    {
        if (Auth::guard('sanctum')->check() && Auth::guard('sanctum')->user()->is_guest) {
            Auth::guard('sanctum')->user()->touch();
        }
    }
}
