<?php

namespace App\Http\Middleware;

use App\Helpers\UserHelper;
use Closure;

class CheckIfAdmin
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
        if(!$this->isAdmin()) {
            abort(404);
        }
        return $next($request);
    }
}
