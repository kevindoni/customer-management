<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Settings\Address;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Settings\EditAccount;
use App\Livewire\Settings\DeleteAccount;


Route::get('/', function () {

    if (Auth::user()) {
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('technitian') || Auth::user()->hasRole('teller')) {
            return redirect()->route('dashboard');
        } else if (Auth::user()->hasRole('customer')) {
            return redirect()->route('customer.dashboard');
        }
    } else {
        if (env('APP_INSTALLED') === false) {
            return redirect()->route('install');
        }
        return view('welcome');
    }
})->name('home');

Route::get('install', App\Livewire\Install::class)->name('install');

Route::get('pages/term-of-service', App\Livewire\Pages\TermOfService::class)->name('tos');
Route::get('pages/privacy', App\Livewire\Pages\Privacy::class)->name('privacy');
Route::get('pages/contact', App\Livewire\Pages\Contact::class)->name('contact');

Route::webhooks('whatsapp/webhook', 'whatsapp-gateway');
Route::webhooks('mikrotik/webhook', 'mikrotik');
//Route::webhooks('tripay/response', 'tripay');
Route::post('tripay/response', App\Http\Controllers\TripayController::class)->name('tripay');

//Route::middleware(['auth', 'role:admin|technitian'])->group(function () {
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/address', Address::class)->name('settings.address');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/account', EditAccount::class)->name('settings.account');
});

Route::middleware(['auth', 'role:admin|technitian'])->group(function () {
    Route::get('update', App\Livewire\Update::class)->name('update');
});

