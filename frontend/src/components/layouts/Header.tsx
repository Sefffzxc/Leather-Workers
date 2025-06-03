import { useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { toast } from 'react-toastify';
import { useAppDispatch, useAppSelector } from '../../redux/store';
import { authApi } from '../../features/auth/api/authApi';
import { setCurrentUser, setLoggedInOut, setToken } from '../../redux/slices/userSlice';

export default function Header() {
    const { isLoggedIn, token, user } = useAppSelector(state => state.user);
    const { cartItems } = useAppSelector(state=> state.cart);
    const dispatch = useAppDispatch();
    const location = useLocation();

    const getLoggedInUser = async () => {
        try { 
            const response = await authApi.getCurrentUser(token);
            dispatch(setCurrentUser(response.user));
        } catch (error: any) {
            if (error?.response?.status === 401) {
                dispatch(setCurrentUser(null));
                dispatch(setToken(''));
                dispatch(setLoggedInOut(false));
            }
            console.error(error);
        }
    };

    const logoutUser = async () => {
        try {
            const response = await authApi.logout(token);
            dispatch(setCurrentUser(null));
            dispatch(setToken(''));
            dispatch(setLoggedInOut(false));
            toast.success(response.message);
        } catch (error) {
            console.error(error);
            toast.error('Logout failed');
        }
    };

    useEffect(() => {
        if (token) getLoggedInUser();
    }, [token, dispatch]);

    return (
        <nav className="navbar navbar-expand-lg navbar-light sticky-top shadow-lg" style={{
            background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            backdropFilter: 'blur(20px)',
            borderBottom: '1px solid rgba(255, 255, 255, 0.1)',
            padding: '0.8rem 0'
        }}>
            <div className="container-fluid px-4">
                {/* Enhanced Brand Logo */}
                <Link className="navbar-brand d-flex align-items-center" to="/" style={{
                    color: 'white',
                    fontWeight: '700',
                    fontSize: '1.6rem',
                    textDecoration: 'none',
                    transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                    textShadow: '0 2px 4px rgba(0,0,0,0.3)'
                }}>
                    <div className="d-flex align-items-center justify-content-center me-3" style={{
                        width: '45px',
                        height: '45px',
                        background: 'rgba(255, 255, 255, 0.2)',
                        borderRadius: '15px',
                        transition: 'all 0.3s ease',
                        boxShadow: '0 4px 15px rgba(0, 0, 0, 0.1)',
                        border: '1px solid rgba(255, 255, 255, 0.2)'
                    }}>
                        <img 
                            src="/public/logo.png" 
                            alt="Shop Icon" 
                            style={{ width: '2rem', height: '2rem' }} 
                            />
                    </div>
                    <span className="d-none d-md-inline">kariktan</span>
                </Link>

                {/* Stylized Mobile Toggle */}
                <button
                    className="navbar-toggler border-0 p-0"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                    style={{
                        background: 'rgba(255, 255, 255, 0.2)',
                        borderRadius: '12px',
                        padding: '10px 14px',
                        backdropFilter: 'blur(10px)',
                        border: '1px solid rgba(255, 255, 255, 0.1)'
                    }}
                >
                    <span style={{ 
                        display: 'block',
                        width: '20px',
                        height: '2px',
                        background: 'white',
                        borderRadius: '2px',
                        position: 'relative',
                        transition: 'all 0.3s ease'
                    }}></span>
                    <span style={{ 
                        display: 'block',
                        width: '20px',
                        height: '2px',
                        background: 'white',
                        borderRadius: '2px',
                        margin: '4px 0',
                        transition: 'all 0.3s ease'
                    }}></span>
                    <span style={{ 
                        display: 'block',
                        width: '20px',
                        height: '2px',
                        background: 'white',
                        borderRadius: '2px',
                        transition: 'all 0.3s ease'
                    }}></span>
                </button>

                {/* Navigation Menu */}
                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav mx-auto mb-2 mb-lg-0 d-flex align-items-center gap-1">
                        
                        {/* Home Link */}
                        <li className="nav-item">
                            <Link
                                className="nav-link d-flex align-items-center px-4 py-2 rounded-pill position-relative"
                                to="/"
                                style={{
                                    color: 'white',
                                    fontWeight: '600',
                                    textDecoration: 'none',
                                    transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                    background: location.pathname === '/' 
                                        ? 'rgba(255, 255, 255, 0.25)' 
                                        : 'transparent',
                                    backdropFilter: location.pathname === '/' ? 'blur(10px)' : 'none',
                                    border: location.pathname === '/' 
                                        ? '1px solid rgba(255, 255, 255, 0.3)' 
                                        : '1px solid transparent',
                                    fontSize: '0.95rem',
                                    letterSpacing: '0.3px'
                                }}
                                onMouseEnter={(e) => {
                                    if (location.pathname !== '/') {
                                        e.target.style.background = 'rgba(255, 255, 255, 0.15)';
                                        e.target.style.transform = 'translateY(-1px)';
                                    }
                                }}
                                onMouseLeave={(e) => {
                                    if (location.pathname !== '/') {
                                        e.target.style.background = 'transparent';
                                        e.target.style.transform = 'translateY(0)';
                                    }
                                }}
                            >
                                <i className="bi bi-house-fill me-2" style={{ fontSize: '1rem' }} />
                                Home
                            </Link>
                        </li>

                        {/* Authentication Conditional Rendering */}
                        {isLoggedIn ? (
                            <>
                                {/* Profile Link */}
                                <li className="nav-item">
                                    <Link
                                        className="nav-link d-flex align-items-center px-4 py-2 rounded-pill"
                                        to="/profile"
                                        style={{
                                            color: 'white',
                                            fontWeight: '600',
                                            textDecoration: 'none',
                                            transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                            background: location.pathname === '/profile' 
                                                ? 'rgba(255, 255, 255, 0.25)' 
                                                : 'transparent',
                                            backdropFilter: location.pathname === '/profile' ? 'blur(10px)' : 'none',
                                            border: location.pathname === '/profile' 
                                                ? '1px solid rgba(255, 255, 255, 0.3)' 
                                                : '1px solid transparent',
                                            fontSize: '0.95rem',
                                            letterSpacing: '0.3px'
                                        }}
                                        onMouseEnter={(e) => {
                                            if (location.pathname !== '/profile') {
                                                e.target.style.background = 'rgba(255, 255, 255, 0.15)';
                                                e.target.style.transform = 'translateY(-1px)';
                                            }
                                        }}
                                        onMouseLeave={(e) => {
                                            if (location.pathname !== '/profile') {
                                                e.target.style.background = 'transparent';
                                                e.target.style.transform = 'translateY(0)';
                                            }
                                        }}
                                    >
                                        <i className="bi bi-person-circle me-2" style={{ fontSize: '1rem' }} />
                                        <span className="d-none d-lg-inline">{user?.name}</span>
                                        <span className="d-lg-none">Profile</span>
                                    </Link>
                                </li>

                                {/* Logout Button */}
                                <li className="nav-item">
                                    <button
                                        className="nav-link d-flex align-items-center px-4 py-2 rounded-pill border-0"
                                        onClick={logoutUser}
                                        style={{
                                            color: 'white',
                                            fontWeight: '600',
                                            background: 'transparent',
                                            transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                            cursor: 'pointer',
                                            fontSize: '0.95rem',
                                            letterSpacing: '0.3px',
                                            border: '1px solid transparent'
                                        }}
                                        onMouseEnter={(e) => {
                                            e.target.style.background = 'rgba(255, 99, 99, 0.2)';
                                            e.target.style.transform = 'translateY(-1px)';
                                            e.target.style.border = '1px solid rgba(255, 99, 99, 0.3)';
                                        }}
                                        onMouseLeave={(e) => {
                                            e.target.style.background = 'transparent';
                                            e.target.style.transform = 'translateY(0)';
                                            e.target.style.border = '1px solid transparent';
                                        }}
                                    >
                                        <i className="bi bi-box-arrow-right me-2" style={{ fontSize: '1rem' }} />
                                        Logout
                                    </button>
                                </li>
                            </>
                        ) : (
                            <>
                                {/* Register Link */}
                                <li className="nav-item">
                                    <Link
                                        className="nav-link d-flex align-items-center px-4 py-2 rounded-pill"
                                        to="/register"
                                        style={{
                                            color: 'white',
                                            fontWeight: '600',
                                            textDecoration: 'none',
                                            transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                            background: location.pathname === '/register' 
                                                ? 'rgba(255, 255, 255, 0.25)' 
                                                : 'transparent',
                                            backdropFilter: location.pathname === '/register' ? 'blur(10px)' : 'none',
                                            border: location.pathname === '/register' 
                                                ? '1px solid rgba(255, 255, 255, 0.3)' 
                                                : '1px solid transparent',
                                            fontSize: '0.95rem',
                                            letterSpacing: '0.3px'
                                        }}
                                        onMouseEnter={(e) => {
                                            if (location.pathname !== '/register') {
                                                e.target.style.background = 'rgba(255, 255, 255, 0.15)';
                                                e.target.style.transform = 'translateY(-1px)';
                                            }
                                        }}
                                        onMouseLeave={(e) => {
                                            if (location.pathname !== '/register') {
                                                e.target.style.background = 'transparent';
                                                e.target.style.transform = 'translateY(0)';
                                            }
                                        }}
                                    >
                                        <i className="bi bi-person-plus-fill me-2" style={{ fontSize: '1rem' }} />
                                        Register
                                    </Link>
                                </li>

                                {/* Login Link */}
                                <li className="nav-item">
                                    <Link
                                        className="nav-link d-flex align-items-center px-4 py-2 rounded-pill"
                                        to="/login"
                                        style={{
                                            color: 'white',
                                            fontWeight: '600',
                                            textDecoration: 'none',
                                            transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                            background: location.pathname === '/login' 
                                                ? 'rgba(255, 255, 255, 0.25)' 
                                                : 'transparent',
                                            backdropFilter: location.pathname === '/login' ? 'blur(10px)' : 'none',
                                            border: location.pathname === '/login' 
                                                ? '1px solid rgba(255, 255, 255, 0.3)' 
                                                : '1px solid transparent',
                                            fontSize: '0.95rem',
                                            letterSpacing: '0.3px'
                                        }}
                                        onMouseEnter={(e) => {
                                            if (location.pathname !== '/login') {
                                                e.target.style.background = 'rgba(255, 255, 255, 0.15)';
                                                e.target.style.transform = 'translateY(-1px)';
                                            }
                                        }}
                                        onMouseLeave={(e) => {
                                            if (location.pathname !== '/login') {
                                                e.target.style.background = 'transparent';
                                                e.target.style.transform = 'translateY(0)';
                                            }
                                        }}
                                    >
                                        <i className="bi bi-box-arrow-in-right me-2" style={{ fontSize: '1rem' }} />
                                        Login
                                    </Link>
                                </li>
                            </>
                        )}

                        {/* Enhanced Cart with Animated Badge */}
                        <li className="nav-item">
                            <Link
                                className="nav-link d-flex align-items-center px-4 py-2 rounded-pill position-relative"
                                to="/cart"
                                style={{
                                    color: 'white',
                                    fontWeight: '600',
                                    textDecoration: 'none',
                                    transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                    background: location.pathname === '/cart' 
                                        ? 'rgba(255, 255, 255, 0.25)' 
                                        : 'transparent',
                                    backdropFilter: location.pathname === '/cart' ? 'blur(10px)' : 'none',
                                    border: location.pathname === '/cart' 
                                        ? '1px solid rgba(255, 255, 255, 0.3)' 
                                        : '1px solid transparent',
                                    fontSize: '0.95rem',
                                    letterSpacing: '0.3px'
                                }}
                                onMouseEnter={(e) => {
                                    if (location.pathname !== '/cart') {
                                        e.target.style.background = 'rgba(255, 255, 255, 0.15)';
                                        e.target.style.transform = 'translateY(-1px)';
                                    }
                                }}
                                onMouseLeave={(e) => {
                                    if (location.pathname !== '/cart') {
                                        e.target.style.background = 'transparent';
                                        e.target.style.transform = 'translateY(0)';
                                    }
                                }}
                            >
                                <i className="bi bi-bag-fill me-2" style={{ fontSize: '1rem' }} />
                                Cart
                                {cartItems.length > 0 && (
                                    <span 
                                        className="position-absolute badge rounded-pill"
                                        style={{
                                            background: 'linear-gradient(45deg, #ff6b6b, #ff8e8e)',
                                            color: 'white',
                                            fontSize: '0.7rem',
                                            fontWeight: '700',
                                            padding: '4px 8px',
                                            top: '8px',
                                            right: '8px',
                                            minWidth: '20px',
                                            height: '20px',
                                            display: 'flex',
                                            alignItems: 'center',
                                            justifyContent: 'center',
                                            boxShadow: '0 2px 8px rgba(255, 107, 107, 0.4)',
                                            border: '2px solid rgba(255, 255, 255, 0.3)',
                                            animation: 'pulse 2s infinite'
                                        }}
                                    >
                                        {cartItems.length}
                                    </span>
                                )}
                            </Link>
                        </li>
                    </ul>

                    {/* Admin Button - Far Right */}
                    <div className="d-flex align-items-center ms-auto">
                        <a
                            href="http://localhost:8000"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="btn d-flex align-items-center px-3 py-2 rounded-pill"
                            style={{
                                color: 'white',
                                fontWeight: '600',
                                textDecoration: 'none',
                                transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                                background: 'rgba(255, 215, 0, 0.2)',
                                backdropFilter: 'blur(10px)',
                                border: '1px solid rgba(255, 215, 0, 0.3)',
                                fontSize: '0.9rem',
                                letterSpacing: '0.3px'
                            }}
                            onMouseEnter={(e) => {
                                e.target.style.background = 'rgba(255, 215, 0, 0.3)';
                                e.target.style.transform = 'translateY(-1px)';
                                e.target.style.boxShadow = '0 4px 15px rgba(255, 215, 0, 0.2)';
                            }}
                            onMouseLeave={(e) => {
                                e.target.style.background = 'rgba(255, 215, 0, 0.2)';
                                e.target.style.transform = 'translateY(0)';
                                e.target.style.boxShadow = 'none';
                            }}
                        >
                            <i className="bi bi-gear-fill me-2" style={{ fontSize: '1rem' }} />
                            Admin
                        </a>
                    </div>
                </div>
            </div>

            {/* Add CSS for animations */}
            <style jsx>{`
                @keyframes pulse {
                    0% {
                        transform: scale(1);
                    }
                    50% {
                        transform: scale(1.1);
                    }
                    100% {
                        transform: scale(1);
                    }
                }

                .navbar-brand:hover > div {
                    transform: rotate(360deg) scale(1.1);
                    background: rgba(255, 255, 255, 0.3) !important;
                }

                .nav-link:hover {
                    text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
                }

                .navbar-toggler:hover {
                    background: rgba(255, 255, 255, 0.3) !important;
                    transform: scale(1.05);
                }

                .navbar-toggler:hover span {
                    background: rgba(255, 255, 255, 0.9) !important;
                }

                @media (max-width: 991.98px) {
                    .navbar-collapse {
                        background: rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(20px);
                        border-radius: 15px;
                        margin-top: 1rem;
                        padding: 1rem;
                        border: 1px solid rgba(255, 255, 255, 0.2);
                    }
                    
                    .navbar-nav {
                        gap: 0.5rem;
                    }
                    
                    .nav-link {
                        margin: 0.2rem 0;
                        text-align: center;
                    }
                }

                /* Smooth scroll behavior for sticky nav */
                html {
                    scroll-padding-top: 80px;
                }

                /* Enhanced focus states for accessibility */
                .nav-link:focus,
                .navbar-brand:focus,
                .navbar-toggler:focus {
                    outline: 2px solid rgba(255, 255, 255, 0.8);
                    outline-offset: 2px;
                    border-radius: 8px;
                }
            `}</style>
        </nav>
    );
}