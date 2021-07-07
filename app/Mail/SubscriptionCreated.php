<?php

namespace App\Mail;

use App\Repositories\SubscriptionRepository;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $subscription = new SubscriptionRepository();

        $this->user  = $user;
        $this->price = $subscription->getSubscriptionRepo($this->user->subscription->subscriptionPlan->name);

        if ( $this->user->subscription->start == $this->user->subscription->ending ) {
            $this->user->subscription->ending = $this->user->subscription->ending->addMonths($this->user->subscription->subscriptionPlan->months);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Subscription Details')
            ->view('emails.subscription.created', [
                'user'   => $this->user,
                'price'  => $this->price,
                'coupon' => $this->user->coupon
            ]);
    }
}
