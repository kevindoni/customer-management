<table>
    <thead>
        <tr>
            <th width="6" height="22" align="center" valign="center">
                {{ trans('billing.table.no') }}
            </th>
            <th width="18" align="center" valign="center">
                {{ trans('billing.table.customer-name') }}
            </th>
            <th width="30" align="center" valign="center">
                {{ trans('billing.table.customer-address') }}
            </th>
            <th width="8" align="center" valign="center">
                {{ trans('billing.table.periode') }}
            </th>
            <th width="15" align="center" valign="center">
                {{ trans('billing.table.paket-name') }}
            </th>
            <th width="9" align="center" valign="center">
                {{ trans('billing.table.bill') }}
            </th>
            <th width="11" align="center" valign="center">
                {{ trans('billing.table.deadline') }}
            </th>
            <th width="13" align="center" valign="center">
                {{ trans('billing.table.payment-time') }}
            </th>
            <th width="13" align="center" valign="center">
                {{ trans('billing.table.teller') }}
            </th>
            <th width="8" align="center" valign="center">
                {{ trans('billing.table.description') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($billings as $key => $billing)
            <tr>

                <td align="center">{{ $loop->index + 1 }}</td>
                <td>{{ $billing->customer_paket->user->full_name }}</td>
                <td>{{ $billing->customer_paket->billing_address }}</td>
                <td>{{ \Carbon\Carbon::parse($billing->billing_periode)->format('m-Y') }}</td>
                <td>{{ $billing->customer_paket->paket->name }}</td>
                <td>{{ $billing->bill }}</td>
                <td>{{ \Carbon\Carbon::parse($billing->deadline)->format('d-m-Y') }}</td>
                <td>
                    @if (is_null($billing->payment_time))
                        {{ trans('billing.status.unpayment') }}
                    @else
                        {{ \Carbon\Carbon::parse($billing->payment_time)->format('d-m-Y, H:i') }}
                    @endif

                </td>
                <td>{{ $billing->teller_name }}</td>
                <td></td>

            </tr>
        @endforeach
    </tbody>
</table>
