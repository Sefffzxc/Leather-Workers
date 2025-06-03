import { useProducts } from '../hooks/useProducts';
import { useAdmins } from '../hooks/useAdmins';
import Alert from '../../../components/ui/Alert/Alert';
import Spinner from '../../../components/ui/Spinner/Spinner';
import { FilterSection } from './FilterSection';
import ProductsList from '../../products/components/ProductsList';
import AdminsList from './AdminsList';
 
export default function Home() {
    const { 
        products, 
        colors, 
        sizes, 
        loading: productsLoading, 
        message, 
        filters, 
        setFilters 
    } = useProducts();
    
    const { 
        admins, 
        loading: adminsLoading, 
        error: adminsError, 
        refetch: refetchAdmins 
    } = useAdmins();

    const handleFilterChange = (filterName: string, value: string) => {
        setFilters(() => ({
            color: '',
            size: '',
            search: '',
            [filterName]: value
        }));
    };

    return (
        <div className="min-vh-100" style={{
            background: 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
            paddingTop: '2rem',
            paddingBottom: '3rem'
        }}>
            {/* Hero Section */}
            <div className="container-fluid px-4 mb-5">
                <div className="row justify-content-center">
                    <div className="col-12 col-lg-10">
                        <div className="text-center mb-5" style={{
                            background: 'rgba(255, 255, 255, 0.9)',
                            backdropFilter: 'blur(20px)',
                            borderRadius: '25px',
                            padding: '3rem 2rem',
                            boxShadow: '0 20px 40px rgba(0, 0, 0, 0.1)',
                            border: '1px solid rgba(255, 255, 255, 0.2)'
                        }}>
                            <h1 className="display-4 fw-bold mb-3" style={{
                                background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                WebkitBackgroundClip: 'text',
                                WebkitTextFillColor: 'transparent',
                                backgroundClip: 'text',
                                letterSpacing: '-0.5px'
                            }}>
                                Discover Amazing Products
                            </h1>
                            <p className="lead text-muted mb-4" style={{
                                fontSize: '1.2rem',
                                fontWeight: '400',
                                maxWidth: '600px',
                                margin: '0 auto'
                            }}>
                                Explore our curated collection of premium products, 
                                designed to elevate your lifestyle and exceed your expectations.
                            </p>
                            
                            {/* Product Stats */}
                            <div className="row justify-content-center mt-4">
                                <div className="col-auto">
                                    <div className="d-flex align-items-center justify-content-center" style={{
                                        background: 'linear-gradient(45deg, #667eea, #764ba2)',
                                        color: 'white',
                                        padding: '0.8rem 1.5rem',
                                        borderRadius: '50px',
                                        fontWeight: '600',
                                        fontSize: '0.95rem',
                                        boxShadow: '0 8px 25px rgba(102, 126, 234, 0.3)'
                                    }}>
                                        <i className="bi bi-box-seam me-2" style={{ fontSize: '1.1rem' }}></i>
                                        {products.length} Products Available
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Content Area */}
            <div className="container-fluid px-4">
                <div className="row justify-content-center">
                    <div className="col-12 col-lg-10">

                        {/* Artisans Section */}
                        <div className="mb-4" style={{
                            marginTop: '-1.5rem',
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdropFilter: 'blur(20px)',
                            borderRadius: '20px',
                            padding: '2rem',
                            boxShadow: '0 15px 35px rgba(0, 0, 0, 0.08)',
                            border: '1px solid rgba(255, 255, 255, 0.3)'
                        }}>
                            <div className="d-flex align-items-center justify-content-between mb-4 pb-3" style={{
                                borderBottom: '2px solid rgba(102, 126, 234, 0.1)'
                            }}>
                                <div className="d-flex align-items-center">
                                    <div className="d-flex align-items-center justify-content-center me-3" style={{
                                        width: '40px',
                                        height: '40px',
                                        background: 'linear-gradient(45deg, #667eea, #764ba2)',
                                        borderRadius: '12px'
                                    }}>
                                        <i className="bi bi-people-fill text-white" style={{ fontSize: '1rem' }}></i>
                                    </div>
                                    <div>
                                        <h3 className="mb-0 fw-bold" style={{
                                            color: '#2d3748',
                                            fontSize: '1.4rem'
                                        }}>
                                            Meet Our Artisans
                                        </h3>
                                        <p className="text-muted mb-0 small">
                                            Skilled craftspeople ready to bring your vision to life
                                        </p>
                                    </div>
                                </div>
                                
                                {adminsError && (
                                    <button 
                                        className="btn btn-sm btn-outline-primary"
                                        onClick={refetchAdmins}
                                        style={{ borderRadius: '8px' }}
                                    >
                                        <i className="bi bi-arrow-clockwise me-1"></i>
                                        Retry
                                    </button>
                                )}
                            </div>

                            {/* Admins Error State */}
                            {adminsError && (
                                <div className="mb-4">
                                    <Alert 
                                        type="error" 
                                        content={`Failed to load artisans: ${adminsError}`} 
                                    />
                                </div>
                            )}

                            {/* Admins List */}
                            <AdminsList admins={admins} loading={adminsLoading} />
                        </div>
                        {/* Enhanced Filter Section */}
                        <div className="mb-4" style={{
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdropFilter: 'blur(20px)',
                            borderRadius: '20px',
                            padding: '2rem',
                            boxShadow: '0 15px 35px rgba(0, 0, 0, 0.08)',
                            border: '1px solid rgba(255, 255, 255, 0.3)'
                        }}>
                            <div className="d-flex align-items-center mb-3">
                                <div className="d-flex align-items-center justify-content-center me-3" style={{
                                    width: '40px',
                                    height: '40px',
                                    background: 'linear-gradient(45deg, #667eea, #764ba2)',
                                    borderRadius: '12px'
                                }}>
                                    <i className="bi bi-funnel-fill text-white" style={{ fontSize: '1rem' }}></i>
                                </div>
                                <h3 className="mb-0 fw-bold" style={{
                                    color: '#2d3748',
                                    fontSize: '1.4rem'
                                }}>
                                    Filter Products
                                </h3>
                            </div>
                            
                            <FilterSection
                                colors={colors}
                                sizes={sizes}
                                filters={filters}
                                onFilterChange={handleFilterChange}
                            />
                        </div>

                        {/* Alert Messages */}
                        {message && (
                            <div className="mb-4" style={{
                                background: 'rgba(255, 255, 255, 0.95)',
                                backdropFilter: 'blur(20px)',
                                borderRadius: '15px',
                                padding: '0.5rem',
                                boxShadow: '0 10px 25px rgba(0, 0, 0, 0.06)',
                                border: '1px solid rgba(255, 255, 255, 0.3)'
                            }}>
                                <Alert type="info" content={message} />
                            </div>
                        )}

                        {/* Loading State */}
                        {productsLoading && (
                            <div className="text-center py-5" style={{
                                background: 'rgba(255, 255, 255, 0.95)',
                                backdropFilter: 'blur(20px)',
                                borderRadius: '20px',
                                boxShadow: '0 15px 35px rgba(0, 0, 0, 0.08)',
                                border: '1px solid rgba(255, 255, 255, 0.3)'
                            }}>
                                <div className="mb-3">
                                    <Spinner />
                                </div>
                                <p className="text-muted mb-0" style={{
                                    fontSize: '1.1rem',
                                    fontWeight: '500'
                                }}>
                                    Loading amazing products for you...
                                </p>
                            </div>
                        )}

                        {/* Products Grid */}
                        {!productsLoading && (
                            <div style={{
                                background: 'rgba(255, 255, 255, 0.95)',
                                backdropFilter: 'blur(20px)',
                                borderRadius: '20px',
                                padding: '2rem',
                                boxShadow: '0 15px 35px rgba(0, 0, 0, 0.08)',
                                border: '1px solid rgba(255, 255, 255, 0.3)'
                            }}>
                                {/* Products Header */}
                                <div className="d-flex align-items-center justify-content-between mb-4 pb-3" style={{
                                    borderBottom: '2px solid rgba(102, 126, 234, 0.1)'
                                }}>
                                    <div className="d-flex align-items-center">
                                        <div className="d-flex align-items-center justify-content-center me-3" style={{
                                            width: '40px',
                                            height: '40px',
                                            background: 'linear-gradient(45deg, #667eea, #764ba2)',
                                            borderRadius: '12px'
                                        }}>
                                            <i className="bi bi-grid-3x3-gap-fill text-white" style={{ fontSize: '1rem' }}></i>
                                        </div>
                                        <div>
                                            <h3 className="mb-0 fw-bold" style={{
                                                color: '#2d3748',
                                                fontSize: '1.4rem'
                                            }}>
                                                Our Products
                                            </h3>
                                            <p className="text-muted mb-0 small">
                                                Showing {products.length} result{products.length !== 1 ? 's' : ''}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {/* View Toggle (Optional Enhancement) */}
                                    <div className="d-none d-md-flex align-items-center" style={{
                                        background: 'rgba(102, 126, 234, 0.1)',
                                        borderRadius: '12px',
                                        padding: '0.5rem'
                                    }}>
                                        <button className="btn btn-sm me-1" style={{
                                            background: 'transparent',
                                            border: 'none',
                                            color: '#667eea',
                                            padding: '0.5rem 0.8rem',
                                            borderRadius: '8px'
                                        }}>
                                            <i className="bi bi-grid-3x3-gap"></i>
                                        </button>
                                        <button className="btn btn-sm" style={{
                                            background: 'transparent',
                                            border: 'none',
                                            color: '#999',
                                            padding: '0.5rem 0.8rem',
                                            borderRadius: '8px'
                                        }}>
                                            <i className="bi bi-list"></i>
                                        </button>
                                    </div>
                                </div>

                                {/* Products List Component */}
                                <ProductsList products={products} />

                                {/* Empty State */}
                                {products.length === 0 && !productsLoading && (
                                    <div className="text-center py-5">
                                        <div className="mb-4">
                                            <i className="bi bi-search" style={{
                                                fontSize: '4rem',
                                                color: '#e2e8f0'
                                            }}></i>
                                        </div>
                                        <h4 className="fw-bold text-muted mb-2">No Products Found</h4>
                                        <p className="text-muted mb-4">
                                            Try adjusting your filters or search terms to find what you're looking for.
                                        </p>
                                        <button 
                                            className="btn px-4 py-2"
                                            onClick={() => setFilters({ color: '', size: '', search: '' })}
                                            style={{
                                                background: 'linear-gradient(45deg, #667eea, #764ba2)',
                                                color: 'white',
                                                border: 'none',
                                                borderRadius: '12px',
                                                fontWeight: '600',
                                                boxShadow: '0 8px 25px rgba(102, 126, 234, 0.3)'
                                            }}
                                        >
                                            <i className="bi bi-arrow-clockwise me-2"></i>
                                            Clear All Filters
                                        </button>
                                    </div>
                                )}
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Additional CSS for enhanced aesthetics */}
            <style jsx>{`
                .btn:hover {
                    transform: translateY(-2px);
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                @media (max-width: 768px) {
                    .display-4 {
                        font-size: 2.5rem !important;
                    }
                    
                    .container-fluid {
                        padding-left: 1rem !important;
                        padding-right: 1rem !important;
                    }
                }
                
                /* Smooth scrolling */
                html {
                    scroll-behavior: smooth;
                }
                
                /* Custom scrollbar */
                ::-webkit-scrollbar {
                    width: 8px;
                }
                
                ::-webkit-scrollbar-track {
                    background: rgba(0, 0, 0, 0.05);
                }
                
                ::-webkit-scrollbar-thumb {
                    background: linear-gradient(45deg, #667eea, #764ba2);
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(45deg, #5a67d8, #6b46c1);
                }
            `}</style>
        </div>
    );
}