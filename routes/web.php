<?php

use Illuminate\Support\Facades\Route;


/*
 * Vladimir ZONE!
 * All routes in vue-router (from planner.js)
 */
Route::prefix('meal-planner')->group(static function () {
    Route::view('{any?}', 'planner');
});

Route::prefix('admin')->group(static function () {
    Route::view('{any?}', 'admin');
});

/*
 * BulavaDM ZONE!
 * URLS:
 * / - home
 * /blog - blog
 * /login (vue router from app.js)
 * /register (vue router from app.js)
 * /logout (vue router from app.js)
 * /{blog-post-slug} - blog post
 * /{page-slug} - static page
 */
Route::view('{any?}', 'index')
    ->where('any', '^(?!storage/images/stores/).*$');
Route::get('/login')->name('login');
