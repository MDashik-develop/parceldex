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
    @endphp

    @php
        $total_customer_collect_amount = 0;
        $total_collected_amount = 0;
        $total_delivery_charge = 0;
        $total_cod_charge = 0;
        $total_weight_charge = 0;
        $total_return_charge = 0;
        $total_child_commission = 0;
        $total_referral_commission = 0;
        $total_payable = 0;
    @endphp

    @forelse ($parcels->get() as $parcel)
        @php
            $total_customer_collect_amount += $parcel->customer_collect_amount + $parcel->cancel_amount_collection;
            $total_collected_amount += $parcel->total_collect_amount;
            $total_delivery_charge += $parcel->delivery_charge;
            $total_cod_charge += $parcel->cod_charge;
            $total_weight_charge += $parcel->weight_package_charge;
            $total_return_charge += $parcel->return_charge;
            $total_child_commission += $parcel->parent_commission_amount;
            $total_referral_commission += 0;

            $payable_amount =
                $parcel->customer_collect_amount +
                $parcel->cancel_amount_collection -
                $parcel->delivery_charge -
                $parcel->cod_charge -
                $parcel->weight_package_charge -
                $parcel->return_charge -
                $parcel->parent_commission_amount;

            $total_payable += $payable_amount;
        @endphp

    @empty
    @endforelse

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
                    <div>- {{ $total_delivery_charge }}</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>COD Charge</div>
                    <div>-{{ $total_cod_charge }}</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Weight Charge</div>
                    <div>-0</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Return Charge</div>
                    <div>-{{ $total_return_charge }}</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Child Commission</div>
                    <div>-{{ $total_child_commission }}</div>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div>Referral Commission</div>
                    <div>-{{ $total_referral_commission }}</div>
                </div>

                <div style="display: flex; justify-content: space-between; border-top: 1px solid #3d3d3d;">
                    <div>Avaiable Balance (BDT)</div>
                    <div> {{ $total_payable }} </div>
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
                                    <th>Service Area</th>
                                    <th>Status</th>
                                    <th>Amount to be Collect</th>
                                    <th>Collected Amount</th>
                                    <th>Delivery Charge</th>
                                    <th>COD Charge</th>
                                    <th>Weight Charge</th>
                                    <th>Return Charge</th>
                                    <th>Child Commission</th>
                                    <th>Referral Commision</th>
                                    <th>Payable Amount</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $total_customer_collect_amount = 0;
                                    $total_collected_amount = 0;
                                    $total_delivery_charge = 0;
                                    $total_cod_charge = 0;
                                    $total_weight_charge = 0;
                                    $total_return_charge = 0;
                                    $total_child_commission = 0;
                                    $total_referral_commission = 0;
                                    $total_payable = 0;
                                @endphp

                                @forelse ($parcels->get() as $parcel)
                                    @php
                                        $total_customer_collect_amount +=
                                            $parcel->customer_collect_amount + $parcel->cancel_amount_collection;
                                        $total_collected_amount += $parcel->total_collect_amount;
                                        $total_delivery_charge += $parcel->delivery_charge;
                                        $total_cod_charge += $parcel->cod_charge;
                                        $total_weight_charge += $parcel->weight_package_charge;
                                        $total_return_charge += $parcel->return_charge;
                                        $total_child_commission += $parcel->parent_commission_amount;
                                        $total_referral_commission += 0;
                                    @endphp

                                    <tr>
                                        <td>{{ $parcel->created_at }}</td>
                                        <td>{{ $parcel->parcel_invoice }}</td>
                                        <td>{{ $parcel->merchant_order_id }}</td>
                                        <td>
                                            @if ($parcel?->district?->service_area?->name == 'Inside City')
                                                ISD
                                            @elseif ($parcel?->district?->service_area?->name == 'Outside City')
                                                OSD
                                            @else
                                                SUB
                                            @endif
                                        </td>
                                        <td>{{ returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type, $parcel->parcel_invoice)['status_name'] }}
                                        </td>
                                        <td>{{ $parcel->total_collect_amount }}</td>
                                        <td>{{ $parcel->customer_collect_amount + $parcel->cancel_amount_collection }}
                                        </td>
                                        <td>{{ $parcel->delivery_charge }}</td>
                                        <td>{{ number_format($parcel->cod_charge) }}</td>
                                        <td>{{ $parcel->weight_package_charge }}</td>
                                        <td>{{ $parcel->return_charge }}</td>
                                        <td>{{ 0 }}</td>
                                        <td>{{ $parcel->parent_commission_amount ?? 0 }}</td>

                                        @php
                                            $payable_amount =
                                                $parcel->customer_collect_amount +
                                                $parcel->cancel_amount_collection -
                                                $parcel->delivery_charge -
                                                $parcel->cod_charge -
                                                $parcel->weight_package_charge -
                                                $parcel->return_charge -
                                                $parcel->parent_commission_amount;

                                            $total_payable += $payable_amount;
                                        @endphp

                                        <td>{{ $payable_amount }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tr>
                                <td colspan="5">Total</td>
                                <td>{{ $parcels->sum('total_collect_amount') }}</td>
                                <td>{{ $total_customer_collect_amount }}</td>
                                <td>{{ $total_delivery_charge }}</td>
                                <td>{{ $total_cod_charge }}</td>
                                <td>{{ $total_weight_charge }}</td>
                                <td>{{ $total_return_charge }}</td>
                                <td>{{ $total_child_commission }}</td>
                                <td>{{ $total_referral_commission }}</td>
                                <td>{{ $total_payable }}</td>
                            </tr>
                            <tfoot>

                            </tfoot>

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
