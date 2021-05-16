<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ResendActivation;
use App\Http\Resources\CustomersResource;
use App\Mail\ActivateUser;
use App\User;
use App\Helpers\UserHelper;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;

/**
 * Customer controller.
 *
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    use UserHelper;

    /**
     * CustomerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get customers.
     *
     * @return AnonymousResourceCollection
     */
    public function all(): AnonymousResourceCollection
    {
        if (!$this->isAdmin()) {
            abort(404);
        }

        return CustomersResource::collection(User::where('is_admin', false)->get());
    }

    /**
     * @param ResendActivation $request
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resendActivation(ResendActivation $request, User $user)
    {
        if(!$user->activated_at) {
            Mail::to($user->email)->send(new ActivateUser($user));
            return response([
                'success' => true,
                'message' => 'Activation mail send successfully!',
            ]);
        } else {
            return response([
                'success' => false,
                'message' => 'Customer account is already activated!',
            ], 422);
        }

    }

}
