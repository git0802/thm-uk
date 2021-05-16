<?php

namespace App\Console\Commands;

use App\Helpers\EmailStaticHelper;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class WeeklyPlannerReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:weekly-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send every monday notification with stats and to update meal-planner';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $users = User::where('finished_setup', true)->where('is_spam_wanted', true)->whereHas('subscription', function(Builder $query) {
            $query->whereIn('paid', [1,2]);
        })->get();

        foreach ($users as $user) {
            EmailStaticHelper::sendWeeklyReminder($user);
        }


    }
}