Route::middleware(['auth', 'role:admin|technitian', 'appIsntalled', 'checkVersion'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('settings/delete-account', DeleteAccount::class)->name('settings.deleteAccount');

    Route::get('customers', App\Livewire\Admin\Customers\CustomersManagement::class)->name('customers.management');
    Route::get('deleted-customers', App\Livewire\Admin\Customers\DeletedCustomersManagement::class)->name('deletedCustomers.management');
    Route::get('customers-paket', App\Livewire\Admin\Customers\CustomerPaketManagement::class)->name('customers.paket.management');
    Route::get('deleted-customer-pakets', App\Livewire\Admin\Customers\DeletedCustomerPaketManagement::class)->name('deletedCustomers.paket.management');
    Route::get('/customer/{user}/show', App\Livewire\Admin\Customers\ShowCustomer::class)->name('customer.show');
    Route::get('/customer/{user}/show-billing', App\Livewire\Admin\Customers\ShowBillingCustomer::class)->name('customer.billing');

    Route::get('pakets', App\Livewire\Admin\Pakets\PaketList::class)->name('pakets.management');
    Route::get('deleted-pakets', App\Livewire\Admin\Pakets\DeletedPaket::class)->name('deletedPakets.management');
    Route::get('profile', [App\Livewire\Admin\Pakets\ProfileList::class, '__invoke'])->name('pakets.profile.management');

    Route::get('billings', App\Livewire\Admin\Billings\BillingManagement::class)->name('billings.management');
    Route::get('billings/payment-management', App\Livewire\Admin\Billings\Payments\PaymentManagement::class)->name('billings.management.payments');

    Route::get('managements/mikrotiks', App\Livewire\Admin\Mikrotiks\MikrotiksManagement::class)->name('managements.mikrotiks');
    Route::get('managements/deleted-mikrotiks', App\Livewire\Admin\Mikrotiks\DeletedMikrotiksManagement::class)->name('deletedMikrotiks.management');

    Route::get('managements/users', App\Livewire\Admin\Users\UsersManagement::class)->name('managements.users');
    Route::get('managements/roles', App\Livewire\Admin\Roles\RolesManagement::class)->name('managements.roles');

    //View Mikrotik Route
    Route::get('managements/whatsapp-gateway', App\Livewire\Admin\WhatsappGateway\WhatsappGatewayManagement::class)->name('managements.whatsapp_gateway');
    Route::get('managements/whatsapp/number', App\Livewire\Admin\WhatsappGateway\Device::class)->name('managements.whatsapp.number');
    Route::get('managements/whatsapp/message-histories', App\Livewire\Admin\WhatsappGateway\MessageHistories::class)->name('managements.whatsapp.messageHistories');
    Route::get('managements/whatsapp/notification-message', App\Livewire\Admin\WhatsappGateway\NotificationMessageController::class)->name('managements.whatsapp.notificationMessage');
    Route::get('managements/whatsapp/boot-message', App\Livewire\Admin\WhatsappGateway\BootMessageController::class)->name('managements.whatsapp.bootMessage');
    Route::get('managements/whatsapp/invoices', App\Livewire\Admin\WhatsappGateway\InvoiceManagement::class)->name('managements.whatsapp.invoice');

    Route::get('managements/general', App\Livewire\Admin\Settings\General::class)->name('managements.websystem');

    Route::get('managements/autoisolir', App\Livewire\Admin\Settings\AutoIsolir::class)->name('managements.autoisolirs');
    Route::get('managements/wan-monitoring', App\Livewire\Admin\Settings\WanMonitoring::class)->name('managements.wanMonitorings');
    Route::get('managements/webhook-monitoring', App\Livewire\Admin\Settings\WebhookMonitoring::class)->name('managements.webhookMonitorings');

    Route::name('managements.mikrotik.')->prefix('mikrotik')->group(function () {
        Route::get('{mikrotik}', App\Livewire\Admin\Mikrotiks\View\MikrotikDashboard::class)->name('dashboard');
        Route::get('{mikrotik}/profiles', App\Livewire\Admin\Mikrotiks\View\MikrotikProfiles::class)->name('profiles');
        Route::get('{mikrotik}/secrets', App\Livewire\Admin\Mikrotiks\View\MikrotikUserSecrets::class)->name('secrets');
        Route::get('{mikrotik}/pakets', App\Livewire\Admin\Mikrotiks\View\MikrotikPakets::class)->name('pakets');
        Route::get('{mikrotik}/customer', App\Livewire\Admin\Mikrotiks\View\MikrotikCustomer::class)->name('customers');
        Route::get('{mikrotik}/wan-monitoring', App\Livewire\Admin\Mikrotiks\View\WanMonitoring::class)->name('wanmonitoring');
        Route::get('{mikrotik}/user-monitoring', App\Livewire\Admin\Mikrotiks\View\MikrotikUserMonitoring::class)->name('usermonitoring');
    });
    Route::get('hotspots', App\Livewire\Admin\Hotspots\HotspotManagement::class)->name('managements.hotspots');
    Route::get('managements/payment-gateway', App\Livewire\Admin\Settings\PaymentGatewayManagement::class)->name('managements.paymentgateways');

    Route::get('managements/banks', App\Livewire\Admin\Settings\Banks\BankManagement::class)->name('managements.banks');

    Route::name('helps.')->prefix('helps')->group(function () {
        Route::get('home', App\Livewire\Admin\Help\Index::class)->name('home');
        Route::get('general-setting', App\Livewire\Admin\Help\WebSystem\GeneralSetting::class)->name('generalSetting');
        Route::get('development', App\Livewire\Admin\Help\Development::class)->name('development');
        Route::name('servers.')->prefix('servers')->group(function () {
            Route::get('mikrotik', App\Livewire\Admin\Help\Servers\Mikrotik::class)->name('mikrotik');
            Route::get('add-mikrotik', App\Livewire\Admin\Help\Servers\AddMikrotik::class)->name('addMikrotik');
            Route::get('edit-mikrotik', App\Livewire\Admin\Help\Servers\EditMikrotik::class)->name('editMikrotik');
            Route::get('delete-mikrotik', App\Livewire\Admin\Help\Servers\DeleteMikrotik::class)->name('deleteMikrotik');
            Route::get('import-customer', App\Livewire\Admin\Help\Servers\ImportCustomer::class)->name('importCustomer');
            Route::get('import-profile', App\Livewire\Admin\Help\Servers\ImportProfile::class)->name('importProfile');
        });
        Route::name('customers.')->prefix('customers')->group(function () {
            Route::get('customer', App\Livewire\Admin\Help\Customers\Customer::class)->name('customer');
            Route::get('add', App\Livewire\Admin\Help\Customers\AddCustomer::class)->name('add');
            Route::get('delete', App\Livewire\Admin\Help\Customers\DeleteCustomer::class)->name('delete');
            Route::name('pakets.')->prefix('pakets')->group(function () {
                Route::get('paket', App\Livewire\Admin\Help\Customers\CustomerPakets\Paket::class)->name('paket');
                Route::get('add', App\Livewire\Admin\Help\Customers\CustomerPakets\AddCustomerPaket::class)->name('add');
                Route::get('edit', App\Livewire\Admin\Help\Customers\CustomerPakets\EditCustomerPaket::class)->name('edit');
                Route::get('delete', App\Livewire\Admin\Help\Customers\CustomerPakets\DeleteCustomerPaket::class)->name('delete');
            });
        });

        Route::name('whatsapps.')->prefix('whatsapp-gateway')->group(function () {
            Route::get('register', App\Livewire\Admin\Help\WhatsappGateway\Register::class)->name('register');
            Route::get('general-config', App\Livewire\Admin\Help\WhatsappGateway\GeneralConfiguration::class)->name('generalConfig');
            Route::get('add-device', App\Livewire\Admin\Help\WhatsappGateway\AddDevice::class)->name('addDevice');
            Route::get('delete-device', App\Livewire\Admin\Help\WhatsappGateway\DeleteDevice::class)->name('deleteDevice');
            Route::get('scan-device', App\Livewire\Admin\Help\WhatsappGateway\ScanDevice::class)->name('scanDevice');
            Route::get('notification-message', App\Livewire\Admin\Help\WhatsappGateway\NotificationMessage::class)->name('notificationMessage');
            Route::get('edit-notification-message', App\Livewire\Admin\Help\WhatsappGateway\EditNotificationMessage::class)->name('editNotificationMessage');
            Route::get('payment-notification-message', App\Livewire\Admin\Help\WhatsappGateway\PaymentNotificationMessage::class)->name('paymentNotificationMessage');
            Route::get('unpayment-notification-message', App\Livewire\Admin\Help\WhatsappGateway\UnpaymentNotificationMessage::class)->name('unpaymentNotificationMessage');
            Route::get('paylater-notification-message', App\Livewire\Admin\Help\WhatsappGateway\PaylaterNotificationMessage::class)->name('paylaterNotificationMessage');
            Route::get('warning-bill-notification-message', App\Livewire\Admin\Help\WhatsappGateway\WarningBillNotificationMessage::class)->name('warningBillNotificationMessage');
            Route::get('deadline-bill-notification-message', App\Livewire\Admin\Help\WhatsappGateway\DeadlineBillNotificationMessage::class)->name('deadlineBillNotificationMessage');
            Route::get('isolir-notification-message', App\Livewire\Admin\Help\WhatsappGateway\IsolirNotificationMessage::class)->name('isolirNotificationMessage');
            Route::get('keyword-message', App\Livewire\Admin\Help\WhatsappGateway\KeywordMessage::class)->name('keywordMessage');
            Route::get('payment', App\Livewire\Admin\Help\WhatsappGateway\Payment::class)->name('payment');
            Route::get('subscription', App\Livewire\Admin\Help\WhatsappGateway\Subscription::class)->name('subscription');
        });

        Route::name('paymentgateways.')->prefix('payment-gateway')->group(function () {
            Route::get('midtrans', App\Livewire\Admin\Help\PaymentGateway\Midtrans::class)->name('midtrans');
            Route::get('tripay', App\Livewire\Admin\Help\PaymentGateway\Tripay::class)->name('tripay');
        });
    });
});

Route::middleware(['auth', 'role:customer'])->name('customer.')->prefix('customer')->group(function () {
    Route::get('dashboard', App\Livewire\Customer\Dashboard::class)->name('dashboard');
    Route::get('payment-management', App\Livewire\Customer\Billing\PaymentManagement::class)->name('paymentmanagement');

    Route::get('subscription-management', App\Livewire\Customer\Subscription\SubscriptionManagement::class)->name('subscriptionmanagement');
});


//Customer Route
//Route::middleware(['auth'])->name('customer.')->prefix('customer')->group(function () {});

require __DIR__ . '/auth.php';
