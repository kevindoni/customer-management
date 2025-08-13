@props(['billing'])
<div class="flex justify-center text-xs">
    @if ($billing->customer_paket->paylater_date)
        {{ trans('billing.status.pay-later') }}
    @else
        @if ($billing->status === 'pending')
            {{ trans('billing.status.unpayment') }}
        @elseif ($billing->status === 'partially_paid')
            {{ trans('billing.button.partially-paid') }}
        @elseif ($billing->status == 'paid')
            <flux:badge color="lime" size="sm"
                title="{{ \Carbon\Carbon::parse($billing->paid_at)->format('d F Y, h:i') }}">
                {{ \Carbon\Carbon::parse($billing->paid_at)->format('d-m-y') }}
            </flux:badge>
        @endif
    @endif
</div>

@if ($billing->last_reminder_date != null)
    <div class="flex justify-center text-xs">
        <flux:subheading size="sm" color="lime">
            {{ trans('billing.label.last-reminder') }}
            {{ \Carbon\Carbon::parse($billing->last_reminder_date)->format('d-m-y H:i:s') }}
        </flux:subheading>
    </div>
@endif
