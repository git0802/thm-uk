<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionRenewalCanceled;
use Illuminate\Console\Command;
use App\Subscription;
use App\Repositories\SubscriptionRepository;
use Illuminate\Support\Facades\Mail;

class NotifyPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify_paid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription status notifications';

    /**
     * Create a new command instance.
     *
     * @param SubscriptionRepository $subscription
     */
    protected $subscription;

    public function __construct(SubscriptionRepository $subscription)
    {
        parent::__construct();
        $this->subscription = $subscription;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $notify = $this->subscription->getData('notify');
        $check  = now()->addDays($notify['expire_days']);
        $query  = Subscription::where('paid', '!=', 0)
                   ->where(function($query) {
                        $query->where('payment_method', '')
                            ->orWhereNull('payment_method');
                    })
                    ->whereDate('ending', $check);

        $query->each(function ($item) {
            $user = $item->user;
            Mail::to($user->email)->send(new SubscriptionRenewalCanceled($user));
        });
    }
}
