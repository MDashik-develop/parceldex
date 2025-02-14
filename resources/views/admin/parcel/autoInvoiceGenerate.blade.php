@extends('layouts.admin_layout.admin_layout')

@section('content')
    @php
        $merchants = \App\Models\Merchant::all();

        $data = [];
        $bank = 0;
        $mfs = 0;
        $cash = 0;
        $payment_method_null = 0;

        foreach ($merchants as $merchant) {
            $parcels = App\Models\Parcel::where('merchant_id', $merchant->id)
                ->whereIn('status', [21, 22, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37])
                ->where(function ($query) {
                    $query->whereNull('delivery_type')->orWhereIn('delivery_type', [1, 2, 4]);
                })
                ->where(function ($query) {
                    $query->whereNull('payment_type')->orWhereIn('payment_type', [1, 2, 3, 6]);
                });

            $total_customer_collect_amount = 0;
            $total_collected_amount = 0;
            $total_delivery_charge = 0;
            $total_cod_charge = 0;
            $total_weight_charge = 0;
            $total_return_charge = 0;
            $total_child_commission = 0;
            $total_referral_commission = 0;
            $total_payable = 0;

            if ($parcels->count() > 0) {
                $p = [];

                foreach ($parcels->get() as $parcel) {
                    $total_customer_collect_amount +=
                        $parcel->customer_collect_amount + $parcel->cancel_amount_collection;
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

                    $p[] = [
                        'id' => $parcel->id,
                        'delivery_date' => $parcel->delivery_date,
                        'parcel_invoice' => $parcel->parcel_invoice,
                        'collected' => $parcel->customer_collect_amount + $parcel->cancel_amount_collection,
                        'payable' => $payable_amount,
                        'parcel_charge' =>
                            $parcel->delivery_charge +
                            $parcel->cod_charge +
                            $parcel->weight_package_charge +
                            $parcel->return_charge +
                            $parcel->parent_commission_amount,
                        'parcel_payable' => $payable_amount,
                    ];
                }

                $data[] = [
                    'id' => $merchant->id,
                    'name' => $merchant->company_name,
                    'collected' => $total_customer_collect_amount,
                    'total_charge' =>
                        $total_delivery_charge +
                        $total_cod_charge +
                        $total_weight_charge +
                        $total_return_charge +
                        $total_child_commission +
                        $total_referral_commission,
                    'adjustment' => 0,
                    'adjustment_reason' => '',
                    'payable' => $total_payable,
                    'number_of_parcels' => $parcels->count(),
                    'parcels' => $p,
                ];

                if ($merchant->payment_recived_by == 0) {
                    $payment_method_null += $total_payable;
                } elseif ($merchant->payment_recived_by == 1) {
                    $cash += $total_payable;
                } elseif ($merchant->payment_recived_by == 5) {
                    $bank += $total_payable;
                } else {
                    $mfs += $total_payable;
                }
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
                            <td> {{ $bank }} </td>
                        </tr>
                        <tr>
                            <td>MFS</td>
                            <td>{{ $mfs }}</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
                            <td>{{ $cash }}</td>
                        </tr>
                        <tr>
                            <td>Pament Method Null</td>
                            <td>{{ $payment_method_null }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Total Payable</td>
                            <td class="font-weight-bold">{{ $bank + $mfs + $cash + $payment_method_null }}</td>
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
                                <button onclick="submitChecked()" id="approve_all">Approve All</button>
                                <input type="checkbox" id="checkbox" class="checkbox checkbox-parcel"
                                    onchange="checkAll()">
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($data as $key => $item)
                            <tr>
                                <input type="text" id="merchant-id-{{ $key }}" value="{{ $item['id'] }}"
                                    hidden>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td id="total-collected-{{ $key }}">{{ $item['collected'] }}</td>
                                <td id="total-charge-{{ $key }}">{{ $item['total_charge'] }}</td>
                                <td>
                                    <input type="number" style="max-width: 80px;" class="form-control"
                                        id="adjustment-input-{{ $key }}"
                                        oninput="adjustment({{ $key }})">
                                </td>
                                <td>
                                    <textarea style="width: 100%" id="adjustment_note-{{ $key }}"></textarea>
                                </td>
                                <td id="total-payable-{{ $key }}">{{ $item['payable'] }}</td>
                                <td id="ctotal-payable-{{ $key }}" style="display: none">{{ $item['payable'] }}
                                </td>
                                <td>
                                    <span id="parcel-count-{{ $key }}">{{ $item['number_of_parcels'] }}</span>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal-{{ $key }}">
                                        Edit
                                    </button>
                                </td>
                                <td>
                                    <button onclick="approve({{ $key }})">Approve</button>
                                    <input type="checkbox" class="checkbox" value="{{ $key }}">
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

    @foreach ($data as $key => $item)
        <!-- Modal -->
        <div class="modal fade" id="exampleModal-{{ $key }}" tabindex="-1"
            aria-labelledby="exampleModalLabel-{{ $key }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel-{{ $key }}">Parcel List</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>Order ID</td>
                                <td>Collected</td>
                                <td>Delivery Date</td>
                                <td>Delete</td>
                            </tr>
                            @foreach ($item['parcels'] as $k => $p)
                                <tr id="parcel-tr-{{ $p['parcel_invoice'] }}">
                                    <td>
                                        <input type="text" id="parcel-charge-{{ $p['parcel_invoice'] }}"
                                            value="{{ $p['parcel_charge'] }}" hidden>
                                        <input type="text" id="parcel-payable-{{ $p['parcel_invoice'] }}"
                                            value="{{ $p['parcel_payable'] }}" hidden>
                                        <input type="text" value="{{ $p['id'] }}"
                                            class="parcel-id-{{ $key }}" hidden>

                                        {{ $p['parcel_invoice'] }}
                                    </td>
                                    <td id="parcel-collected-{{ $p['parcel_invoice'] }}">{{ $p['collected'] }}</td>
                                    <td>{{ $p['delivery_date'] }}</td>
                                    <td><button
                                            onclick="removeParcel({{ $p['parcel_invoice'] }}, {{ $key }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('script_js')
        <script>
            function removeParcel(parcelId, key) {
                $('#total-collected-' + key).text(
                    $('#total-collected-' + key).text() - $('#parcel-collected-' + parcelId).text()
                );

                $('#total-charge-' + key).text($('#total-charge-' + key).text() - $('#parcel-charge-' + parcelId).val());

                $('#total-payable-' + key).text($('#total-payable-' + key).text() - $('#parcel-payable-' + parcelId).val());
                $('#ctotal-payable-' + key).text($('#ctotal-payable-' + key).text() - $('#parcel-payable-' + parcelId).val());

                $('#parcel-tr-' + parcelId).remove();
                $('#parcel-count-' + key).text($('#parcel-count-' + key).text() - 1);
            }

            function adjustment(key) {
                let a = parseInt($('#adjustment-input-' + key).val());

                if (a) {
                    $('#total-payable-' + key).text(parseInt($('#ctotal-payable-' + key).text()) + a);
                } else {
                    $('#total-payable-' + key).text(parseInt($('#ctotal-payable-' + key).text()));
                }
            }

            async function approve(key,
                return_result = false) {
                let merchantId = $('#merchant-id-' + key).val();
                let adjustment = parseInt($('#adjustment-input-' + key).val());
                let adjustment_note = $('#adjustment_note-' + key).val();
                let parcels = [];

                $('.parcel-id-' + key).each(function() {
                    let parcelId = $(this).val();
                    parcels.push(parcelId);
                });

                let response = await fetch("{{ route('admin.account.autoInvoiceGenerate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        total_payment_parcel: $('#parcel-count-' + key).text(),
                        total_payment_amount: $('#total-payable-' + key).text(),
                        merchant_id: merchantId,
                        parcels: parcels,
                        adjustment: adjustment ? adjustment : 0,
                        adjustment_note: adjustment_note
                    })
                });

                let result = await response.json();

                if (return_result) {
                    return result;
                }

                if (result.success) {
                    alert('Invoice created successfully');
                    location.reload();
                } else {
                    alert('Something went wrong');
                }
            }

            let check_toggle = false;

            function checkAll() {
                if (check_toggle == false) {
                    $('.checkbox').prop('checked', true);
                    check_toggle = true;
                } else {
                    $('.checkbox').prop('checked', false);
                    check_toggle = false;
                }
            }

            async function submitChecked() {

                // button loading and change text loading
                $('#approve_all').text('Approving...');
                $('#approve_all').prop('disabled', true);

                let checked = [];
                $('.checkbox-parcel').each(function() {
                    if ($(this).is(':checked')) {
                        checked.push($(this).val());
                    }
                });

                checked.forEach(async (key, value) => {
                    await approve(value, true)
                });

                alert('Invoice created successfully');
                location.reload();
            }
        </script>
    @endpush
@endsection
