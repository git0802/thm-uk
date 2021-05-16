<?php

namespace App\Http\Controllers;

use App\CouponCode;
use App\CouponSet;
use App\Exceptions\Error;
use App\Http\Requests\Coupon\AddCodes;
use App\Http\Requests\Coupon\AddNewSet;
use App\Http\Requests\Coupon\CheckCode;
use App\Helpers\UserHelper;
use App\Helpers\LocationHelper;
use App\Http\Requests\Coupon\UpdateSet;
use App\Http\Resources\Coupon\CheckedCouponSetResource;
use App\Http\Resources\Coupon\SetResource;
use App\Repositories\SubscriptionRepository;
use Exception;
use League\Csv\Reader;
use Illuminate\Http\Response;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

/**
 * Coupons Controller
 *
 * @package App\Http\Controllers
 */
class CouponsController extends Controller
{
    use UserHelper;

    /**
     * CouponsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->except([
                'check',
            ]);
    }

    /**
     * Get coupon sets.
     *
     * @return Response
     */
    public function sets(): Response
    {
        return response([
            'success' => $this->isAdmin(),
            'sets' => $this->isAdmin() ? CouponSet::where('deletion_prohibited', false)->get() : null,
        ]);
    }

    /**
     * Get all codes by coupon set.
     *
     * @param CouponSet $set
     * @return Response
     */
    public function codes(CouponSet $set): Response
    {
        return response([
            'success' => $this->isAdmin(),
            'sets' => $this->isAdmin()
                ? $set->codes()->orderBy('used_at', 'desc')->get()
                : null,
        ]);
    }

    /**
     * Check coupon code.
     *
     * @param CheckCode $request
     * @return Response
     * @throws Error
     */
    public function check(CheckCode $request): Response
    {
        $code = CouponCode::where('code', $request->get('code'))
            ->first();
        if (!$code) {
            throw new Error('This code doesn`t exist.');
        }
        if ($code->user) {
            throw new Error('Sorry, this code is already used.');
        }

        return response([
            'success' => true,
            'message' => $code->set->title,
            'set' => CheckedCouponSetResource::make($code->set),
        ]);
    }

    /**
     * Add codes to coupon set.
     *
     * @param CouponSet $set
     * @param AddCodes $request
     * @return Response
     */
    public function addCodes(CouponSet $set, AddCodes $request)
    {
        $reader = Reader::createFromPath($request->file('csv')->getPathname(), 'r');
        $codes = iterator_to_array($reader, true);
        $rawCodes = array_values((array) CouponCode::where('coupon_set_id', $set->id)->pluck('code'));
        $cleanCodes = array_map(function ($item) {
            return trim($item);
        }, $rawCodes[0]);
        foreach ($codes as $code) {
            if (isset($code[0]) && mb_strlen($code[0]) && !in_array($code[0], $cleanCodes)) {
                $oldCodes[] = $code[0];
                $set->codes()->create([
                    'code' => $code[0]
                ]);
            }
        }

        return response([
            'success' => true
        ]);
    }

    /**
     * Add new coupon set.
     *
     * @param AddNewSet $request
     * @param SubscriptionRepository $subscription
     * @return Response
     * @throws Error
     */
    public function create(AddNewSet $request, SubscriptionRepository $subscription)
    {
        $set = CouponSet::create($request->all());

        $stripe = new StripeClient(env('STRIPE_API_KEY'));
        try {
            $stripeProduct = $stripe->products->create([
                'name' => $request->get('title'),
            ]);
            $set->stripe_product_id = $stripeProduct->id;

            $currency = LocationHelper::getCurrency();

            $price = $set->type === 'month' ? $subscription->getPrice(100) : $subscription->getData('price') * 12 * 100;
            $priceArray = [
                'currency'  => strtolower($currency['currency']),
                'recurring' => ['interval' => 'year'],
                'product'   => $set->stripe_product_id,
            ];

            if ($set->type === 'fixed') {
                $price -= $set->value * 100;
            } else if ($set->type === 'percent') {
                $price -= $price / 100 * $set->value;
            }

            $priceArray['unit_amount'] = $price;
            $stripePrice = $stripe->prices->create($priceArray);

            $set->stripe_price_id = $stripePrice->id;
            $set->cost = $price / 100;
            $set->save();
        } catch (Exception $exception) {
            $set->delete();
            throw new Error($exception->getMessage(), 422);
        }

        return response([
            'success' => true,
            'message' => 'New coupon set created successfully!',
            'set' => SetResource::make($set),
        ]);
    }


    public function update(CouponSet $set, UpdateSet $request)
    {
        if ($set->deletion_prohibited) {
            return response([
                'success' => false,
                'message' => 'No. You cant. Also how?'
            ], 403);
        }

        $set->name = $request->get('name');
        $set->title = $request->get('title');
        $set->save();

        return response([
            'success' => true,
            'message' => $set->title .' updated successfully!'
        ]);
    }


    /**
     * Remove set with codes.
     *
     * @param CouponSet $set
     * @return Response
     * @throws Exception
     */
    public function remove(CouponSet $set)
    {
        if (!$this->isAdmin()) {
            return response([
                'success' => false,
                'message' => 'Only admin can do this!'
            ], 403);
        }

        if ($set->deletion_prohibited) {
            return response([
                'success' => false,
                'message' => 'Deletion for this coupon is prohibited'
            ], 403);
        }

        $set->codes()->delete();
        $set->delete();

        return response([
            'success' => true,
            'message' => 'Codes deleted successfully!'
        ]);
    }
}
