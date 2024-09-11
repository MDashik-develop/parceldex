<!-- resources/views/pdf/document.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <title>Merchant Delivery Payment| {{ session()->get('company_name') ?? config('app.name', 'Flier Express') }}
    </title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            min-height: 100%;
        }

        body {
            font-size: 10px !important;
            font-family: Arial, sans-serif;
            /* background: url('{{ public_path('image/invoice-bg.png') }}') no-repeat center center fixed; */
            /* background-size: cover; */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            /* Slightly transparent background */
            /* padding: 20px; */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            overflow: hidden;
        }

        .header,
        .footer {
            margin: 20px 0;
        }

        .header img {
            max-width: 150px;
        }

        .section {
            display: inline-block;
            width: 45%;
            vertical-align: top;
            margin: 10px;
        }

        .section h2 {
            text-align: center;
            text-decoration: underline;
        }

        table>tbody>tr>td,
        table>tbody>tr>th,
        table>tfoot>tr>td,
        table>tfoot>tr>th,
        table>thead>tr>td,
        table>thead>tr>th {
            padding: 2px;
            line-height: 1;
        }

        table {
            margin-bottom: .0rem;
        }

        table td,
        table th {
            padding: .0rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            text-align: left;
            padding: 4px;
        }

        th,
        td {
            width: 8%;
        }
    </style>
</head>

<body>
    <div class="container">
        <section
            style="text-align: center; font-size: 26px; font-weight: bold; color: #00509d; margin-top: 2rem; margin-bottom: 2rem;">
            Parceldex Payment
            Statement</section>

        <div class="section">
            <h2>Payment Info</h2>
            <table>
                <tr>
                    <td>ID</td>
                    <td>{{ $parcelMerchantDeliveryPayment->merchant_payment_invoice }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ \Carbon\Carbon::parse($parcelMerchantDeliveryPayment->date_time)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Total Parcel</td>
                    <td>{{ $parcelMerchantDeliveryPayment->total_payment_parcel }}</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td>{{ number_format($parcelMerchantDeliveryPayment->total_payment_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Reference</td>
                    <td>{{ $parcelMerchantDeliveryPayment->transfer_reference }}</td>
                </tr>
                <tr>
                    <td>Note</td>
                    <td>{{ $parcelMerchantDeliveryPayment->note }}</td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h2>Merchant Info</h2>
            <table>
                <tr>
                    <td>Name</td>
                    <td>{{ $parcelMerchantDeliveryPayment->merchant->company_name }}</td>
                </tr>
                <tr>
                    <td>Contact</td>
                    <td>{{ $parcelMerchantDeliveryPayment->merchant->contact_number }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>{{ $parcelMerchantDeliveryPayment->merchant->address }}</td>
                </tr>
            </table>
        </div>

        @if ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details->count() > 0)
            <table class="table table-bordered" width="100%"
                style="margin-top: 2rem; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th class="text-center"> SL</th>
                        <th class="text-center">Invoice</th>
                        <th class="text-center">Order ID</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Customer Name</th>
                        <th class="text-center">Customer Number</th>
                        <th class="text-center">Amount to be Collect</th>
                        <th class="text-center">Collected</th>
                        <th class="text-center"> Weight Charge</th>
                        <th class="text-center"> COD Charge</th>
                        <th class="text-center">Delivery</th>
                        <th class="text-center">Return</th>
                        <th class="text-center">Total Charge</th>
                        <th class="text-center">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalCollectionAmount = 0;
                        $total_weight_package_charge = 0;
                        $total_cod_charge = 0;
                        $total_delivery_charge = 0;
                        $total_return_charge = 0;
                        $total_paid_amount = 0;
                        $total_charge = 0;
                        $total_collect_amount = 0;

                    @endphp


                    @foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $parcel_merchant_delivery_payment_detail)
                        @php
                            $parcelStatus = returnParcelStatusNameForMerchant(
                                $parcel_merchant_delivery_payment_detail->parcel->status,
                                $parcel_merchant_delivery_payment_detail->parcel->delivery_type,
                                $parcel_merchant_delivery_payment_detail->parcel->payment_type,
                            );

                        @endphp
                        <tr>
                            <td class="text-center"> {{ $loop->iteration }} </td>
                            <td class="text-center">
                                {{ $parcel_merchant_delivery_payment_detail->parcel->parcel_invoice }} </td>
                            <td class="text-center">
                                {{ $parcel_merchant_delivery_payment_detail->parcel->merchant_order_id }} </td>
                            <td class="text-center"> {{ $parcelStatus['status_name'] }} </td>
                            <td class="text-center">
                                {{ $parcel_merchant_delivery_payment_detail->parcel->customer_name }} </td>
                            <td class="text-center">
                                {{ $parcel_merchant_delivery_payment_detail->parcel->customer_contact_number }}
                            </td>

                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->parcel->total_collect_amount, 2) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->collected_amount, 2) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->weight_package_charge, 2) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->cod_charge, 2) }} </td>
                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->delivery_charge, 2) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->return_charge, 2) }}
                            </td>
                            <td class="text-center">
                                {{ number_format(
                                    $parcel_merchant_delivery_payment_detail->parcel->total_charge +
                                        $parcel_merchant_delivery_payment_detail->return_charge,
                                    2,
                                ) }}
                            </td>
                            <td class="text-center">
                                {{ number_format($parcel_merchant_delivery_payment_detail->paid_amount, 2) }} </td>
                        </tr>
                        @php

                            $totalCollectionAmount += $parcel_merchant_delivery_payment_detail->collected_amount;
                            $total_weight_package_charge +=
                                $parcel_merchant_delivery_payment_detail->weight_package_charge;
                            $total_cod_charge += $parcel_merchant_delivery_payment_detail->cod_charge;
                            $total_delivery_charge += $parcel_merchant_delivery_payment_detail->delivery_charge;
                            $total_return_charge += $parcel_merchant_delivery_payment_detail->return_charge;
                            $total_paid_amount += $parcel_merchant_delivery_payment_detail->paid_amount;
                            $total_charge +=
                                $parcel_merchant_delivery_payment_detail->parcel->total_charge +
                                $parcel_merchant_delivery_payment_detail->return_charge;
                            $total_collect_amount +=
                                $parcel_merchant_delivery_payment_detail->parcel->total_collect_amount;
                        @endphp
                    @endforeach
                    <tr>
                        <th colspan="6" style="text-align: right">Totals:</th>

                        <th class="text-center">{{ number_format($total_collect_amount) }}</th>
                        <th class="text-center">{{ number_format($totalCollectionAmount) }}</th>
                        <th class="text-center">{{ number_format($total_weight_package_charge) }}</th>
                        <th class="text-center">{{ number_format($total_cod_charge) }}</th>
                        <th class="text-center">{{ number_format($total_delivery_charge) }}</th>
                        <th class="text-center">{{ number_format($total_return_charge) }}</th>
                        <th class="text-center">{{ number_format($total_charge) }}</th>
                        <th class="text-center">{{ number_format($total_paid_amount) }}</th>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</body>

</html>
