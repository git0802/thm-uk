<?php

namespace App\Console\Commands;

use App\Helpers\EmailStaticHelper;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\StatsStaticHelper;
use Mail;
use App\Mail\WeeklyStats;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test {email=admin@thehotmeal.com} {--last}';

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
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            $this->error("User with given email not found");
            return;
        }

        $sendLast = $this->option('last');
        $planner = null;
        if ($sendLast) {
            $date = Carbon::parse('last week')->startOfDay();
            $planner = $user->planners()->where('starts', $date)->first();
        } else {
            $planner = $user->planners->last();
        }
        if ($planner) {
            $stats = StatsStaticHelper::extractPlannerData($planner);
            Mail::to($user->email)->send(new WeeklyStats($user, $stats));
            $this->info('Weekly stats email is sent to ' .  $user->email);
        } else {
            $this->error('Planner not found');
        }

    }
}
