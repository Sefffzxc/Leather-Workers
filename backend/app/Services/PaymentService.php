<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
 
class PaymentService
{
    public function __construct()
    {
        // Set Stripe API key with proper validation
        $stripeKey = config('services.stripe.secret');
        
        if (empty($stripeKey)) {
            throw new \Exception('Stripe secret key is not configured. Please check your .env file.');
        }
        
        Stripe::setApiKey($stripeKey);
    }

    public function createPaymentIntent(array $cartItems): PaymentIntent
    {
        try {
            // Validate cart items
            if (empty($cartItems)) {
                throw new \Exception('Cart is empty');
            }

            // Calculate total amount
            $amount = $this->calculateTotalAmount($cartItems);
            
            if ($amount <= 0) {
                throw new \Exception('Invalid amount: ' . $amount);
            }
            
            Log::info('Creating payment intent', [
                'amount' => $amount,
                'amount_cents' => $amount * 100,
                'items_count' => count($cartItems),
                'cart_items' => $cartItems
            ]);

            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100), // Convert to cents and ensure integer
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'description' => 'Order payment for ' . count($cartItems) . ' items',
                'metadata' => [
                    'items_count' => count($cartItems),
                    'total_amount' => $amount
                ]
            ]);

            Log::info('Payment intent created successfully', [
                'payment_intent_id' => $paymentIntent->id,
                'amount_cents' => $paymentIntent->amount,
                'status' => $paymentIntent->status
            ]);

            return $paymentIntent;

        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error', [
                'error' => $e->getMessage(),
                'type' => $e->getStripeCode(),
                'http_status' => $e->getHttpStatus(),
                'stripe_code' => $e->getStripeCode(),
                'request_id' => $e->getRequestId(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Stripe API Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Payment Intent Creation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart_items' => $cartItems
            ]);
            
            throw $e;
        }
    }

    private function calculateTotalAmount(array $cartItems): float
    {
        $total = 0;
        
        foreach ($cartItems as $item) {
            // Handle both array and object formats
            $price = is_array($item) ? $item['price'] : $item->price;
            $qty = is_array($item) ? $item['qty'] : $item->qty;
            
            if (!is_numeric($price) || !is_numeric($qty) || $price <= 0 || $qty <= 0) {
                throw new \Exception('Invalid item data: price=' . $price . ', qty=' . $qty);
            }
            
            $total += $price * $qty;
        }
        
        return round($total, 2);
    }
}