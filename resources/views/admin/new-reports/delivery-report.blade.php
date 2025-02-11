<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --width: auto;
            --height: auto;
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

            .label {
                width: 100%;
                height: auto;
                padding: 0;
                background: #fff;
                border: none;
                border-radius: 0px;
                box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);
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

<body>

    @php

        $start_date = request()->start_date
            ? \Illuminate\Support\Carbon::parse(request()->start_date)->startOfDay()
            : \Illuminate\Support\Carbon::now()->startOfMonth();

        $end_date = request()->end_date
            ? \Illuminate\Support\Carbon::parse(request()->end_date)->endOfDay()
            : \Illuminate\Support\Carbon::now()->endOfDay();

        $qbrach = \App\Models\Branch::with([
            'riders.deliveryParcels' => function ($q) use ($start_date, $end_date) {
                $q->whereBetween('delivery_date', [$start_date, $end_date]);
            },
        ]);
        // ->withCount([
        //     'riders as total_parcel' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 19)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ])
        // ->withCount([
        //     'riders as total_deliveried' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 25)
        //                 ->where('parcels.delivery_type', 1)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ])
        // ->withCount([
        //     'riders as total_partial_deliveried' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 25)
        //                 ->where('parcels.delivery_type', 2)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ])
        // ->withCount([
        //     'riders as total_hold' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 25)
        //                 ->where('parcels.delivery_type', 3)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ])
        // ->withCount([
        //     'riders as total_cancel' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 25)
        //                 ->where('parcels.delivery_type', 4)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ])
        // ->withCount([
        //     'riders as total_cancel' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 25)
        //                 ->where('parcels.delivery_type', 4)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ])
        // ->withCount([
        //     'riders as total_unassigned' => function ($query) use ($start_date, $end_date) {
        //         $query->whereHas('deliveryParcels', function ($subQuery) use ($start_date, $end_date) {
        //             $subQuery
        //                 ->whereIn('parcels.status', [16, 17, 18, 20])
        //                 ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
        //                 ->where('parcel_logs.status', 16)
        //                 // ->where('parcels.delivery_type', 2)
        //                 ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
        //         });
        //     },
        // ]);

        if (request()->branch_id) {
            $qbrach = $qbrach->where('id', request()->branch_id);
        }

        $qbrach = $qbrach->limit(1000)->get();

        // dd($qbrach);

        $branches = App\Models\Branch::get();

    @endphp

    <section style="background: #f8f9fa;" class="print-hidden">
        <div class="container py-5 mx-auto" style="max-width: 900px;">
            <form class="d-flex gap-5 flex-wrap justify-content-center flex-lg-nowrap align-content-center">
                <select name="branch_id" id="" class="form-control">
                    <option value="">All</option>
                    @foreach ($branches as $item)
                        <option value="{{ $item->id }}" {{ $item->id == request()->branch_id ? 'selected' : '' }}>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
                <label for="" style="white-space: nowrap;">Start Date</label>
                <input type="date" name="start_date" id="" class="form-control"
                    value="{{ $start_date->format('Y-m-d') }}">
                <label for="" style="white-space: nowrap;">End Date</label>
                <input type="date" name="end_date" id="" class="form-control"
                    value="{{ $end_date->format('Y-m-d') }}">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </section>

    <div class="print-container">
        <div class="label">

            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Hub Name</th>
                        <th>Rider Name</th>
                        <th>Total Assigned</th>
                        <th>Delivered</th>
                        <th>Partial Delivered</th>
                        <th>Hold</th>
                        <th>Cancel</th>
                        <th>Success Ratio</th>
                        <th>Pending Ratio</th>
                        <th>Return Ratio</th>
                        <th>Collected Amount</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $btotal_assigned = 0;
                        $btotal_deliveried = 0;
                        $btotal_partial_delivered = 0;
                        $btotal_hold = 0;
                        $btotal_cancel = 0;
                        $btotal_collect_amount = 0;
                    @endphp

                    @foreach ($qbrach as $b)
                        <!-- Hub -->

                        @php
                            $total_assigned = 0;
                            $total_deliveried = 0;
                            $total_partial_delivered = 0;
                            $total_hold = 0;
                            $total_cancel = 0;
                            $total_collect_amount = 0;
                        @endphp

                        @foreach ($b->riders as $kr => $r)
                            @php
                                $totalParcels = $r->deliveryParcels->count();

                                $btotal_assigned += $totalParcels;
                                $total_assigned += $totalParcels;

                                $btotal_deliveried += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 1)
                                    ->count();
                                $total_deliveried += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 1)
                                    ->count();

                                $btotal_partial_delivered += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 2)
                                    ->count();
                                $total_partial_delivered += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 2)
                                    ->count();

                                $btotal_hold += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 3)
                                    ->count();
                                $total_hold += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 3)
                                    ->count();

                                $btotal_cancel += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 4)
                                    ->count();
                                $total_cancel += $r->deliveryParcels
                                    ->where('status', 25)
                                    ->where('delivery_type', 4)
                                    ->count();

                                $total_collect_amount +=
                                    $r->deliveryParcels->sum('customer_collect_amount') +
                                    $r->deliveryParcels->sum('cancel_amount_collection');
                            @endphp

                            <tr>
                                @if (!$kr)
                                    <td rowspan="{{ $b->riders->count() }}">{{ $b->name }}</td>
                                @endif
                                <td>{{ $r->name }}</td>
                                <td style="text-align: right">{{ $r->deliveryParcels->count() ?? 0 }}</td>
                                <td style="text-align: right">
                                    {{ $r->deliveryParcels->where('status', 25)->where('delivery_type', 1)->count() ?? 0 }}
                                </td>
                                <td style="text-align: right">
                                    {{ $r->deliveryParcels->where('status', 25)->where('delivery_type', 2)->count() ?? 0 }}
                                </td>
                                <td style="text-align: right">
                                    {{ $r->deliveryParcels->where('status', 25)->where('delivery_type', 3)->count() ?? 0 }}
                                </td>
                                <td style="text-align: right">
                                    {{ $r->deliveryParcels->where('status', 25)->where('delivery_type', 4)->count() ?? 0 }}
                                </td>
                                <td style="text-align: right">
                                    @php
                                        $deliveredParcels = $r->deliveryParcels
                                            ->where('status', 25)
                                            ->whereIn('delivery_type', [1, 2])
                                            ->count();
                                    @endphp

                                    @if ($totalParcels > 0)
                                        {{ number_format(($deliveredParcels / $totalParcels) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td style="text-align: right">
                                    @php
                                        $holdParcels = $r->deliveryParcels
                                            ->where('status', 25)
                                            ->where('delivery_type', 3)
                                            ->count();
                                    @endphp
                                    @if ($totalParcels > 0)
                                        {{ number_format(($holdParcels / $totalParcels) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td style="text-align: right">
                                    @php
                                        $cancelParcels = $r->deliveryParcels
                                            ->where('status', 25)
                                            ->where('delivery_type', 4)
                                            ->count();
                                    @endphp
                                    @if ($totalParcels > 0)
                                        {{ number_format(($cancelParcels / $totalParcels) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td style="text-align: right">
                                    {{ $r->deliveryParcels->sum('customer_collect_amount') + $r->deliveryParcels->sum('cancel_amount_collection') }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold">
                            <td colspan="2" class="text-center">{{ $b->name }} Total</td>
                            <td style="text-align: right">{{ $total_assigned }}</td>
                            <td style="text-align: right"> {{ $total_deliveried }} </td>
                            <td style="text-align: right">{{ $total_partial_delivered }}</td>
                            <td style="text-align: right">
                                {{ $total_hold }}
                            </td>
                            <td style="text-align: right">
                                {{ $total_cancel }}
                            </td>
                            <td style="text-align: right">
                                {{ number_format(($total_deliveried * 100) / ($total_assigned ?? 1), 2) }}%
                            </td>
                            <td style="text-align: right">
                                {{ number_format(($total_hold * 100) / ($total_assigned ?? 1), 2) }}%
                            </td>
                            <td style="text-align: right">
                                {{ number_format(($total_cancel * 100) / ($total_assigned ?? 1), 2) }}%
                            </td>
                            <td style="text-align: right">
                                {{ $total_collect_amount }}
                            </td>
                        </tr>
                    @endforeach

                    <!-- Grand Total -->
                    <tr class="table-dark fw-bold">
                        <td colspan="2" class="text-center">Grand Total</td>
                        <td style="text-align: right">{{ $btotal_assigned }}</td>
                        <td style="text-align: right">{{ $btotal_deliveried }}</td>
                        <td style="text-align: right">{{ $btotal_partial_delivered }}</td>
                        <td style="text-align: right">{{ $btotal_hold }}</td>
                        <td style="text-align: right">{{ $btotal_cancel }}</td>
                        <td style="text-align: right">
                            {{ number_format(($btotal_deliveried * 100) / ($btotal_assigned ?? 1), 2) }}%
                        </td>
                        <td style="text-align: right">
                            {{ number_format(($btotal_hold * 100) / ($btotal_assigned ?? 1), 2) }} %
                        </td>
                        <td style="text-align: right">
                            {{ number_format(($btotal_cancel * 100) / ($btotal_assigned ?? 1), 2) }}%
                        </td>
                        <td style="text-align: right">
                            {{ $btotal_collect_amount }}
                        </td>
                    </tr>
                </tbody>
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
