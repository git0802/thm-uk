<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function __invoke(Request $request)
    {
        if ($tokenId = Str::before(request()->bearerToken(), '|')) {
            Auth::guard('sanctum')->user()->tokens()->where('id', $tokenId)->delete();
        }

        return response([
            'success' => true,
        ], 200);
    }
}
