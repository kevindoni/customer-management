<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

class WhatsappGateway extends Controller
{
    public const DEVICES = 'api/devices';
    public const INIT = 'api/initialize';
    public const USER = 'api/users';
    public const GENERATE_QRCODE = 'api/generate-qrcode';
    public const NOTIFICATION_MESSAGE = 'api/notification-messages';
    public const SUBSCRIPTION = 'api/subscriptions';
    //public const EDIT_SUBSCRIPTION = 'api/edit-subscription';
    public const SUBSCRIPTION_PLAN = 'api/subscription-plans';
    public const PAYMENT_METHODE = 'api/order/payment-method';
    public const ORDER = 'api/orders';
    public const INVOICE = 'api/invoices';

    public const SEND_MESSAGE = 'api/send-message';
    public const MESSAGE_HISTORIES = 'api/message-histories';
}
