import apiFetch from '@wordpress/api-fetch';
import { Button, TextControl } from '@wordpress/components';
import { StrictMode, createRoot, render, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import Notification from '../notifications/Notification';

import "../scss/style.scss";

const domElement = document.getElementById( window.wpmudevPluginTest.dom_element_id );

const WPMUDEV_PluginTest = () => {
    const [response, setResponse] = useState(null);
    const [error, setError] = useState(null);
    const [notifications, setNotifications] = useState([]);
    const [postTypes, setPostTypes] = useState('');
    const handleRemoveNotification = (index) => {
        setNotifications(notifications.filter((_, i) => i !== index));
    };
    const txtDomain = 'wpmudev-plugin-test';
    const showNotification = (message, status) => {
        setNotifications([...notifications, { message, status }]);
    };
    const handleClick = async () => {
        try {
            const result = await apiFetch({ 
                path: wpmudevPluginTest.restEndpointPostScanAll,
                method: 'POST',
                data: { postTypes },
            });
            if (result.success) {
                setResponse(result.data);
                setError(null);
                showNotification(result.data.message, 'success');
            } else {
                setResponse(result.data);
                setError(null);
                showNotification(response.message, 'error');
            }
        } catch (err) {
            setError(err.data);
            showNotification(err.message, 'error');
        }
    }

    return (
    <>
        <div class="sui-header">
            <h1 class="sui-header-title">
             { __('Post Maintenance', txtDomain) }
            </h1>
      </div>
      <div className="sui-floating-notices">
                {notifications.map((notification, index) => (
                    <Notification
                        key={index}
                        message={notification.message}
                        status={notification.status}
                        onRemove={() => handleRemoveNotification(index)}
                    />
                ))}
            </div>
        <div className="sui-box">
            <div className="sui-box-header">
                <h2 className="sui-box-title">{ __('Run Post Maintenance', txtDomain) }</h2>
            </div>

            <div className="sui-box-body">
                <div className="sui-box-settings-row">
                    <TextControl
                        label="Post Types"
                        value={postTypes}
                        onChange={(value) => setPostTypes(value)}
                        placeHolder="post,page"
                    />
                </div>
            </div>

            <div className="sui-box-footer">
                <div className="sui-actions-right">
                    <Button
                        variant="primary"
                        onClick={ handleClick }
                    >
                        { __('Run', txtDomain) }
                    </Button>

                </div>
            </div>
        </div>
    </>
    );
}

if ( createRoot ) {
    createRoot( domElement ).render(<StrictMode><WPMUDEV_PluginTest/></StrictMode>);
} else {
    render( <StrictMode><WPMUDEV_PluginTest/></StrictMode>, domElement );
}
