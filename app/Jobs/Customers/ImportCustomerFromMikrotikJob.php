<?php

namespace App\Jobs\Customers;

use App\Livewire\Actions\Customers\CustomerPaketAction;
use App\Livewire\Actions\Users\UserAction;
use App\Models\UserAddress;
use App\Services\Billings\BillingService;
use Illuminate\Support\Str;
use App\Models\Pakets\PppType;
use Illuminate\Support\Carbon;
use App\Models\Servers\Mikrotik;
use Illuminate\Support\Facades\DB;
use App\Models\Pakets\PaketProfile;
use Illuminate\Support\Facades\Log;
use App\Models\Customers\UserCustomer;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
//use App\Services\Mikrotiks\MikrotikPppUserSecretService;

class ImportCustomerFromMikrotikJob implements ShouldQueue
{
    use Queueable;

    private Mikrotik $mikrotik;

    private $userSecret;
    private $input;
    /**
     * Create a new job instance.
     */
    public function __construct(Mikrotik $mikrotik, $userSecret, array $input)
    {
        $this->mikrotik = $mikrotik;
        $this->userSecret = $userSecret;
        $this->input = $input;
    }

    /**
     * Execute the job.
     */
    public function handle(BillingService $billingService): void
    {

        DB::beginTransaction();
        try {
            $str = Str::random(6);
            $input = [
                'first_name' => $this->userSecret['name'],
                'email' => $this->userSecret['name'] . '_' . Str::slug($str, '_') . '@cm.com',
                'password' => $str,
                'disabled' => false,
            ];

            $user = (new UserAction())->addUser($input);
            $user->activation();
            $user->assignRole('customer');

            $userAddress = new UserAddress();
            $user->user_address()->save($userAddress);

            $userCustomer = new UserCustomer();
            $user->user_address()->save($userCustomer);

            $paketProfile = PaketProfile::where('profile_name', $this->userSecret['profile'])->first();
            $paket = $paketProfile->mikrotik_paket($this->mikrotik);

            $input = array_merge([
                'selectedPaket' => $paket->id,
                'selectedInternetService' => 'ppp',
                'activation_date' =>$this->input['activation_date'],
                'renewal_period' =>$this->input['renewal_period'],
                'usernamePpp' => $this->userSecret['name'],
                'passwordPpp' => $this->userSecret['password'],
                'secret_id' => $this->userSecret['.id'],
                'selectedPppService' =>  PppType::where('name', $this->userSecret['service'])->first()->id,
            ]);

            $customerPaket = (new CustomerPaketAction())->importCustomerPaket($user, $input);
            $customerPaket->activation($customerPaket->start_date);



            $invoice = $billingService->generateInvoice($customerPaket);
            //back expired date to normally
           // $invoice->forceFill(['due_date' => $customerPaket->expired_date])->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
