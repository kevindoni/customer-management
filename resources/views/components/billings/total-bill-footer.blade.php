@props(['user', 'whatsappGatewayDisabled'])

@php
    $userCustomer = $user->user_customer;
    $paymentWithPartialStatus = $userCustomer->payments->where('reconciliation_status', 'partial');
    $totalCustomerPaid = $paymentWithPartialStatus->sum('amount');
    $totalCustomerTax = $paymentWithPartialStatus->sum('tax');
    $totalCustomerDiscount = $paymentWithPartialStatus->sum('discount');
    
    $totalCustomerRefunded = $paymentWithPartialStatus->sum('refunded_amount');
    $netCustomerPaid = $totalCustomerPaid - $totalCustomerRefunded;
    $totalCustomerBill = ($user->invoices->where('status', '!=', 'paid')->sum('amount') + $totalCustomerTax) - $totalCustomerDiscount - $netCustomerPaid;
@endphp


<tr>

    <td class="px-3 py-1 border-r bg-blue-100 border-gray-300 dark:border-gray-800 text-xs leading-5 text-gray-800 dark:text-gray-300"
        colspan="4">
        <dif class="justify-end flex">
            {!! trans('billing.total-bills') !!}:
        </dif>
    </td>
    <td
        class="gap-4 flex font-bold justify-end px-3 py-1 text-xs leading-5 text-gray-800 dark:text-gray-300">
        @moneyIDR($totalCustomerBill)

        @php
            $invoices = \App\Models\Billings\Invoice::where('status', '!=','unpaid')->latest()->get();
            $dayReminder = \Carbon\Carbon::parse($invoices->first()->due_date)->subDays(\App\Models\WhatsappGateway\WhatsappGatewayGeneral::first()->remaining_day);
        @endphp
        <flux:button.group>
            @if(!$whatsappGatewayDisabled && $invoices->count() && \Carbon\Carbon::now()->gte($dayReminder) && $user->user_address->phone)
                <flux:tooltip content="{{ __('billing.button.send-notification') }}">
                    <flux:button size="xs"
                        wire:click="sendNotification('{{ $user->username }}')"
                        style="cursor: pointer;" variant="success" icon="wa" />
                </flux:tooltip>
            @endif

            <flux:tooltip content="{!! trans('billing.button.export-invoices') !!}">
            <flux:button size="xs" wire:click="download_customer_invoices('{{ $user->username }}')"
                style="cursor: pointer;" icon="printer"/>
            </flux:tooltip>
            </flux:button.group>
    </td>
</tr>
