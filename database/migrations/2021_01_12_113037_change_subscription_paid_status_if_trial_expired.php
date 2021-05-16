<?php

use Illuminate\Database\Migrations\Migration;

class ChangeSubscriptionPaidStatusIfTrialExpired extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $subscriptions = \App\Subscription::all();
        foreach ($subscriptions as $subscription) {
            // check if user made subscription and expired, and set paid status to false
            if ($subscription->created_at->toString() === $subscription->updated_at->toString()
                && $subscription->ending < \Carbon\Carbon::now()
            ) {
                $subscription->paid = (bool) 0;
                $subscription->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
