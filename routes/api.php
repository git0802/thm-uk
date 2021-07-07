<?php

use App\Http\Resources\User\UserResource;
use App\Meal;
use App\Planner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('check', static function () {
    return response([
        'authenticated' => Auth::guard('sanctum')->check(),
        'user'          => Auth::guard('sanctum')->user()
            ? UserResource::make(Auth::guard('sanctum')->user())
            : null,
        'subscription' => Auth::guard('sanctum')->user()->subscription ? Auth::guard('sanctum')->user()->subscription->subscriptionPlan : null,
    ]);
});

Route::namespace('Auth')->group(static function () {
    Route::get('activate/{activation_token}', 'ActivateController');
    Route::post('login', 'LoginController');
    Route::post('logout', 'LogoutController');
    Route::post('register', 'RegisterController');
    Route::post('check-cart', 'RegisterController@checkCart');

    Route::prefix('password')->group(static function () {
        Route::post('reset', 'PasswordController@reset');
        Route::post('send', 'PasswordController@send');
    });
});

Route::prefix('user')->group(static function () {
    Route::get('all', 'CustomerController@all');
    Route::post('/resend/{user}', 'CustomerController@resendActivation');
    Route::post('avatar', 'ImageController@changeAvatar');
    Route::prefix('settings')->group(static function() {
        Route::post('change-password', 'Auth\PasswordController@change');
        Route::post('change-email', 'CustomerSettingsController@changeEmail');
        Route::post('change-name', 'CustomerSettingsController@changeName');
        Route::post('change-phone', 'CustomerSettingsController@changePhone');
        Route::post('change-metrics', 'CustomerSettingsController@changeMetrics');
        Route::post('change-spam', 'CustomerSettingsController@changeSpam');
    });
});

Route::prefix('product')->group(static function () {
    Route::get('/', 'ProductController@all');
    Route::get('find', 'ProductController@find');
    Route::post('{store}', 'ProductController@store')->middleware('subscription');
});

Route::prefix('image')->group(static function () {
    Route::post('/upload', 'ImageController@upload')->middleware('subscription');
    // todo add admin middleware
    Route::get('/list', 'ImageController@imageList');

});

Route::prefix('coupon')->group(static function () {
    Route::get('/', 'CouponsController@sets');
    Route::get('{set}', 'CouponsController@codes');
    Route::patch('{set}', 'CouponsController@update');
    Route::post('check', 'CouponsController@check');
    Route::post('{set}/add', 'CouponsController@addCodes');
    Route::post('/', 'CouponsController@create');
    Route::delete('{set}', 'CouponsController@remove');
});

Route::prefix('dish')->group(static function () {
    Route::post('/', 'DishController@create')->middleware('subscription');
    Route::post('/meals', 'DishController@createDishByMeals')->middleware('subscription');
    Route::delete('{id}', 'DishController@remove')->middleware('subscription');
});

Route::prefix('subscription')->group(static function () {
    Route::get('status', 'SubscriptionController@status');
    Route::get('price', 'SubscriptionController@defaultPrice');
    Route::post('cancel', 'SubscriptionController@cancel');
    Route::post('renew', 'SubscriptionController@renew');
    Route::post('create', 'SubscriptionController@create');
    Route::post('renew-check', 'SubscriptionController@renewCheck');
});

Route::prefix('stripe')->group(static function () {
    Route::post('check', 'StripeController@check')->name('stripe-check');
});

Route::prefix('planner')->group(static function () {
    Route::get('/', 'PlannerController@findOrCreate');
    Route::get('/initial', 'PlannerController@initialData')->middleware('subscription');
    Route::patch('{planner}/correct', 'PlannerController@extraCalories')->middleware('subscription');
    Route::get('{planner}/extraCalories', 'ExtraCaloriesController@list')->middleware('subscription');
    Route::delete('{planner}/extraCalories/{id}', 'ExtraCaloriesController@delete')->middleware('subscription');
    Route::post('{planner}/finish', 'PlannerController@finishInitialSetup')->middleware('subscription');
    Route::patch('{planner}/goals', 'PlannerController@goals')->middleware('subscription');
    Route::get('{planner}/shopping', 'PlannerController@shoppingList')->middleware('subscription');
    Route::post('{planner}/preset/{preset}', 'PlannerController@applyPreset')->middleware('subscription');
    Route::post('{planner}/shopping/actions', 'PlannerController@shoppingListQuantity')->middleware('subscription');
});

Route::prefix('meal')->group(static function () {
    Route::patch('eaten/{meal}', 'MealController@eaten')->middleware('subscription');
    Route::post('clone/{planner}', 'MealController@cloneMeals')->middleware('subscription');
    Route::put('{planner}/{product}', 'MealController@add')->middleware('subscription');
    Route::patch('{planner}/{meal}', 'MealController@update')->middleware('subscription');
    Route::delete('{planner}/{meal}', 'MealController@remove')->middleware('subscription');
});

