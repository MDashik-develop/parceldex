@extends('layouts.merchant_layout.merchant_layout')

@section('content')
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
                    <div>0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Delivery Charge</div>
                    <div>0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>COD Charge</div>
                    <div>0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Weight Charge</div>
                    <div>0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Return Charge</div>
                    <div>0</div>
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
                        <table class="table table-striped" style="width: 100%">
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
                                @foreach ($balance_details ?? [] as $balance_detail)
                                    <tr>
                                        <td> {{ $balance_detail->date }} </td>
                                        <td> {{ $balance_detail->order_id }} </td>
                                        <td> {{ $balance_detail->order_amount }} </td>
                                        <td> {{ $balance_detail->delivery_charge }} </td>
                                        <td> {{ $balance_detail->cod_charge }} </td>
                                        <td> {{ $balance_detail->total_amount }} </td>
                                        <td> {{ $balance_detail->paid_amount }} </td>
                                        <td> {{ $balance_detail->due_amount }} </td>
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
