<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sidebar whatsapp gateway language
    |--------------------------------------------------------------------------

    */
    'number-notfound' => 'Number Not Found',
    'notification-message-notfound' => 'Notification Message Not Found',
    'heading' => [
        'add-device' => 'Add Device',
        'edit-device' => 'Edit Device',
        'subtitle-add-device' => 'Add device number to start using whatsapp gateway.',
        'subtitle-edit-device' => 'Only edit description',
        'upgrade-subscription' => 'Upgrade Subscription',
        'add-subscription' => 'Add Subscription',
        'subtitle-edit-subscription' => 'Warning! If you upgrade or edit subscription, current subscription will be remove.',
        'subtitle-add-subscription' => 'Add subscription to be able to start whatsapp gateway.'

    ],
    'menu' => [
        'general' => 'General',
        'number' => 'Number',
        'notification-message' => 'Notification',
        'boot-message' => 'Boot Message',
        'invoices' => 'Invoices',
        'message-histories' => 'Message Histories'

    ],
    'button' => [
        'register' => 'Register',
        'add-device' => 'Add Device',
        'cancel' => 'Cancel',
        'update' => 'Update',
        'delete' => 'Delete',
        'login' => 'Login',
        'add' => 'Add',
    ],
    'label' => [
        'username' => 'Gateway Username',
        'email' => 'Email',
        'apikey' => 'API Key',
        'number' => 'Number',
        'description' => 'Description',
        'url-callback' => 'URL Callback',
        'subscription-status' => 'Subscription Status',
        'subscription' => 'Subscription',
        'subscription-expired' => 'Subscription Expired',
        'whatsapp-number-notification' => 'Notification Number',
        'whatsapp-number-boot' => 'Boot Number',
        'remaining-day' => 'Remaining Day',
        'schedule-time' => 'Schedule Time',
        'day' => 'Day',
        'enable' => 'Enable',
        'whatsapp-gateway-disable' => 'Whatsapp gateway disable',
        'whatsapp-gateway-enable' => 'Whatsapp gateway enable',
        'notif-admin' => 'Send Notification to Admin',
        'yes' => 'Yes',
        'no' => 'No',
        'renewal-period' => 'Renewal Period',
        'product' => 'Product',
        'payment-method' => 'Payment Method',
        'device-name' => 'Device Name',
        'name' => 'Name',
        'parent' => 'Parent Message',
        'command-number' => 'Command Number',
        'action-message' => 'Action Message',
        'hidden-message' => 'Hidden Message',
        'end-message' => 'End Message',
        'message' => 'Message',



    ],

    'table' => [
        'no' => 'No',
        'name' => 'Name',
        'number' => 'Number',
        'incoming-message' => 'Incoming Message',
        'reply-message' => 'Reply Message',
        'description'  => 'Description',
        'status' => 'Status',
        'enable' => 'Enable',
        'hide' => 'Hide',
        'action' => 'Action',
        'parent-message' => 'Parent',
        'command-number' => 'Cmd',
        'message' => 'Message',
        'action-message' => 'Act. Msg'

    ],
    'invoice' => [
        'table' => [
            'product' => 'Product',
            'subscription-plan' => 'Subscription Plan',
            'total-amount' => 'Amount',
            'expired-date' => "Expired"
        ],
    ],
    'helper' => [
        'password' => 'Input your password CM',
        'number' => 'Ex: 6285xxxxxxxx',
        'device-name' => 'Your device name',
        'description' => 'Describe your device number',
        'url-callback' => 'Address server CM',
        'schedule-time' => 'Default 23',
        'remaining-day' => 'Default 7',
    ],
    'ph' => [
        'select-wa-number' => 'Select Number',
        'select-renewal-period' => 'Select renewal period',
        'select-product' => 'Select Product',
        'monthly' => 'Monthly',
        'bimonthly' => 'Bimonthly',
        'quarterly' => 'Quarterly',
        'semi-annually' => 'Semi Annually',
        'annually' => 'Annually',
        'select-bank' => 'Select Bank'
    ],

    'wa-message' => [
        'menu' => ':no. :menu%0a',
        //'notif-lessthan-deadline-payment'      => 'Periode :periode bill :bill deadline :deadline',
        'notif-admin-deadline-payment-customer-paket'      => ':name :address tagihan :bill jatuh tempo :deadline.',
        'notif-admin-deadline-paylater-customer-paket'      => ':name :address tagihan :bill jatuh tempo paylater :deadline.',
        'notif-lessthan-deadline-payment'      => 'Periode :periode tagihan :bill batas bayar :deadline (:invoice_number).',
        'notif-lessthan-deadline-payment-with-pay-later' => 'Periode :periode tagihan :bill batas bayar :deadline dengan jatuh tempo Paylater :paylater (:invoice_number).',
        'enable-message' => 'Are you sure enable [:name] message?',
        'error-enable-message' => 'Enable message [:name] failed. Please try again!',
        'disable-message' => 'Are you sure disable [:name] message?',
        'error-disable-message' => 'Disable message [:name] failed. Please try again!',
        'delete-message' => 'Are you sure delete :name message?',
        'detail-paket' => ':no. :paketname Rp. :bill. Batas waktu pembayaran tanggal :deadline.%0a',
        'info-detail-billing' => ':no. Periode :start_periode s/d :end_periode Rp. :bill.%0a',
        'info-wifi' => ':no. :ssid_id *:ssid_name*, Lokasi :installation_address %0a%0a   Perangkat yang terhubung saat ini:%0a:device_connected%0a',
        'device-connected' => ':no. :host_name%0a',
        'info-wifi-unconfigure' => ':no. Router belum disetting.',
        'no-device-connected' => '   Tidak ada perangkat yang terhubung%0a%0a',
        'today' => 'Hari ini',
        'tomorrow' => 'Besok',
        'days-again' => ':day hari lagi',
        'false-user-reply-message' => 'The answer you give is not appropriate!%0a%0a%last_message%',
        'reply-user-action-message' => 'Please wait, your request :action_name to :value_user will be processed immediately.',
        'action' => [
            'change-ssid' => 'Change SSID',
            'change-password-wifi' => 'Change password wifi',
            'info-paket-bill' => 'Berikut info tagihan anda.%0a%bills% %0aJumlah tagihan: %total-bills%%0a%0aAnda dapat melakukan pembayaran melalui:%0a %account-banks% %0aTerima kasih telah mempercayakan kebutuhan internet anda kepada kami. '
        ],
        'status' => [
            'nonactive' => 'Non Active',
            'active' => 'Active'
        ],
        'admin-status' => [
            'activated' => 'Activated',
            'disabled' => 'Disabled',
            'enabled' => 'Enabled'
        ]


    ],



];
