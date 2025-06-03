import { useState, FormEvent, useEffect } from "react";
import { useDispatch } from "react-redux";
import { toast } from "react-toastify";
import { PaymentElement, useStripe, useElements } from "@stripe/react-stripe-js";
import { useAppSelector } from "../../../redux/store";
import { paymentApi } from "../api/paymentApi";
import { clearCartItems, setValidCoupon } from "../../../redux/slices/cartSlice";
import { setCurrentUser } from "../../../redux/slices/userSlice";
import { StripePaymentResponse } from "../api/types";

interface CheckoutFormProps {
    clientSecret: string;
}

export default function CheckoutForm({ clientSecret }: CheckoutFormProps) {
    const { cartItems } = useAppSelector(state => state.cart);
    const { token } = useAppSelector(state => state.user);
    const stripe = useStripe();
    const elements = useElements();
    const dispatch = useDispatch();

    const [message, setMessage] = useState<string | null>(null);
    const [isProcessing, setIsProcessing] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    // Wait for Stripe and Elements to be ready
    useEffect(() => {
        if (stripe && elements && clientSecret) {
            setIsLoading(false);
        }
    }, [stripe, elements, clientSecret]);

    const storeOrder = async (): Promise<void> => {
        try {
            const response = await paymentApi.storeOrder(cartItems, token);

            dispatch(clearCartItems());
            dispatch(setValidCoupon({
                name: '',
                discount: 0
            }));
            dispatch(setCurrentUser(response.user));

            toast.success('Payment completed successfully!');
        } catch (error) {
            console.error('Failed to store order:', error);
            toast.error('Failed to store order. Please contact support.');
            throw error; // Re-throw to handle in handleSubmit
        }
    };

    const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!stripe || !elements) {
            setMessage("Payment system is not ready. Please refresh the page.");
            return;
        }

        if (!clientSecret) {
            setMessage("Payment initialization failed. Please try again.");
            return;
        }

        setIsProcessing(true);
        setMessage(null);

        try {
            // Confirm payment with Stripe
            const { error, paymentIntent } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    // Add return URL for redirect-based payment methods
                    return_url: `${window.location.origin}/payment-success`,
                },
                redirect: "if_required",
            }) as StripePaymentResponse;

            if (error) {
                console.error('Payment error:', error);
                
                if (error.type === "card_error" || error.type === "validation_error") {
                    setMessage(error.message || "Payment failed. Please check your card details.");
                } else {
                    setMessage("An unexpected error occurred. Please try again.");
                }
                return;
            }

            if (paymentIntent && paymentIntent.status === 'succeeded') {
                console.log('Payment succeeded:', paymentIntent);
                await storeOrder();
            } else {
                setMessage("Payment was not completed. Please try again.");
            }
        } catch (error) {
            console.error('Payment processing error:', error);
            setMessage('Payment failed. Please try again or contact support.');
        } finally {
            setIsProcessing(false);
        }
    };

    // Show loading state
    if (isLoading) {
        return (
            <div className="d-flex justify-content-center align-items-center p-4">
                <div className="spinner-border text-primary me-2" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
                <span>Loading payment form...</span>
            </div>
        );
    }

    return (
        <div className="payment-form-container">
            <form id="payment-form" onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label htmlFor="payment-element" className="form-label">
                        Payment Details
                    </label>
                    <div className="border rounded p-3">
                        <PaymentElement 
                            id="payment-element"
                            options={{
                                layout: "tabs"
                            }}
                        />
                    </div>
                </div>

                {message && (
                    <div className="alert alert-danger mb-3" role="alert">
                        {message}
                    </div>
                )}

                <button
                    disabled={isProcessing || !stripe || !elements}
                    id="submit"
                    type="submit"
                    className="btn btn-primary w-100 py-2"
                    style={{ fontSize: '16px' }}
                >
                    <span id="button-text">
                        {isProcessing ? (
                            <>
                                <span className="spinner-border spinner-border-sm me-2" role="status">
                                    <span className="visually-hidden">Processing...</span>
                                </span>
                                Processing Payment...
                            </>
                        ) : (
                            "Pay Now"
                        )}
                    </span>
                </button>
            </form>
        </div>
    );
}