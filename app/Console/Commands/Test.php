<?php

namespace App\Console\Commands;

use App\Helpers\EmailStaticHelper;
use App\Helpers\StatsStaticHelper;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use App\Mail\WeeklyStats;
use Hash;
use Mail;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
      $admin = User::where('email', 'aidonline01@gmail.com')->first();
      $stats = StatsStaticHelper::extractPlannerData($admin->planners()->first());
      Mail::to('aidonline01@gmail.com')->send(new WeeklyStats($admin, $stats));
      Mail::to('tara@thehotmeal.com')->send(new WeeklyStats($admin, $stats));
    }
}
