<?php

return [
    'title' => [
        'mikrotik-monitoring' => 'Mikrotik Monitoring'
    ],
    'version' => 'Version: ',
    'new-version' => 'New Version: ',
    'header' => [
        'index' => 'Settings',
        'edit' => 'Change Setting',
        'customer' => 'Configuration Customer'
    ],
    'tabs' => [
        'general' => 'General',
        'auto-isolir' => 'Auto Isolir',

    ],
    'label' => [
        'web-title' => 'Web Title:',
        'company' => 'Company:',
        'app-url' => 'App URL:',
        'name-profile-isolir' => 'Name of Profile Isolir:',
        'comment-payment' => 'Comment Payment:',
        'comment-unpayment' => 'Comment Unpayment:',
        'due-date' => 'Due Date:',
        'enable-tax-button' => 'Enable Tax?',
        'tax-rate' => 'Tax Rate',
        'email' => 'Email',
        'address' => 'Address',
        'city' => 'City',
        'postal_code' => 'Postal Code',
        'phone' => 'Phone',
        'customer_code' => 'Customer Code',
        'long_customer_code' => 'Long Customer Code',
        'app-env' => 'App Env',
        'app-debug' => 'App Debug',
        'session-driver' => 'Session Driver',
        'cache-store' => 'Cache Store',
        'queue-connection' => 'Queue Connection',
        'app-timezone' => 'App Time Zone',
        'isolir-driver' => 'Isolir Driver',
        'subscription-type' => 'Subscription Type',
        'different-days-create-invoice' => 'Invoice Diff.'
    ],
    'info' => [
        'comment-payment' => 'This is used to mark user secrets who have paid on Mikrotik.',
        'comment-unpayment' => 'This is used to mark user secrets who have unpaid on Mikrotik.',
        'profile-isolir' => 'This is used to set a customer isolation profile.',
        'due-date' => 'Determine the payment date.',
        'every-due-date' => 'Every date :due_date',
        'tax-rate' => 'Please write without %, default in Indonesia 11.'

    ],
    'placeholder' => [
        'web-title' => 'Web Title',
        'app-url' => 'Ex: http://appdomain.co',
        'tax-rate' => 'Ex: 11',
        'email' => 'Email',
        'address' => 'Address',
        'city' => 'City',
        'postal_code' => 'Postal Code',
        'phone' => 'Phone',
        'customer_code' => 'Customer Code',
        'long_customer_code' => 'Long Customer Code',
        'select-server' => 'Select Server',
        'select-interface' => 'Select interface',
        'different-days-create-invoice' => 'Different days to create invoice'
    ],
    'button' => [
        'add' => 'Add',
        'edit' => 'Edit',
        'update' => 'Update',
        'update-new-version' => 'Update to Version :version',
        'cancel' => 'Cancel',
        'link-storage' => 'Link Storage',
        'optimize' => 'Optimize'
    ],

    'midtran' => [
        'header-edit' => 'Configuration Midtrans',
        'name' => 'Name',
        'merchat_id' => 'Merchant Id',
        'server_key' => 'Production Server Key',
        'client_key' => 'Production Client Key',
        'sandbox_server_key' => 'Sandbox Server Key',
        'sandbox_client_key' => 'Sandbox Client Key',
        'production-mode' => 'Production Mode',
        'disable' => 'Disable',
        'placeholder' => [
            'name' => 'Name',
            'merchant_id' => 'Gxxxxxxxxxx',
            'server_key' => 'Mid-server-xxxxxxxxxxx',
            'client_key' => 'Mid-client-xxxxxxxxxxx',
            'sandbox_server_key' => 'SB-Mid-server-xxxxxxxxxxx',
            'sandbox_client_key' => 'SB-Mid-client-xxxxxxxxxxx',
        ],
    ],
    'alert' => [
        'updated' => 'Updated',
        'updated-message-successfully' => 'Updated web system successfully.',
        'optimize-success' => 'Optimize successfully.',
        'link-storage-success' => 'Link storage successfully.',
        'success' => 'Success',
        'tripay-updated-successfully' => 'TriPay payment gateway updated successfully.',
        'midtrans-updated-successfully' => 'Midtrans ayment gateway updated successfully.'
    ],
    'acs' => [
        'configuration' => 'Configuration Geni ACS Server',
        'label' => [
            'host' => 'Host/URL',
            'username' => "Username",
            'password' => 'Password',
            'port' => 'Port'
        ],
    ],

    'mikrotik-monitoring' => [
        'table' => [
            'server' => 'Server',
            'interface' => 'Interface',
            'interface_type' => 'Type',
            'min-upload' => 'Min. Up',
            'max-upload' => 'Max. Up',
            'min-download' => 'Min. Down',
            'max-download' => 'Max. Down',
            'status' => 'Status',
            'action' => 'Action',
        ],

        'helper' => [
            'interface' => ' Select interface',
            'server' => 'Select server',
            'min-upload' => 'Min. Upload must be numeric',
            'max-upload' => 'Max. Upload must be numeric',
            'min-download' => 'Min. Download must be numeric',
            'max-download' => 'Max. Download must be numeric',
        ],

        'label' => [
            'edit-wan-monitoring' => 'Edit WAN Monitoring :mikrotik',
            'add-wan-monitoring' => 'Add WAN Monitoring',
            'server' => 'Server',
            'interface' => 'Interface',
            'upload' => 'Upload (Mbps)',
            'download' => 'Download (Mbps)',
            'min' => 'Min.',
            'max' => 'Max.',
        ],
    ]


];
