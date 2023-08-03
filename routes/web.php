<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthWPController;
use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\ProductController;
use App\Http\Controllers\Cms\PostController;
use App\Http\Controllers\Cms\SliderController;
use App\Http\Controllers\Cms\NetworkController;
use App\Http\Controllers\Cms\StoreCategoryController;
use App\Http\Controllers\Cms\StorePostsController;
use App\Http\Controllers\Cms\PagesController;
use App\Http\Controllers\Cms\SocialResponses;
use App\Http\Controllers\Cms\SettingController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Api\RequestApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});
Route::get('logout', [LogoutController::class, 'logout']);

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::get('/api-auth', [AuthWPController::class, 'authenticate'])->name('wp.auth');



Route::group([
    'name' => 'dashboard',
    'prefix' => 'dashboard',
    'middleware' => 'auth'
], function () {
    Route::get('/sample/data', [ProductController::class, 'getSampleData'])->name('sample.data');

    // * DASHBOARD
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // * POSTS DATA
    Route::get('/article-news', [PostController::class, 'index'])->name('posts.index');
    Route::get('/article-news/data', [PostController::class, 'getPostData'])->name('posts.data');
    Route::get('/news-category', [PostController::class, 'newsCategory'])->name('news.category.index');
    Route::get('/news-category/data', [PostController::class, 'getCategories'])->name('news.category.data');
    // * END POSTS DATA

    // * INSURANCE
    Route::get('/insurance', [ProductController::class, 'index'])->name('insurance.index');
    Route::get('/insurance/contents/{id}', [ProductController::class, 'insuracePage'])->name('insurance.content');
    Route::get('/insurance/products/{id}', [ProductController::class, 'product'])->name('insurance.product');
    Route::get('/insurance-products/data', [ProductController::class, 'getInsuranceProduct'])->name('insurance.products.data');
    // ** END INSURANCE

    // // ** CATEGORY
    // Route::get('/insurance-category', [ProductController::class, 'categoriesInsurance'])->name('insurance.categories');
    // Route::get('/insurance-category/data', [ProductController::class, 'getCategoryInsurance'])->name('insurance.categories.data');
    // // ** END CATEGORY

    // * END INSURANCE

    // * NETWORKS

    // ** GET NETWORKS
    Route::get('networks', [NetworkController::class, 'index'])->name('network.index');
    Route::get('networks/data', [NetworkController::class, 'getNetworkData'])->name('network.data');
    // ** END NETWORKS

    // * SETTINGS

    // ** GET SLIDERS
    Route::get('settings/home-sliders', [SliderController::class, 'index'])->name('sliders.index');
    Route::get('settings/home-sliders/data', [SliderController::class, 'getSliderData'])->name('sliders.data');
    // ** END GET SLIDERS



    // * STORE DATA

    // ** STORE CATEGORY
    Route::post('/store-category', [StoreCategoryController::class, 'storeCategory'])->name('category.store');

    // ** GET CATEGORY BY ID
    Route::get('/store-category/{types}/{id}', [StoreCategoryController::class, 'getCategoriesID'])->name('category.get.id');

    // ** PUT CATEGORY
    Route::post('/put-category', [StoreCategoryController::class, 'putCategory'])->name('category.put');

    // ** DELETE CATEGORY
    Route::delete('/delete-category/{types}/{id}/{id_en}', [StoreCategoryController::class, 'deleteCategory'])->name('category.delete');

    // ** CREATE POST
    Route::get('/article-news/create', [PostController::class, 'create'])->name('posts.create');

    // ** CREATE PRODUCT
    Route::get('/insurance-products/create', [ProductController::class, 'create'])->name('insurance.create');

    // ** STORE PRODUCT & POSTS
    Route::post('/article-news/create', [StorePostsController::class, 'store'])->name('posts.store');

    // ** GET WEB SETTINGS
    Route::get('/web-settings', [SettingController::class, 'index'])->name('web.settings');
    Route::put('/web-settings/update', [RequestApiController::class, 'updateSettings'])->name('updateWebSettings');

    // ** GET PAGES
    Route::get('/web-pages', [PagesController::class, 'index'])->name('web.pages');
    Route::get('/web-pages/{id}', [PagesController::class, 'getPagesById'])->name('web.pages.detail');
    Route::put('/web-pages/{id}', [PagesController::class, 'update'])->name('web.pages.update');
    // ** UPDATE PAGES DATA SECTION
    Route::post('/web-pages/update-section', [PagesController::class, 'updateSection'])->name('updateSection');


    // ** GET POST SOCIAL RESPONSIBILITY
    Route::get('/corporate-social-responsibility', [SocialResponses::class, 'index'])->name('social.responsibility');
});
