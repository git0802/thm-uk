<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use App\Helpers\MealHelper;
use App\Helpers\PlannerHelper;
use App\Helpers\PlannerStaticHelper;
use App\Http\Requests\ApplyPreset;
use App\Http\Requests\FinishPlannerSetup;
use App\Http\Requests\Planner\ExtraCalories;
use App\Http\Requests\Planner\FindOrCreate;
use App\Http\Requests\Planner\Goals;
use App\Http\Requests\PlannerInitialData;
use App\Http\Requests\ShoppingListActions;
use App\Http\Resources\ClonedPlannerResource;
use App\Http\Resources\Planner\PlannerResource;
use App\Http\Resources\ShoppingListResource;
use App\Planner;
use App\PlannerPreset;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Planner controller.
 *
 * @package App\Http\Controllers
 */
class PlannerController extends Controller
{
    use PlannerHelper, MealHelper;

    /**
     * Planner controller constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get user week planner or create if not exists.
     *
     * @param FindOrCreate $request
     * @return mixed
     */
    public function findOrCreate(FindOrCreate $request)
    {
        $planner = $this->findOrCreatePlanner($request->get('date'));

        if($request->get('mode') === 'setup') {
            $planner['code'] = 200;
        }

        if(isset($planner['planner'])) {
            return ClonedPlannerResource::make($planner['planner'])
                ->additional($planner['code'] === 307 ? ['route' => '/store'] : [])
                ->response()->setStatusCode($planner['code']);
        }

        return response(
            collect(['data' => $planner['planner']])->when($planner['code'] === 307,
                static function ($col) {
                    return $col->put('route', '/store');
                })
        )->setStatusCode($planner['code']);
    }

    public function finishInitialSetup(Planner $planner, FinishPlannerSetup $request)
    {
        $user = Auth::guard('sanctum')->user();

        if($user->finished_setup) {
            return response([
                'success' => false,
                'message' => __('planner.finish.already')
            ], 422);
        }

        $validator = PlannerStaticHelper::nestedValidation($planner);

        if($validator['is_valid']) {
            if ($request->has('skip_setup') && $request->get('skip_setup')) {
                if ($user->subscription && $user->checkPaid() && $user->subscription->payment_method) {
                    $user->finished_setup = true;
                    $user->save();
                }
            } else {
                $user->finished_setup = true;
                $user->save();
            }

            return response([
                'success' => true,
                'message' => __('planner.finish.completed')
            ], 200);
        } else {
            return response([
                'success' => false,
                'message' => __('planner.finish.not')
            ], 422);
        }


    }


    public function applyPreset(Planner $planner, PlannerPreset $preset, ApplyPreset $request)
    {
        if ( $this->userCanNotModifyPlanner($planner) ) {
            return response([
                'success' => false,
                'message' => __('meal.planner_prohibited')
            ], 403);
        }

        $date = Carbon::parse($request->get('date'))->startOfDay();

        if(!$date->between($planner->starts, $planner->ends)) {
            return response([
                'success' => false,
                'message' => __('meal.not_between')
            ], 422);
        };

        $items = $preset->preset;

        $dates = [];
        for($i = 0; $i <= 6; $i++) {
            $p = $planner->starts->addDays($i);
            $dates[strtolower($p->format('l'))] = $p->startOfDay();
        }



        // deleting old product (from different planner)
        $deleted = $planner->meals()->whereNotNull('from_preset_id')->delete();

        $message = [
            'dupes' => 0,
            'created' => 0,
            'deleted' => $deleted,
        ];

        // adding products from preset to the planner
        foreach($items as $day => $eatings) {
            foreach($eatings as $eating => $products) {
                foreach($products as $productId => $value) {
                    $product = Product::find($productId);
                    $json = $this->productDataArray($product);
                    $meal = $planner->meals()->firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'eating' => $eating,
                            'date' => $dates[$day]
                        ],
                        [
                            'servings' => $value['servings'],
                            'product_data' => $json->toArray(),
                            'from_preset_id' => $preset->id,
                        ]
                    );
                    if($meal->wasRecentlyCreated) {
                        $message['created']++;
                    } else {
                        $message['dupes']++;
                    }


                }
            }
        }

        return response([
            'success' => true,
            'message' => $message,
        ]);
    }


    /**
     * @param PlannerInitialData $request
     * @return Response
     * @throws Error
     */
    public function initialData(PlannerInitialData $request): Response
    {
        if (!Auth::user()->finished_setup) {
            return response([
                'success' => true,
                'data' => collect([
                    'goal' => Auth::user()->initial_goal,
                    'calories_goal' => Auth::user()->initial_calories_goal
                ])
            ]);
        }

        throw new Error('User already completed this stage');
    }

    /**
     * Extra calories.
     *
     * @param Planner $planner
     * @param ExtraCalories $request
     * @return Response
     * @throws Error
     */
    public function extraCalories(Planner $planner, ExtraCalories $request): Response
    {
        if ($request->user()->id !== $planner->user_id) {
            throw new Error(
                'You are not the owner of that week planner!',
                403
            );
        }


        $date = Carbon::parse($request->get('day'))->startOfDay();

        $planner->extras()->create([
            'value' => $request->value * ($request->type === 'ate' ? 1 : -1),
            'date' => $date,
        ]);

        return response([
            'success' => true,
            'message' => 'Extra calories updated successfully!'
        ]);
    }

    /**
     * Set week goals.
     *
     * @param Planner $planner
     * @param Goals $request
     * @return Response
     */
    public function goals(Planner $planner, Goals $request)
    {
        if ($planner->user_id !== $request->id()) {
            return response([
                'success' => false,
                'message' => 'You can`t modify this planner.'
            ], 403);
        }

        $planner->update($request->all());

        $planner->user()->update($request->only(['weight', 'goal']));

        PlannerStaticHelper::nestedValidation($planner);

        return response([
            'success' => true,
            'message' => 'Week planner updated successfully!'
        ]);
    }

    /**
     * Returns shopping list collection
     *
     * @param Planner $planner
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */

    public function shoppingList(Planner $planner): AnonymousResourceCollection
    {
        return ShoppingListResource::collection($planner->calcShoppingList());
    }


    /**
     * Shopping list crud actions
     * 0 goes for decrease, 1 goes for increase
     *
     * @param Planner $planner
     * @param ShoppingListActions $request
     */

    public function shoppingListQuantity(Planner $planner, ShoppingListActions $request)
    {
        $shoppingList = $planner->shoppingList();

        /**
         * checking if id of product exists in array
         */

        if(!$shoppingList->has($request->get('id'))) {
            return response([
                'success' => false,
                'message' => __('planner.shopping.action.404')
            ], 404);
        };

        $item = $shoppingList->get($request->get('id'));

        /**
         * creating copied shopping list. just in case
         */

        $copiedList = $shoppingList->toArray();

        switch ($request->get('action')) {
            case '0':
                if($copiedList[$request->get('id')]['quantity']<= 1) {
                    return response([
                        'success' => false,
                        'message' => __('planner.shopping.action.decrease.prohibited')
                    ], 422);
                }

                $copiedList[$request->get('id')]['quantity']--;

                $planner->shopping_list_data = $copiedList;
                $planner->save();

                return response([
                    'success' => true,
                    'message' => __('planner.shopping.action.decrease.success')
                ], 200);

                break;
            case '1':
                $copiedList[$request->get('id')]['quantity']++;

                $planner->shopping_list_data = $copiedList;
                $planner->save();

                return response([
                        'success' => true,
                        'message' => __('planner.shopping.action.increase.success')
                    ], 200);
                break;
        }


    }

}
