@extends('layouts.merchant_layout.merchant_layout')

@section('content')
    @php

        $merchant = auth()->guard('merchant')->user();
        $merchant_id = $merchant->id;

        $parcels = App\Models\Parcel::where('merchant_id', $merchant_id)
            ->whereIn('status', [21, 22, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37])
            ->where(function ($query) {
                $query->whereNull('delivery_type')->orWhereIn('delivery_type', [1, 2, 4]);
            })
            ->where(function ($query) {
                $query->whereNull('payment_type')->orWhereIn('payment_type', [1, 2, 3, 6]);
            });

        $total_customer_collect_amount =
            $parcels->sum('customer_collect_amount') + $parcels->sum('cancel_amount_collection');

    @endphp

    <style>
        .table thead th {
            vertical-align: middle;
        }
    </style>

    <div class="container">

        <br>
        <div>
            <div
                style="max-width: 800px; margin: 0 auto; background: #ff8b34; padding: 20px; border-radius: 10px; color: #fff;">
                <h6 style="text-align: center">Your Balance</h6>
                <h2 style="text-align: center">0 TK</h2>
            </div>
        </div>

        <br>

        <div>
            <div
                style="max-width: 800px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 10px; color: #000; gap: 10px; display: flex; flex-direction: column;">

                <div style="display: flex; justify-content: space-between;">
                    <div>Colleced Amount</div>
                    <div>{{ $total_customer_collect_amount }}</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Delivery Charge</div>
                    <div>-0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>COD Charge</div>
                    <div>-0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Weight Charge</div>
                    <div>-0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Return Charge</div>
                    <div>-0</div>
                </div>

                <div style="display: flex; justify-content: space-between; border-top: 1px solid #3d3d3d;">
                    <div>Avaiable Balance (BDT)</div>
                    <div>0</div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Balance Details </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width: 100%; text-align: center;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Cons ID</th>
                                    <th>Merchant order ID</th>
                                    <th>Status</th>
                                    <th>Amount to be Collect</th>
                                    <th>Collected Amount</th>
                                    <th>Delivery Charge</th>
                                    <th>COD Charge</th>
                                    <th>Weight Charge</th>
                                    <th>Return Charge</th>
                                    <th>Payable Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($parcels->get() ?? [] as $parcel)
                                    <tr>
                                        <td>{{ $parcel->created_at }}</td>
                                        <td>{{ $parcel->parcel_invoice }}</td>
                                        <td>{{ $parcel->merchant_order_id }}</td>
                                        <td>{{ $parcel->status }}</td>
                                        <td>{{ $parcel->customer_collect_amount }}</td>
                                        <td>{{ $parcel->collected_amount }}</td>
                                        <td>{{ $parcel->delivery_charge }}</td>
                                        <td>{{ $parcel->cod_charge }}</td>
                                        <td>{{ $parcel->weight_charge }}</td>
                                        <td>{{ $parcel->return_charge }}</td>
                                        <td>{{ $parcel->payable_amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_js')
    <script>
        window.onload = function() {
            var table = $('#yajraDatatable').DataTable({
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('merchant.coverageArea') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'area',
                        name: 'area'
                    },
                    {
                        data: 'post_code',
                        name: 'post_code'
                    },
                    {
                        data: 'district',
                        name: 'district'
                    },
                    {
                        data: 'service_area',
                        name: 'service_area'
                    },
                    {
                        data: 'cod_charge',
                        name: 'cod_charge'
                    },
                ]
            });
        }
    </script>
@endpush
