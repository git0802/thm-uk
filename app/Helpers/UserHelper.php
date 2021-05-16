<?php

namespace App\Helpers;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * Helper function to get authenticated user.
 *
 * @package App\Helpers
 */
trait UserHelper
{
    /**
     * Get authenticated user id.
     *
     * @return int|string|null
     */
    public function id()
    {
        return Auth::guard('sanctum')->id();
    }

    /**
     * Get authenticated user.
     *
     * @return User|Authenticatable|null
     */
    public function getUser()
    {
        return Auth::guard('sanctum')->user();
    }

    /**
     * Is user activated.
     *
     * @return boolean
     */
    public function activated()
    {
        $user = $this->getUser();

        if (!$user) {
            return false;
        } else {
            return $user->activated_at !== null;
        }
    }

    public function isAdmin()
    {
        $user = $this->getUser();

        return $user !== null ? $user->is_admin : false;
    }
}
