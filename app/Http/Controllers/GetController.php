<?php

namespace App\Http\Controllers;

use App\Repositories\SubscriptionRepository;
use App\SubscriptionPlan;

class GetController extends Controller
{
    public function price(SubscriptionRepository $subscription)
    {
        return response( SubscriptionPlan::orderBy('months')->get() );
    }
}
