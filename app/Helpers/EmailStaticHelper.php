<?php


namespace App\Helpers;


use App\Mail\WeeklyStats;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class EmailStaticHelper
{

    public static function sendWeeklyReminder(User $user)
    {
        $date = Carbon::parse('last week')->startOfDay();
        $stats = StatsStaticHelper::extractPlannerData($user->planners()->where('starts', $date)->first());
        Mail::to($user->email)->send(new WeeklyStats($user, $stats));
    }

}
