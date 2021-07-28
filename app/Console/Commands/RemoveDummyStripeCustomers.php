<?php

namespace App\Console\Commands;

use App\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Csv\Writer;
use Stripe\StripeClient;

class RemoveDummyStripeCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:remove-dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove customers from stripe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subscriptions = Subscription::whereNull('payment_method')
            ->whereNull('subscription_plan_id')
            ->whereNull('subscription_id')
            ->whereNotNull('customer_id')
            ->whereNotNull('ending')
            ->where('paid', 1)
            ->where('start', '>', now()->subDays(15))
            ->with('user')
            ->get();

        $this->displayUsers($subscriptions);
        if ($this->confirm('Do you want to remove these customers from stripe?')) {
            $this->removeCustomers($subscriptions);
        }
        return 0;
    }

    private function removeCustomers($subscriptions)
    {
        $stripe = new StripeClient(env('STRIPE_API_KEY'));

        $users = collect();
        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;
            if ($user && $subscription->customer_id) {
                $this->info('Removing ==> ' . $user->name . ' | ' . $user->email . ' | ' . $subscription->customer_id);
                // Remove customer from stripe
                // Set customer_id = null, ending = null, start = null

                try {
                    $paymentMethods = $stripe->paymentMethods->all([
                        'customer' => $subscription->customer_id,
                        'type' => 'card',
                    ]);
                    if(sizeof($paymentMethods->data) > 0) {
                        $this->info('User has a payment method stored.');
                        continue;
                    }
                    $users->push([$user->id, $user->name, $user->email, $subscription->customer_id, $user->created_at]);

                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }


                $this->info('Removed ==> ' . $user->name . ' | ' . $user->email . ' | ' . $subscription->customer_id);
            }
        }
        if ($users->count() > 0) {
            $this->exportCSV($users->toArray(), 'removed_'.now()->timestamp);
            $this->info('TOTAL '. $users->count() . ' USERS REMOVED!');
        }
    }

    private function displayUsers($subscriptions)
    {
        $users = collect();

        foreach ($subscriptions as $subscription) {
            $user = $subscription->user;
            if ($user) {
                $this->info($user->name . ' | ' . $user->email . ' | ' . $subscription->customer_id);
                $users->push([$user->id, $user->name, $user->email, $subscription->customer_id, $user->created_at]);
            }
        }
        if ($users->count() > 0) {
            $this->exportCSV($users->toArray(), 'to_be_removed_'.now()->timestamp);
        }
    }

    private function exportCSV($users, $filename)
    {
        try {
            $writer = Writer::createFromPath(storage_path('app/stripe_users_'. $filename . '.csv'), 'w+');
            $writer->insertOne(['id', 'name', 'email', 'customer_id', 'created_at']);
            $writer->insertAll($users);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
