<?php
 
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AdminInfoApiController;

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{color}/color', [ProductController::class, 'filterProductsByColor']);
Route::get('products/{size}/size', [ProductController::class, 'filterProductsBySize']);
Route::get('products/{searchTerm}/find', [ProductController::class, 'findProductsByTerm']);
Route::get('product/{product}/show', [ProductController::class, 'show']);

Route::post('user/register', [UserController::class, 'store']);
Route::post('user/login', [UserController::class, 'auth']);

Route::prefix('admins')->group(function () {
    Route::get('/', [AdminInfoApiController::class, 'index']); // Get all admins
    Route::get('/available', [AdminInfoApiController::class, 'available']); // Get only available admins
    Route::get('/{id}', [AdminInfoApiController::class, 'show']); // Get specific admin
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('user', function (Request $request) {
        return [
            'user' => UserResource::make($request->user()),
            'access_token' => $request->bearerToken()
        ];
    });

    Route::post('user/logout', [UserController::class, 'logout']);
    Route::put('user/profile/update', [UserController::class, 'updateProfile']);

    Route::post('apply/coupon', [CouponController::class, 'applyCoupon']);

    Route::post('store/order', [OrderController::class, 'store']);
    Route::post('pay/order', [OrderController::class, 'payOrderByStripe']);

    Route::post('review/store', [ReviewController::class, 'store']);
    Route::post('review/delete', [ReviewController::class, 'delete']);
    Route::post('review/update', [ReviewController::class, 'update']);
});
/*
Route::prefix('admin')->group(function () {
    Route::post('/auth', [AdminAuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);
        
        // Add other resource routes
        Route::apiResource('colors', AdminColorController::class);
        Route::apiResource('sizes', AdminSizeController::class);
        Route::apiResource('products', AdminProductController::class);
        Route::apiResource('orders', AdminOrderController::class);
        Route::apiResource('users', AdminUserController::class);
        Route::apiResource('reviews', AdminReviewController::class);
        Route::apiResource('coupons', AdminCouponController::class);
    });
});
*/

// Web-based admin routes (different from API routes)
Route::middleware(['auth:admin'])->group(function () {
    // Existing admin routes...
    
    // Admin Info Routes (for web interface)
    Route::get('/info', [App\Http\Controllers\Admin\AdminInfoController::class, 'index'])->name('admin.info.index');
    Route::put('/info', [App\Http\Controllers\Admin\AdminInfoController::class, 'update'])->name('admin.info.update');
});

Route::get('test-stripe', function() {
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    return response()->json(['status' => 'Stripe configured correctly']);
});

Route::get('debug/stripe', function() {
    try {
        $stripeKey = config('services.stripe.secret');
        $stripePublic = config('services.stripe.key');
        
        if (empty($stripeKey)) {
            return response()->json([
                'error' => 'Stripe secret key is not configured',
                'config_exists' => false
            ], 500);
        }
        
        // Test Stripe connection
        \Stripe\Stripe::setApiKey($stripeKey);
        
        // Try to create a simple payment intent
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => 100, // $1.00 for testing
            'currency' => 'usd',
        ]);
        
        return response()->json([
            'stripe_configured' => true,
            'public_key_exists' => !empty($stripePublic),
            'secret_key_exists' => !empty($stripeKey),
            'test_payment_intent_id' => $paymentIntent->id,
            'message' => 'Stripe is working correctly'
        ]);
        
    } catch (\Stripe\Exception\ApiErrorException $e) {
        return response()->json([
            'error' => 'Stripe API Error: ' . $e->getMessage(),
            'stripe_code' => $e->getStripeCode(),
            'type' => get_class($e)
        ], 500);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'General Error: ' . $e->getMessage(),
            'type' => get_class($e)
        ], 500);
    }
});
