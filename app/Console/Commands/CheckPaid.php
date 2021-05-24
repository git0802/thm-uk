<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Subscription;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class CheckPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_paid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking the status of subscriptions';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $stripe = new StripeClient( env('STRIPE_API_KEY') );

        $subscriptions = Subscription::where('paid', '!=', 0)->get();

        foreach ($subscriptions as $subscription) {

            if(isset($subscription->subscription_id)){

                // $this->info(json_encode($subscription,JSON_PRETTY_PRINT));

                try {

                    $stripeSubscription = $stripe->subscriptions->retrieve($subscription->subscription_id,[]);

                    // $this->info(json_encode($stripeSubscription,JSON_PRETTY_PRINT));

                    if(isset($stripeSubscription->status) && in_array(strtolower(trim($stripeSubscription->status)), ['canceled']) ){

                        $canceledTime = ( isset($stripeSubscription->canceled_at) ? Carbon::createFromTimestamp($stripeSubscription->canceled_at) : now() );

                        // $this->info("canceled time : ".$canceledTime->toDateTimeString());

                        // $subscription->ending = $canceledTime;

                        $subscription->payment_method  = null;
                        $subscription->subscription_id = null;

                        $subscription->paid = 0;

                    }
                    else{
                        $subscription->ending = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
                    }
                    $subscription->save();

                } catch (InvalidRequestException $exception) {
                    $this->error($exception->getMessage());
                }
            }
        }

        $check = now()->subDay();

        Subscription::where('paid', '!=', 0)
            ->whereDate('ending', '<=', $check)
            ->update(['paid' => 0]);
    }
}
