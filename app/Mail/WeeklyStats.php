<?php

namespace App\Mail;

use App\Planner;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyStats extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $stats;

    /**
     * WeeklyStats constructor.
     * @param User $user
     * @param $stats
     */
    public function __construct(
        User $user, $stats
    )
    {
        $this->user = $user;
        $this->stats = $stats;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.planner.weekly-stats', [
            'user' => $this->user,
            'stats' => $this->stats,
        ]);
    }
}
