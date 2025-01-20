<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transit Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
    <style>
        :root {
            --width: 297mm;
            --height: 210mm;
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

            .dt-container>.dt-layout-row:first-child {
                display: none;
                /* Hides the first child of .dt-layout-row */
            }

            .label {
                width: 100%;
                height: auto;
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
    <script src="//cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
</head>

<body>

    @php

        $start_date = request()->start_date
            ? \Illuminate\Support\Carbon::parse(request()->start_date)->startOfDay()
            : \Illuminate\Support\Carbon::now()->startOfMonth();

        $end_date = request()->end_date
            ? \Illuminate\Support\Carbon::parse(request()->end_date)->endOfDay()
            : \Illuminate\Support\Carbon::now()->endOfDay();

        $qbrach = \App\Models\Branch::withCount([
            'pickupParcels as pickup_parcel_sum' => function ($query) use ($start_date, $end_date) {
                $query
                    ->whereIn('parcels.status', [11, 13, 15])
                    ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
                    ->where('parcel_logs.status', 11)
                    ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
            },
        ])->withCount([
            'pickupParcels as transit_parcel_sum' => function ($query) use ($start_date, $end_date) {
                $query
                    ->whereIn('parcels.status', [12])
                    ->join('parcel_logs', 'parcel_logs.parcel_id', '=', 'parcels.id')
                    ->where('parcel_logs.status', 12)
                    ->whereBetween('parcel_logs.date', [$start_date, $end_date]);
            },
        ]);

        if (request()->branch_id) {
            $qbrach = $qbrach->where('id', request()->branch_id);
        }

        $qbrach = $qbrach->get();

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

            <h1 class="text-center">Transit Report</h1>
            <p class="text-center">Date: {{ $start_date->format('d M Y') }} - {{ $end_date->format('d M Y') }}</p>

            <div class="row">
                <div>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Sl</th>
                                <th>Hub</th>
                                <th>Transit Pending</th>
                                <th>Transit</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($qbrach as $k => $b)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $b->name }}</td>
                                    <td>{{ $b->pickup_parcel_sum }}</td>
                                    <td>{{ $b->transit_parcel_sum }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <td colspan="2" class="text-end fw-bold">Total</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

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

    {{-- datatable --}}
    <script>
        new DataTable('table', {
            paging: false, // Disables pagination
            ordering: false, // Disables ordering
            info: false,
            // dom: 't'
        });
    </script>

</body>

</html>
