import { useState, useEffect } from 'react';

interface AdminInfo {
    id: number;
    admin_id: number;
    name: string;
    status: 'available' | 'busy';
    skills: string[];
    skill_level: 'beginner' | 'intermediate' | 'advanced' | 'expert';
    signature_products: string;
    image: string | null;
    created_at: string;
    updated_at: string;
}
 
interface UseAdminsReturn {
    admins: AdminInfo[];
    loading: boolean;
    error: string | null;
    refetch: () => Promise<void>;
}

export const useAdmins = (): UseAdminsReturn => {
    const [admins, setAdmins] = useState<AdminInfo[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    const fetchAdmins = async () => {
        try {
            setLoading(true);
            setError(null);
            
            // Use your Laravel backend URL (change this to match your setup)
            const API_URL = 'http://localhost:8000/api/admins';
            
            const response = await fetch(API_URL);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('API Response:', data); // Debug log
            
            // Handle the response structure from your Laravel API
            if (data.success && data.data) {
                setAdmins(data.data);
            } else if (Array.isArray(data)) {
                setAdmins(data);
            } else {
                setAdmins([]);
            }
        } catch (err) {
            console.error('Error fetching admins:', err);
            setError(err instanceof Error ? err.message : 'An error occurred');
            setAdmins([]);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchAdmins();
    }, []);

    return {
        admins,
        loading,
        error,
        refetch: fetchAdmins,
    };
};