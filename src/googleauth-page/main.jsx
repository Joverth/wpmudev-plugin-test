import apiFetch from '@wordpress/api-fetch';
import { Button, TextControl } from '@wordpress/components';
import { StrictMode, createInterpolateElement, createRoot, render, useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import Notification from '../notifications/Notification';


import "../scss/style.scss";


const domElement = document.getElementById( window.wpmudevPluginTest.dom_element_id );

const WPMUDEV_PluginTest = () => {
    const [response, setResponse] = useState(null);
    const [error, setError] = useState(null);
    const [notifications, setNotifications] = useState([]);
    const [clientId, setClientId] = useState('');
    const [clientSecret, setClientSecret] = useState('');
    const handleRemoveNotification = (index) => {
        setNotifications(notifications.filter((_, i) => i !== index));
    };

    const showNotification = (message, status) => {
        setNotifications([...notifications, { message, status }]);
    };
    const txtDomain = 'wpmudev-plugin-test';
    const fetchDataOnMount = async () => {
        console.log(wpmudevPluginTest);
        try {
            const result = await apiFetch({
                path: wpmudevPluginTest.restEndpointSave,
                method: 'GET',
            });

            if (result.success) {
                setClientId(result.data.client_id);
                setClientSecret(result.data.client_secret);
            } else {
                showNotification(result.data.message || 'Failed to fetch initial data.', 'error');
            }
            console.log(result);
        } catch (err) {
            setError(err.message);
            showNotification(err.message || 'An unknown error occurred while fetching initial data.', 'error');
        }
    };
    useEffect(() => {
        fetchDataOnMount();
    }, []); // Empty dependency array to run only once on mount
    const handleClick = async () => {
        console.log(wpmudevPluginTest);
        try {
            const result = await apiFetch({ 
                path: wpmudevPluginTest.restEndpointSave,
                method: 'POST',
                data: { clientId, clientSecret },
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
            { __('Settings', txtDomain) }
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
                <h2 className="sui-box-title">{ __('Set Google credentials', txtDomain) }</h2>
            </div>

            <div className="sui-box-body">
                <div className="sui-box-settings-row">
                    <TextControl
                        help={createInterpolateElement(
                            'You can get Client ID from <a>here</a>.',
                            {
                              a: <a href="https://developers.google.com/identity/gsi/web/guides/get-google-api-clientid"/>,
                            }
                          )}
                        label="Client ID"
                        value={clientId}
                        onChange={(value) => setClientId(value)}
                    />
                </div>

                <div className="sui-box-settings-row">
                    <TextControl
                        help={createInterpolateElement(
                            'You can get Client Secret from <a>here</a>.',
                            {
                              a: <a href="https://developers.google.com/identity/gsi/web/guides/get-google-api-clientid"/>,
                            }
                          )}
                        type="password"
                        label="Client Secret"
                        value={clientSecret}
                        onChange={(value) => setClientSecret(value)}
                    />
                </div>

                <div className="sui-box-settings-row">
                    <span>{ __('Please use this url ', txtDomain) }<em>{window.wpmudevPluginTest.returnUrl}</em> { __("in your Google API's ", txtDomain) }<strong>{ __('Authorized redirect URIs ', txtDomain) }</strong> { __('field', txtDomain) }</span>
                </div>
            </div>

            <div className="sui-box-footer">
                <div className="sui-actions-right">
                    <Button
                        variant="primary"
                        onClick={ handleClick }
                    >
                        { __('Save ', txtDomain) }
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
