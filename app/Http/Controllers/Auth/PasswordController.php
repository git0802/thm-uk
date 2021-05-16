<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPassword;
use App\Http\Requests\Auth\SendResetLink;
use App\Http\Requests\ChangePassword;
use App\Mail\PasswordReset;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Reset password.
 *
 * @package App\Http\Controllers\Auth
 */
class PasswordController extends Controller
{

    use UserHelper;

    /**
     * Send email with reset link.
     *
     * @param SendResetLink $request
     * @return Response
     */
    public function send(SendResetLink $request): Response
    {
        $user = User::email($request->email)->first();
        $user->reset_password_token = Str::random(60);
        $user->save();

        Mail::to($user->email)
//            ->subject("Reset password on The Hot Meal")
            ->send(new PasswordReset($user));

        return response([
            'success' => true,
            'message' => 'Please, check your email to continue.'
        ]);
    }

    /**
     * Reset password.
     *
     * @param ResetPassword $request
     * @return Response
     */
    public function reset(ResetPassword $request): Response
    {
        $user = User::where('reset_password_token', $request->token)->first();
        $user->reset_password_token = null;
        $user->password = Hash::make($request->password);
        $user->save();

        return response([
            'success' => true,
            'message' => 'Please, sign in with your new password.'
        ]);
    }


    // TODO: when password is changed - kill all tokens and force user to reauth

    public function change(ChangePassword $request): Response
    {
        $user = $this->getUser();

        if (!Hash::check($request->get('old_password'), $user->password)) {
            return response([
                'success' => false,
                'errors' => [
                    'old_password' => ['Old password is incorrect!']
                ]
            ], 422);
        }

        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        $user->tokens()->delete();

        return response([
            'success' => true,
            'message' => 'Password was successfully changed!'
        ], 200);
    }

}
