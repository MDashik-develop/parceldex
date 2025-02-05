@extends('layouts.admin_layout.admin_layout')

@section('content')
    @php
        $merchants = \App\Models\Merchant::all();

        $data = [];

        foreach ($merchants as $merchant) {
            $parcels = App\Models\Parcel::where('merchant_id', $merchant->id)
                ->whereIn('status', [21, 22, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37])
                ->where(function ($query) {
                    $query->whereNull('delivery_type')->orWhereIn('delivery_type', [1, 2, 4]);
                })
                ->where(function ($query) {
                    $query->whereNull('payment_type')->orWhereIn('payment_type', [1, 2, 3, 6]);
                });

            if ($parcels->count() > 0) {
                $data[] = [
                    'id' => $merchant->id,
                    'name' => $merchant->name,
                    'collected' => 200000,
                    'total_charge' => 9000,
                    'adjustment' => 0,
                    'adjustment_reason' => '',
                    'payable' => 191000,
                    'number_of_parcels' => $parcels->count(),
                ];
            }
        }
    @endphp

    <div class="content">
        <div class="container-fluid">

            <h1 class="mb-4">Auto Invoice Create</h1>

            <div class="mb-5">
                <table class="table table-bordered table-striped" style="max-width: 500px;">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-light2 font-weight-bold">Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bank</td>
                            <td>2,00,000</td>
                        </tr>
                        <tr>
                            <td>MFS</td>
                            <td>23,000</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td>10,00,000</td>
                        </tr>
                        <tr>
                            <td>Pament Method Null</td>
                            <td>6,000</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Total Payable</td>
                            <td class="font-weight-bold">12,29,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="bg-light2">SL</th>
                            <th class="bg-light2">Merchant Name</th>
                            <th class="bg-light2">Collected</th>
                            <th class="bg-light2">Total Charge</th>
                            <th class="bg-light2">Adjustment</th>
                            <th class="bg-light2">Adjustment Reason</th>
                            <th class="bg-light2">Payable</th>
                            <th class="bg-light2">Number of Parcels</th>
                            <th class="bg-light2">
                                <button>Approve All</button>
                                <input type="checkbox">
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($data as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['collected'] }}</td>
                                <td>{{ $item['total_charge'] }}</td>
                                <td>
                                    <input type="number" style="max-width: 80px;" class="form-control">
                                </td>
                                <td>
                                    <textarea name="" id="" style="width: 100%"></textarea>
                                </td>
                                <td>{{ $item['payable'] }}</td>
                                <td>{{ $item['number_of_parcels'] }} (eidt)</td>
                                <td>
                                    <button>Approve</button>
                                    <input type="checkbox">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No data found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
