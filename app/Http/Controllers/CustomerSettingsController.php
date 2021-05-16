<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Http\Requests\ChangeEmail;
use App\Http\Requests\ChangeEmailSpam;
use App\Http\Requests\ChangeMetrics;
use App\Http\Requests\ChangeName;
use App\Http\Requests\ChangePhone;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerSettingsController extends Controller
{
    use UserHelper;

    public function __construct()
    {

    }


    /**
     * @param ChangeEmail $request
     * @return Response
     */
    public function changeEmail(ChangeEmail $request): Response
    {
        $user = $this->getUser();

        $user->email = $request->get('email');
        $user->save();

        return response([
            'success' => true,
            'message' => 'Email was successfully changed!'
        ], 200);
    }

    /**
     * @param ChangeName $request
     * @return Response
     */
    public function changeName(ChangeName $request): Response
    {
        $user = $this->getUser();

        $user->update($request->only(['name', 'last_name']));
        return response([
            'success' => true,
            'message' => 'Name was successfully changed!',
            'user'    => UserResource::make(Auth::guard('sanctum')->user())
        ], 200);
    }


    /**
     * @param ChangePhone $request
     * @return Response
     */
    public function changePhone(ChangePhone $request): Response
    {
        $user = $this->getUser();
        $user->update($request->only(['phone']));

        return response([
            'success' => true,
            'message' => 'Phone number was successfully changed!',
            'user'    => UserResource::make(Auth::guard('sanctum')->user())
        ], 200);
    }

    /**
     * @param ChangeMetrics $request
     * @return Response
     */
    public function changeMetrics(ChangeMetrics $request): Response
    {
        $user = $this->getUser();
        $user->update($request->only(['weight', 'height', 'age', 'gender']));

        return response([
            'success' => true,
            'message' => 'Your metrics was successfully changed!',
            'user'    => UserResource::make(Auth::guard('sanctum')->user())
        ], 200);

    }

    /**
     * @param ChangeEmailSpam $request
     * @return Response
     */
    public function changeSpam(ChangeEmailSpam $request): Response
    {
        $user = $this->getUser();
        $user->update($request->only(['is_spam_wanted']));

        return response([
            'success' => true,
            'message' => 'You successfully updated your mailing settings',
            'user'    => UserResource::make(Auth::guard('sanctum')->user())
        ], 200);
    }

}
