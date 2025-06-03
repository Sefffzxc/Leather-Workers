import { useEffect, useState } from "react";
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import { paymentApi } from '../api/paymentApi';
import CheckoutForm from "./CheckoutForm";
import { useAppSelector } from "../../../redux/store";
import { env } from "../../../config/env";
import { toast } from 'react-toastify';
import { useNavigate } from 'react-router-dom';

export default function Stripe() {
    const { token, isLoggedIn } = useAppSelector(state => state.user);
    const { cartItems } = useAppSelector(state => state.cart);
    const navigate = useNavigate();
    
    const [clientSecret, setClientSecret] = useState<string>("");
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>("");
    
    // Initialize Stripe with better error handling
    const [stripePromise] = useState(() => {
        if (!env.STRIPE_PUBLIC_KEY) {
            console.error('Stripe public key is not configured');
            return null;
        }
        
        // Validate the public key format
        if (!env.STRIPE_PUBLIC_KEY.startsWith('pk_')) {
            console.error('Invalid Stripe public key format');
            return null;
        }
        
        try {
            return loadStripe(env.STRIPE_PUBLIC_KEY);
        } catch (error) {
            console.error('Failed to load Stripe:', error);
            return null;
        }
    });

    const fetchClientSecret = async () => {
        // Reset previous errors
        setError("");
        
        // Validation checks
        if (!isLoggedIn || !token) {
            setError("Please log in to continue");
            navigate('/login');
            return;
        }

        if (!cartItems || cartItems.length === 0) {
            setError("Your cart is empty");
            navigate('/cart');
            return;
        }

        // Validate cart items have required fields
        const invalidItems = cartItems.filter(item => 
            !item.price || !item.qty || item.qty <= 0 || item.price <= 0
        );

        if (invalidItems.length > 0) {
            setError("Some items in your cart are invalid");
            console.error('Invalid cart items:', invalidItems);
            return;
        }

        setIsLoading(true);

        try {
            console.log('Creating payment intent for cart items:', cartItems);
            const response = await paymentApi.createPaymentIntent(cartItems, token);
            
            if (response?.clientSecret) {
                setClientSecret(response.clientSecret);
                console.log('Payment intent created successfully');
            } else {
                throw new Error('No client secret received from server');
            }
        } catch (error: any) {
            console.error('Failed to create payment intent:', error);
            
            let errorMessage = 'Failed to initialize payment';
            
            if (error.response?.status === 401) {
                errorMessage = 'Authentication failed. Please log in again.';
                // Optionally redirect to login
                setTimeout(() => navigate('/login'), 2000);
            } else if (error.response?.status === 400) {
                errorMessage = error.response?.data?.error || 'Invalid payment request';
            } else if (error.response?.data?.error) {
                errorMessage = error.response.data.error;
            }
            
            setError(errorMessage);
            toast.error(errorMessage);
        } finally {
            setIsLoading(false);
        }
    };

    useEffect(() => {
        if (isLoggedIn && token && cartItems.length > 0) {
            fetchClientSecret();
        }
    }, [token, cartItems, isLoggedIn]);

    // Redirect if not logged in
    useEffect(() => {
        if (!isLoggedIn) {
            navigate('/login');
        }
    }, [isLoggedIn, navigate]);

    // Show loading state
    if (isLoading) {
        return (
            <div className="container py-5">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-body text-center py-5">
                                <div className="spinner-border text-primary mb-3" role="status">
                                    <span className="visually-hidden">Loading...</span>
                                </div>
                                <h5>Initializing Payment</h5>
                                <p className="text-muted">Please wait while we prepare your payment...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    // Show error state
    if (error) {
        return (
            <div className="container py-5">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="alert alert-danger" role="alert">
                            <h4 className="alert-heading">
                                <i className="bi bi-exclamation-triangle me-2"></i>
                                Payment Error
                            </h4>
                            <p className="mb-3">{error}</p>
                            <hr />
                            <div className="d-flex gap-2">
                                <button 
                                    className="btn btn-outline-danger" 
                                    onClick={() => {
                                        setError("");
                                        fetchClientSecret();
                                    }}
                                >
                                    <i className="bi bi-arrow-clockwise me-1"></i>
                                    Try Again
                                </button>
                                <button 
                                    className="btn btn-secondary" 
                                    onClick={() => navigate('/cart')}
                                >
                                    <i className="bi bi-cart me-1"></i>
                                    Back to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    // Show Stripe configuration error
    if (!stripePromise) {
        return (
            <div className="container py-5">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="alert alert-warning" role="alert">
                            <h4 className="alert-heading">Configuration Error</h4>
                            <p>Payment system is not properly configured. Please contact support.</p>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    // Wait for client secret
    if (!clientSecret) {
        return (
            <div className="container py-5">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-body text-center py-5">
                                <div className="spinner-grow text-primary mb-3" role="status">
                                    <span className="visually-hidden">Loading...</span>
                                </div>
                                <p className="text-muted">Preparing payment form...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="container py-5">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">
                            <h4 className="mb-0">
                                <i className="bi bi-credit-card me-2"></i>
                                Complete Your Payment
                            </h4>
                        </div>
                        <div className="card-body">
                            <Elements 
                                stripe={stripePromise} 
                                options={{ 
                                    clientSecret,
                                    appearance: {
                                        theme: 'stripe',
                                        variables: {
                                            colorPrimary: '#0d6efd',
                                        }
                                    }
                                }}
                            >
                                <CheckoutForm clientSecret={clientSecret} />
                            </Elements>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}