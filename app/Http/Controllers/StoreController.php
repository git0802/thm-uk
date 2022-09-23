<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use App\Exceptions\Errors;
use App\Helpers\StoreHelper;
use App\Http\Requests\SoftDeleteCustomStore;
use App\Http\Requests\Store\CreateStore;
use App\Http\Requests\Store\FillStore;
use App\Http\Requests\Store\UpdateStore;
use App\Http\Resources\Store\StoreImagesResource;
use App\Http\Resources\Store\StoreResource;
use App\Jobs\ProcessImages;
use App\Jobs\ProcessStore;
use App\Product;
use App\Store;
use App\Helpers\UserHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use League\Csv\AbstractCsv;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use function League\Csv\delimiter_detect;

/**
 * Store Controller.
 *
 * @package App\Http\Controllers
 */
class StoreController extends Controller
{
    use UserHelper, ImageHelper, StoreHelper;

    /**
     * StoreController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except([
            'storeImages', 'all'
        ]);
    }

    /**
     * Get all stores for current user.
     *
     * @return AnonymousResourceCollection
     */
    public function all(): AnonymousResourceCollection
    {
        return StoreResource::collection(
            Store::byOwnerId($this->id(), $this->getUser()->stores->first()->id)->orderBy('owner_id', 'DESC')->orderByRaw('LOWER(name)')->get()
        );
    }

    /**
     * Get default stores
     *
     * @return AnonymousResourceCollection
     */

    public function defaults(): AnonymousResourceCollection
    {
        return StoreResource::collection(
            Store::default()->get()
        );
    }

    /**
     * Remove store, store image and related products.
     *
     * @param integer $id
     * @return Response
     */
    public function remove($id): Response
    {
        $store = Store::findOrFail($id);

        if (!$this->getUser()->is_admin && $store->owner_id !== $this->id()) {
            return response([
                'success' => false,
                'message' => __('store.deleting_prohibited')
            ], 403);
        }
        $store->forceDelete();
        return response([
            'success' => true,
            'message' => __('store.deleted')
        ]);
    }

    /**
     * Create store.
     *
     * @param CreateStore $request
     * @return Response
     */
    public function create(CreateStore $request): Response
    {
        $store = Store::make($request->all(['name']));
        $store->owner_id = $this->isAdmin() && $request->get('is_default') !== null ? null : $this->id();
        $store->save();
        if ($request->file('image') !== null) {
            $this->saveImageFromRequestFile($store, $request->file('image'), 'store');
        }

        return response([
            'success' => true,
            'message' => __('store.created', ['store' => $store->name]),
            'store' => StoreResource::make($store),
        ]);
    }

    /**
     * Update store
     *
     * @param Store $store
     * @param UpdateStore $request
     * @return Response
     */
    public function update(Store $store, UpdateStore $request): Response
    {
        $store->name = $request->get('name');
        $store->save();
        if ($request->hasFile('image')) {
            $this->updateImageFromRequestFile($store, $request->file('image'), 'store');
        }

        return response([
            'success' => true,
            'message' => __('store.updated', ['store' => $store->name]),
            'store' => StoreResource::make($store),
        ]);
    }


    /**
     * Soft delete custom store
     *
     * @param Store $store
     * @param SoftDeleteCustomStore $request
     * @return Response
     */

    public function softDeleteStoreCustomStore(Store $store, SoftDeleteCustomStore $request): Response
    {
        if ($store->owner_id !== $this->id()) {
            return response([
                'success' => false,
                'message' => __('store.deleting_prohibited')
            ], 403);
        }
        $store->forceDelete();

        return response([
            'success' => true,
            'message' => __('store.soft_deleted'),
        ]);
    }


    /**
     * Get logo of the stores.
     *
     * @return AnonymousResourceCollection
     */
    public function storeImages(): AnonymousResourceCollection
    {
        return StoreImagesResource::collection(Store::default()->get());
    }

    /**
     * Fill store from csv.
     *
     * @param Store $store
     * @param FillStore $request
     * @return Response
     * @throws Exception
     * @throws Errors
     */
    public function fillStore(Store $store, FillStore $request): Response
    {
//        if ($store->in_progress) {
//            throw new Error("{$store->name} already in progress...");
//        }
        $this->inProgress($store, true);
        $csv = $this->getCSV($request);
        $columns = $this->findColumns($csv, $request);
        if (isset($columns['success']) && !$columns['success']) {
            $this->inProgress($store, false);
            throw new Errors($columns);
        }

        $proxy = [];

        foreach ($csv as $row) {
            array_push($proxy, $row);
        }

        ProcessStore::dispatch($proxy, $columns, $store);


        $this->inProgress($store, false);

        return response([
            'success' => true,
            'message' => 'Image downloading can take a long time...'
        ]);
    }
}
