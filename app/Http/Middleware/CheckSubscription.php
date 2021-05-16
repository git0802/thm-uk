<?php

namespace App\Http\Middleware;

use App\Helpers\UserHelper;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    use UserHelper;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if($this->getUser()) {
            if(!($this->getUser()->checkPaid())) {
                return response([
                    'success' => false,
                    'message' => 'You cant perform this action because your subscription is over'
                ], 402);
            } else {
                return $next($request);
            }
        }

        return $next($request);
    }
}
