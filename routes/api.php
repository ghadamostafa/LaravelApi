<?php

// use App\Http\Controllers\ProductBuyerTransactionController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('users', UserController::class,['except' => ['create', 'edit']]);
Route::get('users/verify/{token}', 'UserController@verify')->name('verify');
Route::get('users/{user}/resend', 'UserController@resend')->name('resend');

Route::apiResource('sellers',SellerController::class,['only' => ['index', 'show']]);
Route::apiResource('sellers.transactions',SellerTransactionController::class,['only' => ['index']]);
Route::apiResource('sellers.categories',SellerCategoryController::class,['only' => ['index']]);
Route::apiResource('sellers.buyers',SellerBuyerController::class,['only' => ['index']]);
Route::apiResource('sellers.products',SellerProductController::class,['only' => ['index','store','update','destroy']]);

Route::apiResource('buyers',BuyerController::class,['only' => ['index', 'show']]);
Route::apiResource('buyers.transactions',BuyerTransactionController::class,['only' => ['index']]);
Route::apiResource('buyers.products',BuyerProductController::class,['only' => ['index']]);
Route::apiResource('buyers.sellers',BuyerSellerController::class,['only' => ['index']]);
Route::apiResource('buyers.categories',BuyerCategoryController::class,['only' => ['index']]);

Route::apiResource('products',ProductsController::class,['only' => ['index', 'show']]);
Route::apiResource('products.transactions',ProductTransactionController::class,['only' => ['index']]);
Route::apiResource('products.buyers',ProductBuyerController::class,['only' => ['index']]);
Route::apiResource('products.categories',ProductCategoryController::class,['only' => ['index','update','destroy']]);
Route::apiResource('products.buyers.transactions',ProductBuyerTransactionController::class,['only' => ['store']]);

Route::apiResource('transactions',TransactionController::class,['only' => ['index', 'show']]);
Route::apiResource('transactions.sellers',TransactionSellerController::class,['only' => ['index']]);
Route::apiResource('transactions.categories',TransactionCategoryController::class,['only' => ['index']]);

Route::apiResource('categories',CategoriesController::class,['except' => ['create', 'edit']]);
Route::apiResource('categories.products',CategoryProductController::class,['only' => ['index']]);
Route::apiResource('categories.sellers',CategorySellerController::class,['only' => ['index']]);
Route::apiResource('categories.buyers',CategoryBuyerController::class,['only' => ['index']]);
Route::apiResource('categories.transactions',CategoryTransactionController::class,['only' => ['index']]);

Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');