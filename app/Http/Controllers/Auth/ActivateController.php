<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;

class ActivateController extends Controller
{
    public function __invoke($activation_token)
    {
        $user = User::where('activation_token', $activation_token)->first();

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'Token not found!'
            ], 422);
        }

        if ($user->activated_at !== null) {
            return response([
                'success' => false,
                'message' => 'User already activated!'
            ], 422);
        }

        $user->activated_at = now();
        $user->save();

        return response([
            'success' => true,
            'message' => 'Your account successfully activated! You can login to your account now.'
        ]);
    }
}
