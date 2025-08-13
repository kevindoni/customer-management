<?php

namespace App\Livewire\Customer\Subscription;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Services\Mikrotiks\MikrotikPppService;

class SubscriptionManagement extends Component
{
    use WithPagination;


    public function render(MikrotikPppService $mikrotikPppService)
    {
        $customerPakets = Auth::user()->customer_pakets()
       ->paginate(5);

       foreach($customerPakets as $customerPaket){
           if ($customerPaket->customer_ppp_paket){
            $statusActive = $mikrotikPppService->getStatusActiveSecret($customerPaket->mikrotik, $customerPaket->customer_ppp_paket->username);
            $customerPaket->forceFill(['online' => $statusActive])->save();

           }
       }
        return view('livewire.customer.subscription.subscription-management', compact('customerPakets'));
    }
}
