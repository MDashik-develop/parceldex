<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --width: 210mm;
            --height: 297mm;
            --margin: 10mm;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .print-container {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin: auto;

        }

        .label {
            width: var(--width);
            min-height: var(--height);
            padding: var(--margin);
            background: #fff;
            border: 1px solid #dee2e6;
            /* border-radius: 10px; */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: scroll;
        }
    </style>

    <style>
        @page {
            /* size: var(--width) var(--height); */
            margin: var(--margin) var(--margin) var(--margin) var(--margin);
            /* change the margins as you want them to be. */
        }

        @media print {

            body {
                background-color: #fff;
                min-height: auto;
            }

            .print-container {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
                margin: auto;
            }

            .label {
                width: 100%;
                height: auto;
                min-height: auto;
                padding: 0;
                background: #fff;
                border: none;
                border-radius: 0px;
                box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);
                overflow-x: hidden;
            }

            .print-hidden {
                display: none;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <!-- Include the QRCode library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>

@php
    $start_date = \Illuminate\Support\Carbon::now()->startOfMonth();
    $end_date = \Illuminate\Support\Carbon::now()->endOfDay();

    $start_date_filter = $start_date;
    $end_date_filter = $end_date;

    $s_parcels = App\Models\Parcel::whereBetween('parcels.status', [25, 36])
        ->whereNull('parcels.suborder')
        ->whereBetween('delivery_branch_date', [$start_date_filter, $end_date_filter])
        ->whereIn('delivery_type', [1, 2, 4])
        ->get();

    $total_day = $end_date->diffInDays($start_date);

    $start_date_filter = $start_date->subMonths(1);
    $end_date_filter = $end_date->subMonths(1);

    $c_parcels = App\Models\Parcel::whereBetween('parcels.status', [25, 36])
        ->whereNull('parcels.suborder')
        ->whereBetween('delivery_branch_date', [$start_date_filter, $end_date_filter])
        ->whereIn('delivery_type', [1, 2, 4])
        ->get();

    // dd($s_parcels);

@endphp

<body>
    <section style="background: #f8f9fa;" class="print-hidden">
        <div class="container py-5 mx-auto" style="max-width: 900px;">
            <form class="d-flex gap-5 flex-wrap justify-content-center flex-lg-nowrap align-content-center">
                <select name="" id="" class="form-control">
                    <option value="">All</option>
                    <option value="">ISD</option>
                    <option value="">OSD</option>
                    <option value="">SUB</option>
                </select>
                <label for="" style="white-space: nowrap;">Start Date</label>
                <input type="date" name="start_date" id="" class="form-control"
                    value="{{ $start_date->format('Y-m-d') }}">
                <label for="" style="white-space: nowrap;">End Date</label>
                <input type="date" name="end_date" id="" class="form-control"
                    value="{{ $end_date->format('Y-m-d') }}">
                <button type="button" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </section>

    <div class="print-container">
        <div class="label">
            <div class="d-flex justify-content-between align-content-end flex-wrap" style="align-items: flex-end;">
                <h3>Parceldex Limited</h3>
                <div>
                    {{ $start_date->format('d M Y') }} - {{ $end_date->format('d M Y') }}
                </div>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th></th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Compare Previous</th>
                        <th class="text-end">Selected Range</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Number of Order</strong></td>
                        <td class="text-end">{{ $c_parcels->count() + $s_parcels->count() }}</td>
                        <td class="text-end">{{ $c_parcels->count() }}</td>
                        <td class="text-end">{{ $s_parcels->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Amount to be Collect</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('total_collect_amount') + $s_parcels->sum('total_collect_amount') }}
                        </td>
                        <td class="text-end">{{ $c_parcels->sum('total_collect_amount') }}</td>
                        <td class="text-end">{{ $s_parcels->sum('total_collect_amount') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Collected Amount</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('cancel_amount_collection') + $s_parcels->sum('cancel_amount_collection') + $c_parcels->sum('customer_collect_amount') + $s_parcels->sum('customer_collect_amount') }}
                        </td>
                        <td class="text-end">
                            {{ $c_parcels->sum('cancel_amount_collection') + $c_parcels->sum('customer_collect_amount') }}
                        </td>
                        <td class="text-end">
                            {{ $s_parcels->sum('cancel_amount_collection') + $s_parcels->sum('customer_collect_amount') }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Weight Charge</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('weight_package_charge') + $s_parcels->sum('weight_package_charge') }}
                        </td>
                        <td class="text-end">{{ $c_parcels->sum('weight_package_charge') }}</td>
                        <td class="text-end">{{ $s_parcels->sum('weight_package_charge') }}</td>
                    </tr>
                    <tr>
                        <td><strong>COD Charge</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('cod_charge') + $s_parcels->sum('cod_charge') }}
                        </td>
                        <td class="text-end">{{ $c_parcels->sum('cod_charge') }}</td>
                        <td class="text-end">{{ $s_parcels->sum('cod_charge') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Delivery Charge</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('delivery_charge') + $s_parcels->sum('delivery_charge') }}
                        </td>
                        <td class="text-end">{{ $c_parcels->sum('delivery_charge') }}</td>
                        <td class="text-end">{{ $s_parcels->sum('delivery_charge') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Return Charge</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('return_charge') + $s_parcels->sum('return_charge') }}
                        </td>
                        <td class="text-end">{{ $c_parcels->sum('return_charge') }}</td>
                        <td class="text-end">{{ $s_parcels->sum('return_charge') }}</td>
                    </tr>
                    <tr class="table-info">
                        <td><strong>Total Revenue</strong></td>
                        <td class="text-end">
                            {{ $c_parcels->sum('weight_package_charge') + $s_parcels->sum('weight_package_charge') + $c_parcels->sum('cod_charge') + $s_parcels->sum('cod_charge') + $c_parcels->sum('delivery_charge') + $s_parcels->sum('delivery_charge') + $c_parcels->sum('return_charge') + $s_parcels->sum('return_charge') }}
                        </td>
                        <td class="text-end">
                            {{ $c_parcels->sum('weight_package_charge') + $c_parcels->sum('cod_charge') + $c_parcels->sum('delivery_charge') + $c_parcels->sum('return_charge') }}
                        </td>
                        <td class="text-end">
                            {{ $s_parcels->sum('weight_package_charge') + $s_parcels->sum('cod_charge') + $s_parcels->sum('delivery_charge') + $s_parcels->sum('return_charge') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>Average Revenue Per Order</strong></td>
                        @php
                            $ar =
                                $c_parcels->sum('weight_package_charge') +
                                $s_parcels->sum('weight_package_charge') +
                                $c_parcels->sum('cod_charge') +
                                $s_parcels->sum('cod_charge') +
                                $c_parcels->sum('delivery_charge') +
                                $s_parcels->sum('delivery_charge') +
                                $c_parcels->sum('return_charge') +
                                $s_parcels->sum('return_charge');
                            $ad = $c_parcels->count() + $s_parcels->count();
                        @endphp
                        <td class="text-end">
                            {{ number_format($ar / ($ad ?? 1), 2) }}
                        </td>
                        <td class="text-end">
                            {{ number_format(($c_parcels->sum('weight_package_charge') + $c_parcels->sum('cod_charge') + $c_parcels->sum('delivery_charge') + $c_parcels->sum('return_charge')) / ($c_parcels->count() ?: 1), 2) }}
                        </td>
                        <td class="text-end">
                            {{ number_format(($s_parcels->sum('weight_package_charge') + $s_parcels->sum('cod_charge') + $s_parcels->sum('delivery_charge') + $s_parcels->sum('return_charge')) / ($s_parcels->count() ?: 1), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Average Order Per Day</strong></td>
                        <td class="text-end">
                            {{ number_format(($c_parcels->count() + $s_parcels->count()) / $total_day ?? 1, 2) }}
                        </td>
                        <td class="text-end">{{ number_format($c_parcels->count() / $total_day ?? 1, 2) }}</td>
                        <td class="text-end">{{ number_format($s_parcels->count() / $total_day ?? 1, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

        </div>
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
