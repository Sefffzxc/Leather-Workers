import axiosInstance from "../../../services/axios.instance";
import { getConfig } from "../../../utils/config";
import { CartItem } from "../../cart/types";
import { PaymentIntentResponse, StoreOrderResponse } from "./types";
import { toast } from 'react-toastify';
 
export const paymentApi = {
    storeOrder: async (cartItems: CartItem[], token: string): Promise<StoreOrderResponse> => {
        try {
            const response = await axiosInstance.post('store/order', { products: cartItems }, getConfig(token));
            return response.data;
        } catch (error: any) {
            console.error('Store order error:', error);
            
            if (error.response?.data?.error) {
                toast.error(error.response.data.error);
            } else {
                toast.error('Failed to create order. Please try again.');
            }
            
            throw error;
        }
    },

    createPaymentIntent: async (cartItems: CartItem[], token: string): Promise<PaymentIntentResponse> => {
        try {
            const response = await axiosInstance.post('pay/order', { cartItems }, getConfig(token));
            return response.data;
        } catch (error: any) {
            console.error('Payment intent error:', error);
            
            if (error.response?.data?.error) {
                toast.error(error.response.data.error);
            } else if (error.response?.status === 500) {
                toast.error('Payment service unavailable. Please check your Stripe configuration.');
            } else {
                toast.error('Payment processing failed. Please try again.');
            }
            
            throw error;
        }
    }
};