Route::prefix('store')->group(static function () {
    Route::get('/', 'StoreController@all');
    Route::get('/defaults', 'StoreController@defaults');
    Route::get('images', 'StoreController@storeImages');
    Route::post('/', 'StoreController@create')->middleware('subscription');
    Route::patch('/{store}', 'StoreController@update')->middleware('subscription');
    Route::put('/{store}', 'StoreController@fillStore')->middleware('subscription');
    Route::delete('{id}', 'StoreController@remove')->middleware('subscription');
    Route::delete('/soften/{store}', 'StoreController@softDeleteStoreCustomStore')->middleware('subscription');
});

Route::prefix('stats')->group(static function () {
    Route::get('week', 'StatsController@week');
    Route::get('total', 'StatsController@total')->middleware('subscription');
});

Route::prefix('video')->group(static function () {
    Route::get('/', 'VideosController@all')->middleware('subscription');
    Route::post('/', 'VideosController@create')->middleware('check.admin');
    Route::patch('{video}', 'VideosController@update')->middleware('check.admin');
    Route::delete('{video}', 'VideosController@remove')->middleware('check.admin');
});

Route::prefix('content')->group(static function () {
    Route::get('/', 'SiteContentController@index');
    Route::post('/update/{id}', 'SiteContentController@update');
    Route::get('/links', 'SiteContentController@socialLinks');
    Route::post('/links', 'SiteContentController@addSocialLink')->middleware('check.admin');
    Route::patch('/links', 'SiteContentController@updateSocialLink')->middleware('check.admin');
    Route::delete('/links', 'SiteContentController@removeSocialLink')->middleware('check.admin');
});

Route::prefix('post')->namespace('Content')->group(static function () {
    Route::get("all/{taxonomy?}", 'PostController@all');
    Route::get("all_blogs", 'PostController@allPosts');
    Route::get("featured", 'PostController@featured');
    Route::get('taxonomies', function () {
        return response([
            'taxonomies' => ["Healthy lifestyle", "Training", "Healthy meal"],
        ], 200);
    });
    Route::get('blacklist', function () {
        return response([
            'blacklisted' => config('app.blacklist_urls'),
        ], 200);
    });
    Route::get("{slug}", 'PostController@show');
    Route::post('/', 'PostController@create');
    Route::patch('{content}', 'PostController@update');
    Route::delete('{content}', 'PostController@delete');
});

Route::prefix('page')->namespace('Content')->group(static function () {
    Route::get("/", 'PageController@list');
    Route::get("/all", 'PageController@all');
    Route::get("{slug}", 'PageController@show');
    Route::post('/', 'PageController@create');
    Route::patch('{content}', 'PageController@update');
    Route::delete('{content}', 'PageController@delete');
});

Route::prefix('quote')->group(static function () {
    Route::get('/', 'QuoteController@show');
    Route::get('all', 'QuoteController@index');
    Route::post('/', 'QuoteController@create');
    Route::patch('{quote}', 'QuoteController@update');
    Route::delete('{quote}', 'QuoteController@remove');
});

Route::prefix('preset')->group(function() {
    Route::get('/', 'PlannerPresetController@index')->middleware('check.admin');
    Route::get('/find', 'PlannerPresetController@findProduct')->middleware('check.admin');
    Route::get('/find/store', 'PlannerPresetController@getPresetForStore');
    Route::post('/dish', 'PlannerPresetController@groupProducts')->middleware('check.admin');
    Route::post('/publish/{preset}', 'PlannerPresetController@publishPreset')->middleware('check.admin');
    Route::post('/', 'PlannerPresetController@create')->middleware('check.admin');
    Route::get('/{preset}', 'PlannerPresetController@show')->middleware('check.admin');
    Route::post('/{preset}', 'PlannerPresetController@addProductToPreset')->middleware('check.admin');
    Route::put('/{preset}', 'PlannerPresetController@deleteProductFromPreset')->middleware('check.admin');
    Route::patch('/{preset}', 'PlannerPresetController@update')->middleware('check.admin');
    Route::patch('/{preset}/servings', 'PlannerPresetController@changeProductServings')->middleware('check.admin');
    Route::delete('/{preset}', 'PlannerPresetController@delete')->middleware('check.admin');
    Route::post('/{preset}/csv', 'PlannerPresetController@storePresetCSV')->middleware('check.admin');

});


Route::post('support', 'SupportController');

Route::prefix('get')->group(static function () {
    Route::get('price', 'GetController@price');
});
