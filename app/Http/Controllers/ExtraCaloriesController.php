<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use App\Planner;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExtraCaloriesController extends Controller
{
    /**
     * Planner controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function list(Planner $planner, Request $request) : JsonResponse
    {
        abort_unless($request->user()->id == $planner->user_id, 403, 'You are not the owner of that week planner!');

        $date = Carbon::parse($request->get('day'))->startOfDay();
        $extras = $planner->extras()->where('date', $date)->get();

        return response()->json($extras);
    }

    public function delete(Planner $planner, Request $request, $id) : Response
    {
        abort_unless($request->user()->id == $planner->user_id, 403, 'You are not the owner of that week planner!');
        $extra = $planner->extras()->findOrFail($id);
        $extra->delete();

        return response()->noContent();
    }
}
