<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Delivery Payment| {{ session()->get('company_name') ?? config('app.name', 'Flier Express') }}
    </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .font-14 {
            font-size: 14px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            gap: 10px;
            flex-wrap: wrap;
        }

        .label {
            width: 297mm;
            min-height: 210mm;
            padding: 20px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .label .hedding {
            font-size: 17px;
            color: #343a40;
            border-bottom: 2px solid #6c757d;
            padding-bottom: 5px;
        }

        .section {
            margin-bottom: 10px;
            font-size: 17px;
            line-height: 1.6;
        }

        .barcode {
            text-align: center;
            margin: 15px 0;
        }

        .barcode img {
            max-width: 100%;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            width: 100%;
            flex-wrap: wrap;
        }

        .info div {
            flex: 1;
            text-align: center;
            font-size: 14px;
            /* background-color: #e9ecef; */
            border-radius: 5px;
            padding: 5px;
            margin: 0 5px;
        }

        .footer {
            text-align: right;
            font-size: 12px;
            color: #6c757d;
            margin-top: 10px;
        }
    </style>

    <style>
        @page {
            size: 297mm 210mm;
            margin: 5mm 5mm 5mm 5mm;
            /* margin: 0; */
            /* change the margins as you want them to be. */
        }

        @media print {

            body {
                font-family: Arial, sans-serif;
                background-color: #fff;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                gap: 0px;
                flex-wrap: wrap;
            }

            .label {
                width: 297mm;
                min-height: 210mm;
                padding: 0px;
                background: none;
                border: none;
                border-radius: 0px;
                box-shadow: none;
            }

            .print-none {
                display: none;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <!-- Include the QRCode library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        function printPage() {
            window.print();
        }
    </script>

</head>

<body>


    <div class="label font-14" style="position: relative;">
        <button style=" position: absolute; top: 0; right: -70px;" class="btn btn-success print-none"
            onclick="printPage()">Print</button>
        <h5><strong>Parceldex Payment Summary</strong></h5>
        <hr class="my-2" style="opacity: 1;border: 1px solid black;">

        <div class="d-flex justify-content-between align-items-start">
            <div>
                <strong>Invoice To</strong><br>
                <strong>{{ $parcelMerchantDeliveryPayment->merchant->company_name }}</strong><br>
                <span>{{ $parcelMerchantDeliveryPayment->merchant->address }}</span><br>
                <span>Email: {{ $parcelMerchantDeliveryPayment->merchant->email }}</span><br>
                <span>Mobile: {{ $parcelMerchantDeliveryPayment->merchant->contact_number }}</span><br>
                <span><strong>Adjustment Note:</strong> {{ $parcelMerchantDeliveryPayment->adjustment_note }}</span>
            </div>

            <table class="table w-auto table-striped table-bordered font-14" style="min-width: 300px;">
                <tr>
                    <th>Invoice Date</th>
                    <td>{{ \Carbon\Carbon::parse($parcelMerchantDeliveryPayment->date_time)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Invoice ID</th>
                    <td>{{ $parcelMerchantDeliveryPayment->merchant_payment_invoice }}</td>
                </tr>
                <tr>
                    <th>Commission</th>
                    <td> {{ $parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details->sum('parent_commission_amount') ?? 0 }}
                    </td>
                </tr>
                <tr>
                    <th>Paid Amount</th>
                    <td>{{ number_format($parcelMerchantDeliveryPayment->total_payment_amount, 0) }}</td>
                </tr>
                <tr>
                    <th>Adjustment</th>
                    <td> {{ $parcelMerchantDeliveryPayment->adjustment ?? 0 }} </td>
                </tr>
                <tr>
                    <th>Total Paid Amount</th>
                    <td>{{ number_format($parcelMerchantDeliveryPayment->total_payment_amount + ($parcelMerchantDeliveryPayment->adjustment ?? 0), 0) }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="table w-auto table-bordered" style="font-size: 12px">
            <thead>
                <tr class="table-active text-center">
                    <th>SL</th>
                    <th>Cons ID</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Delivery Area</th>
                    <th>Parcel Status</th>
                    <th>Product Price</th>
                    <th>Collected Amount</th>
                    <th>Delivery Charge</th>
                    <th>COD Charge</th>
                    <th>Weight Charge</th>
                    <th>Return Charge</th>
                    <th>Total Service Charge</th>
                    <th>Paid Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_product_price = 0;
                    $total_collected = 0;
                    $total_delivery_charge = 0;
                    $total_cod_charge = 0;
                    $total_wight_charge = 0;
                    $total_return_charge = 0;
                    $total_service_charge = 0;
                    $total_paid_amount = 0;
                @endphp

                @foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $key => $item)
                    @php
                        $total_charge =
                            $item->parcel?->delivery_charge +
                            $item->parcel?->cod_charge +
                            $item->parcel?->weight_package_charge +
                            $item->parcel?->return_charge;

                        $collected_amount = $item->parcel?->customer_collect_amount
                            ? $item->parcel->customer_collect_amount
                            : $item->parcel->cancel_amount_collection;

                        $total_product_price += $item->parcel->product_value;
                        $total_collected += $collected_amount;
                        $total_delivery_charge += $item->parcel->delivery_charge;
                        $total_cod_charge += $item->parcel->cod_charge;
                        $total_wight_charge += $item->parcel->weight_package_charge;
                        $total_return_charge += $item->parcel->return_charge;
                        $total_service_charge += $total_charge;
                        $total_paid_amount += $collected_amount - $total_charge;
                    @endphp

                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->parcel?->parcel_invoice }}</td>
                        <td>{{ $item->parcel?->merchant_order_id }}</td>
                        <td>{{ $item->parcel?->customer_name }}</td>
                        <td>{{ $item->parcel?->customer_contact_number }}</td>
                        <td>{{ $item->parcel?->district?->service_area?->name }}</td>
                        <td>{{ returnParcelStatusNameForBranch(
                            $item->parcel?->status,
                            $item->parcel?->delivery_type,
                            $item->parcel?->payment_type,
                            $item->parcel,
                        )['status_name'] }}
                        </td>
                        <td>{{ $item->parcel?->product_value }}</td>
                        <td>{{ $collected_amount }}</td>
                        <td>{{ $item->parcel?->delivery_charge }}</td>
                        <td>{{ $item->parcel?->cod_charge }}</td>
                        <td>{{ $item->parcel?->weight_package_charge }}</td>
                        <td>{{ $item->parcel?->return_charge }}</td>
                        <td>{{ $total_charge }}</td>
                        <td>{{ $collected_amount - $total_charge }}</td>
                    </tr>
                @endforeach

            </tbody>

            <tfoot>
                <tr>
                    <th colspan="7">Total</th>
                    <th>{{ number_format($total_product_price) }}</th>
                    <th>{{ number_format($total_collected) }}</th>
                    <th>{{ number_format($total_delivery_charge) }}</th>
                    <th>{{ number_format($total_cod_charge) }}</th>
                    <th>{{ number_format($total_wight_charge) }}</th>
                    <th>{{ number_format($total_return_charge) }}</th>
                    <th>{{ number_format($total_service_charge) }}</th>
                    <th>{{ number_format($total_paid_amount) }}</th>
                </tr>
            </tfoot>

        </table>

        <div class="mt-4">
            <p class="text-center">Any dispute must be notified in written within 5 days from the date of this
                invoice.This is an electronic statement, does not require any signature</p>
        </div>

        <footer></footer>

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        JsBarcode("#barcode", "Hi!", {
            // format: "pharmacode",
            // lineColor: "#0aa",
            // width: 4,
            height: 30,
            fontSize: 15
            // displayValue: false
        });
    </script>

    <!-- Your script that uses QRCode -->
    <script>
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "https://example.com",
            width: 80,
            height: 80
        });
    </script>
</body>

</html>
