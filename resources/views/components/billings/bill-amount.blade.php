@props(['billing'])

<div class="flex gap-2 justify-end text-xs font-semibold">
    {{ trans('billing.label.amount') }}
    @moneyIDR($billing->amount - $billing->discount + $billing->tax - $billing->payments()->where('reconciliation_status', 'partial')->whereNull('refund_status')->sum('amount'))
</div>

<div class="flex justify-end text-xs">
    <flux:subheading size="sm">
        {{ trans('billing.label.price') }}
        @moneyIDR($billing->customer_paket->price - $billing->customer_paket->discount)
    </flux:subheading>
</div>

@if ($billing->payments->where('reconciliation_status', 'partial')->count())
    <div class="flex justify-end text-xs">
            <flux:subheading size="sm" class="text-blue-700 dark:text-blue-300">
                {{ trans('billing.label.payment_amount') }}

                @moneyIDR($billing->payments->where('reconciliation_status', 'partial')->sum('amount'))

            </flux:subheading>
    </div>
@endif
@if ($billing->payments()->whereNotNull('refund_status')->count())
    <div class="flex justify-end text-xs">
            <flux:subheading size="sm" class="text-red-500 dark:text-red-300">
                {{ trans('billing.label.refund_amount') }}
                @moneyIDR($billing->payments()->where('reconciliation_status', 'partial')->whereNotNull('refund_status')->sum('amount'))
            </flux:subheading>
    </div>
@endif
@if ($billing->payments()->where('reconciliation_status', 'partial')->whereNull('refund_status')->count())
    <div class="flex justify-end text-xs">
            <flux:subheading size="sm" class="text-green-700 dark:text-green-300 font-semibold">
                {{ trans('billing.label.received_amount') }}

                @moneyIDR($billing->payments()->where('reconciliation_status', 'partial')->whereNull('refund_status')->sum('amount'))

            </flux:subheading>
    </div>
@endif


@if ($billing->special_discount > 0)
    <div class="flex gap-2 justify-end text-xs">
        <flux:subheading size="sm">
            {{ trans('billing.label.special-discount') }}
            @moneyIDR($billing->special_discount)
        </flux:subheading>
    </div>
@endif

@if ($billing->tax > 0)
    <div class="flex gap-2 justify-end text-xs">
        <flux:subheading size="sm">
            {{ trans('billing.label.tax') }}@moneyIDR($billing->tax)
        </flux:subheading>
    </div>
@endif
