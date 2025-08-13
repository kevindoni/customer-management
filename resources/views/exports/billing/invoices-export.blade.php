<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ trans('billing.label.invoice-id') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css" media="screen">
        html {

            line-height: 1.15;
            margin: 0;
        }

        body {
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4,
        .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4,
        .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table.table-items .tr-items {
            border-bottom: 1px solid #dee2e6;
        }

        .table.table-items .tr-head {
            border-bottom: 1px solid #a3a3a3;
        }

        .table.table-items td {
            border-bottom: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }


        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        table,
        th,
        tr,
        td,
        p,
        div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .cool-gray {
            color: #6B7280;
        }
    </style>
</head>

@foreach ($users as $user)

    <body>
        {{-- Header --}}
        <img src="{{ public_path('images/receipt_logo.png') }}" alt="GriyaNet" width="200" />

        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase">
                            <strong>{{ trans('billing.label.invoice') }}</strong>
                        </h4>
                    </td>
                    <td class="border-0 pl-0">
                        <h4 class="text-uppercase cool-gray">
                            <strong></strong>
                        </h4>
                        <p>{{ __('invoice.serial') }}
                            <strong>{{ $user->invoices()->where('invoices.status', '!=', 'paid')->first()->invoice_number }}</strong>
                        </p>
                        <p>{{ __('invoice.date') }}:
                            <strong>{{ \Carbon\Carbon::parse($user->invoices()->where('invoices.status', '!=', 'paid')->first()->created_at)->format('d F Y') }}</strong>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="border-0 pl-0 party-header" width="48.5%">
                        {{ __('invoice.seller') }}
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0 party-header">
                        {{ __('invoice.buyer') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        @if ($system->title)
                            <p class="seller-name">
                                <strong>{{ $system->title }}</strong>
                            </p>
                        @endif

                        @if ($system->address)
                            <p class="seller-address">
                                {{ __('invoice.address') }}: {{ $system->address }}
                            </p>
                        @endif

                        <p class="seller-code">
                            {{ __('invoice.invoice-number') }}: 12345
                        </p>

                        <p class="seller-vat">
                            {{ __('invoice.vat') }}: -
                        </p>

                        @if ($system->phone)
                            <p class="seller-phone">
                                {{ __('invoice.phone') }}: {{ $system->phone }}
                            </p>
                        @endif


                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">


                        <p class="buyer-name">
                            <strong>{{ $user->full_name }}</strong>
                        </p>



                        @if ($user->user_address)
                            <p class="buyer-address">
                                {{ __('invoice.address') }}:
                                {{ $user->user_address->address }}
                            </p>

                            <p class="buyer-phone">
                                {{ __('invoice.phone') }}:
                                {{ $user->user_address->phone }}
                            </p>
                        @endif

                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table table-items">
            <thead>
                <tr class="tr-head">
                    <th scope="col" class="border-0 pl-0 text-center">{{ __('billing.table.no') }}</th>
                    <th scope="col" class="border-0 pl-0">{{ __('invoice.description') }}</th>
                    <th scope="col" class="text-right border-0">{{ __('invoice.price') }}</th>
                    <th scope="col" class="text-right border-0">{{ __('invoice.discount') }}</th>
                    <th scope="col" class="text-right border-0">{{ __('invoice.tax') }}</th>
                    <th scope="col" class="text-right border-0">{{ __('invoice.amount-received') }}</th>

                    <th scope="col" class="text-right border-0 pr-0">{{ __('invoice.sub_total') }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                @php
                    $invoices = $user->invoices()->where('invoices.status', '!=', 'paid')->get();
                    $grandTotal = 0;
                @endphp
                @foreach ($invoices as $invoice)
                @php
                    $totalPaid = $invoice->payments->sum('amount');
                  //  $totalTax = $invoice->payments->sum('tax');
                    $totalRefunded = $invoice->payments->sum('refunded_amount');
                  //  $totalDiscount = $invoice->payments->sum('discount');
                    $netPaid = $totalPaid - $totalRefunded;
                    $totalBill = ($invoice->amount - $invoice->discount) + $invoice->tax - $netPaid;
                    $grandTotal += $totalBill;
                @endphp
                    @foreach ($invoice->invoice_items as $invoice_item)
                        <tr class="tr-items">
                            <td class="pl-0 text-center">
                                {{ $loop->index + 1 }}.
                            </td>
                            <td class="pl-0">
                                {{ $invoice_item->item }}
                            </td>
                            <td class="text-right">
                                @moneyIDR($invoice_item->price)
                            </td>
                            <td class="text-right">
                                @moneyIDR($invoice_item->discount)
                            </td>
                            <td class="text-right">
                                @moneyIDR($invoice->tax)
                            </td>
                            <td class="text-right">
                                @moneyIDR($netPaid)
                            </td>
                            <td class="text-right pr-0">
                                @moneyIDR($totalBill)
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                {{-- Summary --}}
                <tr>
                    <td colspan="5" class="border-0"></td>
                    <td class="text-right pl-0">{{ __('invoice.total_amount') }}:</td>
                    <td class="text-right pr-0 total-amount">
                        @moneyIDR($grandTotal)
                    </td>
                </tr>
            </tbody>
        </table>


        <p>
            {{ __('invoice.notes') }}:
        </p>

        <p>
            {{ __('invoice.amount_in_words') }}:
        </p>
        <p>
            {{ __('invoice.pay_until') }}:
            {{ \Carbon\Carbon::parse($invoices->last()->due_date)->format('d F Y') }}
        </p>

        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "{{ __('invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
@endforeach

</html>
