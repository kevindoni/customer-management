@props(['billing'])

@if (!is_null($billing->customer_paket->paylater_date))
<flux:tooltip content="{{ trans('Paylater') }}">
    <flux:badge size="sm" color="{{\Carbon\Carbon::now()->gte($billing->customer_paket->paylater_date) ? 'red' : 'lime' }}">
        PL: {{ \Carbon\Carbon::parse($billing->customer_paket->paylater_date)->format('d-m-y') }}
    </flux:badge>
</flux:tooltip>
@else
<flux:tooltip content="{{ \Carbon\Carbon::parse($billing->due_date)->format('d F Y') }}">
    <flux:badge size="sm" color="{{$billing->status == 'overdue'? 'red' : 'lime' }}">
        {{ \Carbon\Carbon::parse($billing->due_date)->format('d-m-y') }}
    </flux:badge>
</flux:tooltip>
@endif
