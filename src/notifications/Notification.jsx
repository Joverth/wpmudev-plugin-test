import React, { useEffect } from 'react';

const Notification = ({ message, status, onRemove }) => {
    useEffect(() => {
        // Automatically remove the notice after 5 seconds
        const timer = setTimeout(onRemove, 3000);
        return () => clearTimeout(timer);
    }, [onRemove]);
    const noticeClass = status === 'success' ? 'green' : 'red';
    return (
        <div
            role="alert"
            className={`sui-notice sui-notice-${noticeClass} d-block`}
            aria-live="assertive"
        >
            <div className="sui-notice-content">
                <div className="sui-notice-message">
                    <span className="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
                    <p>{message}</p>
                </div>
            </div>
        </div>
        
        
    );
};

export default Notification;
