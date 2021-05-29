<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use App\Exceptions\Errors;
use App\Helpers\PresetStaticHelper;
use App\Http\Requests\ChangePresetProductServings;
use App\Http\Requests\GetAllPresets;
use App\Http\Requests\GetPresetForStore;
use App\Http\Requests\MakePresetDish;
use App\Http\Requests\Preset\DeleteProductFromPreset;
use App\Http\Requests\Product\FindProduct;
use App\Http\Requests\PublishPlannerPreset;
use App\Http\Requests\StorePresetCSV;
use App\Http\Resources\Dish\DishResource;
use App\Http\Resources\PlannerPresetListResource;
use App\Http\Resources\PresetJsonResource;
use App\Http\Resources\Product\FindProductResource;
use App\Meal;
use App\Planner;
use App\Product;
use App\Store;
use App\PlannerPreset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Preset\AddProductToPreset;
use App\Http\Requests\Preset\EditPlannerPreset;
use App\Http\Requests\Preset\CreatePlannerPreset;
use App\Http\Requests\Preset\DeletePlannerPreset;
use App\Http\Resources\PlannerPresetResource;
use Illuminate\Support\Facades\Auth;
use League\Csv\ResultSet;
use League\Csv\Statement;

class PlannerPresetController extends Controller
{
    /**
     * Show ALL presets
     * @param GetAllPresets $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(GetAllPresets $request)
    {
        return PlannerPresetListResource::collection(
            PlannerPreset::where('preset_case', PlannerPreset::CASE_WEEK)->whereNotNull('store_id')->orderBy('id')->get()
        );
    }

    /**
     * Show specific preset
     *
     * @param PlannerPreset $preset
     * @param Request $request
     * @return Response
     */
    public function show(PlannerPreset $preset, Request $request): Response
    {
        return response([
            'success' => true,
            'data' => PlannerPresetResource::make($preset)
        ]);
    }

    public function getPresetForStore(GetPresetForStore $request)
    {
        $preset = PlannerPreset::where('preset_case', PlannerPreset::CASE_WEEK)->where('is_published', true)->where('store_id', $request->get('store_id'))
            ->first();

        if ($preset) {

            $user = Auth::guard('sanctum')->user();

            if(!$user->finished_setup) {

                $latestPlanner = $user->planners()->latest()->orderBy('starts')->with('meals')->first();

                if ($latestPlanner) {
                    Planner::find($latestPlanner->id)->meals()->delete();
                }
            }

            return PlannerPresetResource::make($preset);
        } else {
            return response([
                'success' => false,
                'message' => __('preset.404')
            ]);
        }

    }

    /**
     * Create preset
     *
     * @param CreatePlannerPreset $request
     * @return Response
     */
    public function create(CreatePlannerPreset $request): Response
    {
        $preset = PlannerPreset::create($request->all());

        return response([
            'success' => true,
            'message' => __('preset.created'),
            'preset' => PlannerPresetResource::make($preset)
        ]);

    }

    /**
     * Update preset data
     *
     * @param PlannerPreset $preset
     * @param EditPlannerPreset $request
     * @return Response
     */
    public function update(PlannerPreset $preset, EditPlannerPreset $request): Response
    {
        $preset->update($request->all());

        return response([
            'success' => true,
            'message' => __('preset.updated'),
            'preset' => PlannerPresetResource::make($preset)
        ]);
    }

    /**
     * Delete preset
     *
     * @param PlannerPreset $preset
     * @param DeletePlannerPreset $request
     * @return Response
     * @throws \Exception
     */
    public function delete(PlannerPreset $preset, DeletePlannerPreset $request): Response
    {
        $preset->delete();

        return response([
            'success' => true,
            'message' => __('preset.deleted'),
        ]);
    }

    /**
     * Adding product to preset
     *
     * @param PlannerPreset $preset
     * @param AddProductToPreset $request
     * @return Response
     */
    public function addProductToPreset(PlannerPreset $preset, AddProductToPreset $request): Response
    {
        $product = Product::find($request->get('product_id'));

        $items = $preset->buildArray();

        if (isset($items[$request->get('day')][$request->get('eating')][$product->id])) {
            return response([
                'success' => false,
                'message' => __('preset.product.dupe'),
            ], 422);
        }

        $items[$request->get('day')][$request->get('eating')][$product->id] = [
            'servings' => $items[$request->get('day')][$request->get('eating')][$product->id]['servings'] ?? 1,
        ];

        $preset->savePreset($items);

        return response([
            'success' => true,
            'message' => __('preset.product.added'),
            'preset' => PresetJsonResource::make($preset->preset)
        ]);
    }

    /**
     * Update servings size for stored product
     *
     * @param PlannerPreset $preset
     * @param ChangePresetProductServings $request
     * @return Response
     */
    public function changeProductServings(PlannerPreset $preset, ChangePresetProductServings $request): Response
    {
        $items = $preset->preset;

        if (!isset($items[$request->get('day')][$request->get('eating')][$request->get('product_id')])) {
            return response([
                'success' => false,
                'message' => __('preset.product.404'),
            ], 404);
        } else {
            $items[$request->get('day')][$request->get('eating')][$request->get('product_id')]['servings'] = (int)$request->get('servings');
            $preset->savePreset($items);
            return response([
                'success' => true,
                'message' => __('preset.product.servings.updated'),
                'preset' => PresetJsonResource::make($preset->preset)

            ]);
        }
    }

