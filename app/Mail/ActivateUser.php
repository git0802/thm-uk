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
        $this->user  = $user;
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
                    ]);
    }
}
