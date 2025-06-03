import React from 'react';
 
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

interface AdminsListProps {
    admins: AdminInfo[];
    loading?: boolean;
}

const AdminsList: React.FC<AdminsListProps> = ({ admins, loading = false }) => {
    if (loading) {
        return (
            <div className="text-center py-5">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
                <p className="mt-3 text-muted">Loading artisans...</p>
            </div>
        );
    }

    if (admins.length === 0) {
        return (
            <div className="text-center py-5">
                <div className="mb-4">
                    <i className="bi bi-people" style={{ fontSize: '3rem', color: '#cbd5e0' }}></i>
                </div>
                <h4 className="text-muted mb-2">No Artisans Available</h4>
                <p className="text-muted">Check back later for our craftspeople.</p>
            </div>
        );
    }

    const getImageUrl = (imagePath: string | null, baseUrl: string = 'http://localhost:8000') => {
        if (!imagePath) {
            return `${baseUrl}/images/default-avatar.png`;
        }
        return `${baseUrl}/storage/${imagePath}`;
    };

    const getSkillLevelColor = (level: string) => {
        const colors = {
            beginner: '#10b981',
            intermediate: '#3b82f6', 
            advanced: '#8b5cf6',
            expert: '#f59e0b'
        };
        return colors[level as keyof typeof colors] || '#6b7280';
    };

    return (
        <div className="row g-4">
            {admins.map((admin) => (
                <div key={admin.id} className="col-12 col-md-6 col-xl-4">
                    <div 
                        className="card border-0 h-100"
                        style={{
                            borderRadius: '16px',
                            boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                            transition: 'all 0.2s ease',
                            backgroundColor: '#ffffff'
                        }}
                        onMouseEnter={(e) => {
                            e.currentTarget.style.transform = 'translateY(-4px)';
                            e.currentTarget.style.boxShadow = '0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
                        }}
                        onMouseLeave={(e) => {
                            e.currentTarget.style.transform = 'translateY(0)';
                            e.currentTarget.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
                        }}
                    >
                        <div className="card-body p-4">
                            {/* Header with Avatar and Status */}
                            <div className="d-flex align-items-center mb-3">
                                <div className="position-relative">
                                    <img
                                        src={getImageUrl(admin.image)}
                                        alt={admin.name}
                                        className="rounded-circle"
                                        style={{
                                            width: '60px',
                                            height: '60px',
                                            objectFit: 'cover'
                                        }}
                                        onError={(e) => {
                                            const target = e.target as HTMLImageElement;
                                            target.src = 'http://localhost:8000/images/default-avatar.png';
                                        }}
                                    />
                                    <div 
                                        className="position-absolute rounded-circle"
                                        style={{
                                            width: '16px',
                                            height: '16px',
                                            backgroundColor: admin.status === 'available' ? '#10b981' : '#f59e0b',
                                            bottom: '2px',
                                            right: '2px',
                                            border: '2px solid white'
                                        }}
                                    ></div>
                                </div>
                                <div className="ms-3 flex-grow-1">
                                    <h5 className="mb-1 fw-semibold" style={{ color: '#111827' }}>
                                        {admin.name}
                                    </h5>
                                    <div className="d-flex align-items-center gap-2">
                                        <span 
                                            className="badge"
                                            style={{
                                                backgroundColor: getSkillLevelColor(admin.skill_level),
                                                color: 'white',
                                                fontSize: '0.75rem',
                                                padding: '0.25rem 0.5rem',
                                                borderRadius: '6px',
                                                textTransform: 'capitalize'
                                            }}
                                        >
                                            {admin.skill_level}
                                        </span>
                                        <span 
                                            className="text-muted small"
                                            style={{ fontSize: '0.875rem' }}
                                        >
                                            {admin.status === 'available' ? 'Available' : 'Busy'}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Specialties */}
                            {admin.signature_products && (
                                <div className="mb-3">
                                    <p 
                                        className="text-muted mb-0"
                                        style={{
                                            fontSize: '0.875rem',
                                            lineHeight: '1.5',
                                            display: '-webkit-box',
                                            WebkitLineClamp: 2,
                                            WebkitBoxOrient: 'vertical',
                                            overflow: 'hidden'
                                        }}
                                    >
                                        {admin.signature_products}
                                    </p>
                                </div>
                            )}

                            {/* Skills */}
                            {admin.skills && admin.skills.length > 0 && (
                                <div className="mb-4">
                                    <div className="d-flex flex-wrap gap-1">
                                        {admin.skills.slice(0, 3).map((skill, index) => (
                                            <span
                                                key={index}
                                                className="badge bg-light text-dark"
                                                style={{
                                                    fontSize: '0.75rem',
                                                    padding: '0.375rem 0.5rem',
                                                    borderRadius: '6px',
                                                    fontWeight: '500'
                                                }}
                                            >
                                                {skill}
                                            </span>
                                        ))}
                                        {admin.skills.length > 3 && (
                                            <span
                                                className="badge bg-light text-muted"
                                                style={{
                                                    fontSize: '0.75rem',
                                                    padding: '0.375rem 0.5rem',
                                                    borderRadius: '6px'
                                                }}
                                            >
                                                +{admin.skills.length - 3}
                                            </span>
                                        )}
                                    </div>
                                </div>
                            )}

                            {/* Contact Button */}
                            <button
                                className="btn w-100"
                                disabled={admin.status !== 'available'}
                                style={{
                                    backgroundColor: admin.status === 'available' ? '#3b82f6' : '#e5e7eb',
                                    color: admin.status === 'available' ? 'white' : '#9ca3af',
                                    border: 'none',
                                    borderRadius: '8px',
                                    fontWeight: '500',
                                    padding: '0.75rem',
                                    fontSize: '0.875rem',
                                    transition: 'all 0.2s ease'
                                }}
                                onMouseEnter={(e) => {
                                    if (admin.status === 'available') {
                                        e.currentTarget.style.backgroundColor = '#2563eb';
                                    }
                                }}
                                onMouseLeave={(e) => {
                                    if (admin.status === 'available') {
                                        e.currentTarget.style.backgroundColor = '#3b82f6';
                                    }
                                }}
                            >
                                {admin.status === 'available' ? 'Contact Artisan' : 'Currently Unavailable'}
                            </button>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default AdminsList;