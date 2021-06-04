<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use App\Notifications\SendEmailToSupport;
use Illuminate\Support\Facades\Notification;

class SupportController extends Controller
{
    public function __invoke(SupportRequest $request)
    {
        Notification::route('mail', env('HELLO_EMAIL'))
            ->notify(new SendEmailToSupport($request->all()));

        return response([
            'success' => true,
            'message' => 'Your message has been successfully sent. We will contact you shortly!'
        ]);
    }
}
