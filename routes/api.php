<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/categories')->name('categories.')->controller('CategoryController')->group(function () {

    Route::get('/', 'getCategories')->name('index');

    Route::prefix('/{category:slug}')->group(function () {
        Route::prefix('/items')->name('items.')->group(function () {
            Route::get('/', 'getCategoryItems')->name('index');
            Route::get('/all', 'getAllCategoryItems')->name('all');
        });
    });

});

Route::prefix('/items')->name('items.')->controller('ItemController')->group(function () {

    Route::prefix('/{item:slug}')->group(function () {
        Route::get('/', 'getItem')->name('show');
    });

});

Route::prefix('/feedback')->name('feedback.')->controller('FeedbackController')->group(function () {

    Route::post('/', 'createFeedback')->middleware('throttle:feedback')->name('create');

});
