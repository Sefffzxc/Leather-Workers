<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentService $paymentService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            Log::info('Order creation request received', [
                'user_id' => $request->user()->id,
                'request_data' => $request->all()
            ]);
            
            // Handle both 'products' and 'cartItems' for backward compatibility
            $items = $request->has('cartItems') ? $request->cartItems : $request->products;
            
            if (!$items || !is_array($items) || empty($items)) {
                Log::error('No items provided in order request', [
                    'user_id' => $request->user()->id,
                    'has_cartItems' => $request->has('cartItems'),
                    'has_products' => $request->has('products')
                ]);
                
                return response()->json([
                    'error' => 'No items provided for order'
                ], Response::HTTP_BAD_REQUEST);
            }
 
            // Validate the request based on the data structure
            if ($request->has('cartItems')) {
                $request->validate([
                    'cartItems' => 'required|array|min:1',
                    'cartItems.*.ref' => 'required',
                    'cartItems.*.qty' => 'required|integer|min:1',
                    'cartItems.*.price' => 'required|numeric|min:0',
                    'cartItems.*.name' => 'required|string',
                    'paymentIntentId' => 'nullable|string', // Optional payment intent ID
                ]);
            } else {
                $request->validate([
                    'products' => 'required|array|min:1',
                    'products.*.ref' => 'required',
                    'products.*.qty' => 'required|integer|min:1',
                    'products.*.price' => 'required|numeric|min:0',
                ]);
            }

            Log::info('Creating order with items', [
                'user_id' => $request->user()->id,
                'items_count' => count($items),
                'payment_intent_id' => $request->paymentIntentId ?? 'none'
            ]);

            // Create the order(s)
            $this->orderService->createOrders($items, $request->user()->id, $request->paymentIntentId);
            
            // Refresh user data to get updated info
            $user = $request->user()->fresh();
            
            DB::commit();

            Log::info('Order created successfully', [
                'user_id' => $request->user()->id,
                'items_count' => count($items)
            ]);

            return response()->json([
                'user' => UserResource::make($user),
                'message' => 'Order created successfully',
                'success' => true
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            Log::error('Order validation failed', [
                'user_id' => $request->user()->id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Invalid order data provided',
                'details' => $e->errors()
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Order creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Failed to create order. Please try again.',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function payOrderByStripe(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $request->validate([
                'cartItems' => 'required|array|min:1',
                'cartItems.*.price' => 'required|numeric|min:0',
                'cartItems.*.qty' => 'required|integer|min:1',
            ]);

            Log::info('Processing payment request', [
                'user_id' => $request->user()->id,
                'items_count' => count($request->cartItems)
            ]);

            $paymentIntent = $this->paymentService->createPaymentIntent($request->cartItems);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Payment validation failed', [
                'user_id' => $request->user()->id,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'error' => 'Invalid payment data',
                'details' => $e->errors()
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            Log::error('Payment Intent creation failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Payment processing failed. Please check your configuration and try again.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}