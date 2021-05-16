<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /**
     * User login.
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::email($request->get('email'))->first();

        if (!Hash::check($request->get('password'), $user->password)) {
            return response([
                'success' => false,
                'errors' => [
                    'password' => ['Invalid password!']
                ]
            ], 422);
        }


        if (!$user->activated_at) {
            return response([
                'success' => false,
                'message' => 'Please, confirm your email to continue!'
            ], 422);
        }

        return response([
            'success' => true,
            'token' => $user->createToken($request->server('HTTP_USER_AGENT'))->plainTextToken,
            'user' => UserResource::make($user),
        ]);
    }
}
