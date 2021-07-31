<?php

namespace App\Mail;

use App\Repositories\SubscriptionRepository;
use App\SubscriptionPlan;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $price;

    /**
     * Create a new message instance.
     *
     * @param User $user
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
        return $this->subject('Activate Your Meal Planner Account')
                    ->view('emails.activate', [
                        'user'   => $this->user,
                        'price'  => $this->price,
                        'coupon' => $this->user->coupon
                    ]);
    }
}