    /**
     * Deleting product from preset by eating and product id
     *
     * @param PlannerPreset $preset
     * @param DeleteProductFromPreset $request
     * @return Response
     */
    public function deleteProductFromPreset(PlannerPreset $preset, DeleteProductFromPreset $request): Response
    {
        $items = $preset->preset;

        if (isset($items[$request->get('day')][$request->get('eating')][$request->get('product_id')])) {
            unset($items[$request->get('day')][$request->get('eating')][$request->get('product_id')]);
            $preset->savePreset($items);
            return response([
                'success' => true,
                'message' => __('preset.product.deleted'),
                'preset' => PresetJsonResource::make($preset->preset)
            ]);
        } else {
            return response([
                'success' => false,
                'message' => __('preset.product.404'),
            ], 404);
        }
    }

    public function publishPreset(PlannerPreset $preset, PublishPlannerPreset $request)
    {
        $s = $preset->publishPreset();
        return response([
            'success' => true,
            'message' => __('preset.published'),
        ], 201);
    }

    public function findProduct(FindProduct $request)
    {
        $store = Store::find($request->store_id);
        if ($store->owner_id !== null && $store->owner_id !== Auth::id()) {
            return \response([
                'success' => false,
                'error' => 'You can`t use products from this store.'
            ], 403);
        }

        $searchString = trim(strtolower($request->search));

        $products = Product::whereIn('store_id', [$request->store_id])
            ->when(filter_var($request->get('without_dish'), FILTER_VALIDATE_BOOLEAN), function ($query) {
                $query->where('is_dish', false);
            } )
            ->whereRaw("LOWER(name) LIKE ?", ['%' . $searchString . '%'])
            ->whereNull('owner_id')
            ->take(100)
            ->get();

        return response([
            'success' => true,
            'products' => FindProductResource::collection($products),
        ]);
    }


    public function storePresetCSV(PlannerPreset $preset, StorePresetCSV $request)
    {
        $reader = PresetStaticHelper::getCSV($request);
        $reader->setHeaderOffset(0);
        $records = Statement::create()->process($reader);
        $items = $preset->buildArray();
        $addingErrors = [];
        $messageVariables = [
            'created' => 0,
            'dupe' => 0,
        ];

        $columns = PresetStaticHelper::findColumns($records, $request);

        if (isset($columns['success']) && !$columns['success']) {
            throw new Errors($columns);
        }


        foreach ($records as $val) {

            $productName = trim($val[$columns['product_name']]);
            $day = trim(strtolower($val[$columns['day']]));
            $eating = trim(strtolower($val[$columns['eating']]));

            $query = Product::where('name', $productName)->where('store_id', $preset->store_id);
            $product = $query->first();

            if (!$product) {
                $addingErrors['not_found'][$productName] = 'Product not found. Please, try to add this product manually';
                continue;
            }
            if (!in_array($day, PlannerPreset::DAYS)) {
                $addingErrors[''][$day][$productName] = 'Has invalid day. It must be one of these: ' . implode(',', PlannerPreset::DAYS);
                continue;
            }

            if (!in_array($eating, PlannerPreset::MEALS)) {
                $addingErrors[$val[$columns['eating']]][$productName] = 'Has invalid eating. It must be one of these: ' . implode(',', PlannerPreset::MEALS);
                continue;
            }

            if (isset($items[$day][$eating][$product->id])) {
                $messageVariables['dupe']++;
                continue;
            }

            $items[$day][$eating][$product->id] = [
                'servings' => $items[$day][$eating][$product->id]['servings'] ?? 1,
            ];
            $messageVariables['created']++;
        }

        $preset->savePreset($items);

        return response([
            'success' => true,
            'message' => __('preset.csv.stored', $messageVariables),
            'errors' => $addingErrors,
        ]);
    }

    public function groupProducts(MakePresetDish $request)
    {
        $productArray = collect();
        $eating = null;
        $date = null;
        $plannerId = null;
        foreach ($request->get('product_ids') as $id) {
            $product = Product::findOrFail($id);
            $productArray->push($product);

            if($product->store_id !== (int) $request->get('store_id')) {
                throw new Error(__('dish.wrong_store'), 422);
            }

        }

        // Store blank dish in DB
        $dish = new Product();
        $dish->name = $request->get('name');
        $dish->store_id = (int) $request->get('store_id');
        $dish->owner_id = null;
        $dish->is_dish = true;
        $dish->save();

        // Add products to a dish
        $dish->dish()->attach($productArray->pluck('id'));

        if ($request->file('image') !== null) {
            $this->saveImageFromRequest($dish, $request, 'dishes');
        }

        // Calc summary params
        $dish_params = [
            'calories' => 0,
            'serving_size' => 0,
            'cost' => 0
        ];
        foreach ($dish->dish as $product) {
            $dish_params['calories'] += $product->calories;
            $dish_params['serving_size'] = 1;
            $dish_params['cost'] += $product->cost;
        }
        $dish->update($dish_params);

        return response([
            'success' => true,
            'message' => __('dish.created', ['dish' => $dish->name]),
            'dish' => DishResource::make($dish),
        ]);
    }
}
