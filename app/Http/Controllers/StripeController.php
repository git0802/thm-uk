<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionRenewal;
use App\Setting;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function check(Request $request)
    {
        $token   = Setting::where('name', 'stripe-webhook')->value('value');
        $payload = $request->getContent();
        $header  = $request->server('HTTP_STRIPE_SIGNATURE');
        $event   = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $header, $token
            );
        } catch(UnexpectedValueException $e) {
            return response(null, 400);
        } catch(SignatureVerificationException $e) {
            return response(null, 400);
        }

        switch ($event->type) {
            case 'charge.succeeded':
                $order = $event->data->object;
                $query = Subscription::where([
                        ['payment_method', $order->payment_method],
                        ['customer_id', $order->customer]
                    ]);

                if ( $query->exists() ) {
                    $subscription = $query->first();

                    if ( $subscription->ending->diff( now() )->days ) {
                        $user = $subscription->user;
                        Mail::to($user->email)->send(new SubscriptionRenewal($user));
                    }

                    $query->update([
                            'paid'   => 2,
                            'ending' => now()->addMonths($subscription->subscriptionPlan->months),
                        ]);
                }

                break;
            default:
                return response(null, 200);
        }

        return response(null, 200);
    }
}
