<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProduct;
use App\Http\Requests\Product\FindProduct;
use App\Http\Resources\Product\FindProductResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\ProductsCollection;
use App\Product;
use App\Helpers\UserHelper;
use App\Helpers\ImageHelper;
use App\Store;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    use UserHelper;
    use ImageHelper;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get all products for current user.
     *
     * @return ProductsCollection
     */
    public function all(): ProductsCollection
    {
        return ProductsCollection::make(Product::byOwnerId($this->id)->get());
    }

    /**
     * Find products with search string.
     *
     * @param FindProduct $request
     * @return Response
     */
    public function find(FindProduct $request): Response
    {
        $store = Store::find($request->store_id);
        if ($store->owner_id !== null && $store->owner_id !== Auth::id()) {
            return \response([
                'success' => false,
                'error' => 'You can`t use products from this store.'
            ], 403);
        }


        $searchString = trim(strtolower($request->search));


        $products = Product::whereIn('store_id', [$request->store_id, $this->getUser()->stores->first()->id])
            ->whereNull('owner_id')
            ->whereRaw("LOWER(name) LIKE ?", ['%'. $searchString. '%']);

        $customerProducts = Product::whereIn('store_id', [$request->store_id, $this->getUser()->stores->first()->id])
            ->where('owner_id', $this->id())
            ->whereRaw("LOWER(name) LIKE ?", ['%'. $searchString. '%'])
            ->union($products)
            ->orderBy('owner_id')
            ->take(10)
            ->get();




        return response([
            'success' => true,
            'products' => FindProductResource::collection($customerProducts),
        ]);
    }

    /**
     * Add products to the store.
     *
     * @param Store $store
     * @param CreateProduct $request
     * @return Response
     */
    public function store(Store $store, CreateProduct $request): Response
    {
        if ($request->has('clone_id') && $request->has('edit_id')) {
            return response([
                'success' => false,
                'message' => 'You cant have clone_id and edit_id populated at the same time',
            ], 422);
        }

        if ($request->has('clone_id')) {
            $parent = Product::find($request->get('clone_id'));

            $product = $store->products()->create([
                'name' => $parent->name,
                'serving_size' => $request->get('serving_size'),
                'calories' => $request->get('calories'),
                'cost' => $request->get('cost'),
                'owner_id' => $this->id()
            ])->refresh();

            $image = $parent->image()->first();

            if($image) {
                $filename = Str::random(40) . "." . pathinfo($image->path)['extension'];
                Storage::copy($image->path, "images/products/" . $filename);
                $product->image()->create([
                    'path' => "images/products/" . $filename
                ]);
            }

            $planner = $this->getUser()->planners()->latest()->orderBy('starts')->with('meals')->first();
            if($planner) {
                Product::replaceProductAtPlanner($parent->id, $product, $planner, false);
            }

        } else if($request->has('edit_id')) {
            $product = $store->products()->where('id', $request->get('edit_id'))->first();

            $product->update([
                    'name' => $request->name,
                    'serving_size' => $request->get('serving_size'),
                    'calories' => $request->get('calories'),
                    'cost' => $request->get('cost'),
                    'owner_id' => $this->id()
                ]);

            if ($request->file('image') !== null) {
                if($product->image()->first()) {
                    $this->updateImageFromRequest($product, $request);
                } else {
                    $this->saveImageFromRequest($product, $request);
                }
            }

            $planner = $this->getUser()->planners()->latest()->orderBy('starts')->with('meals')->first();
            if($planner) {
                Product::replaceProductAtPlanner($product->id, $product, $planner, true);
            }

            return response([
                'success' => true,
                'message' => $product->name . " updated successfully!",
                'product' => ProductResource::make($product),
            ]);
        } else {
            $product = $store->products()->create($request->all());
            $product->owner_id = $this->id();
            $product->save();

            if ($request->file('image') !== null) {
                $this->saveImageFromRequest($product, $request);
            }
        }

        return response([
            'success' => true,
            'message' => $product->name . " created successfully!",
            'product' => ProductResource::make($product),
        ]);
    }
}
