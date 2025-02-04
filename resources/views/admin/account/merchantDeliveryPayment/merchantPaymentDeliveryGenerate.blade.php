@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Merchant Delivery Payment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Merchant Delivery Payment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" action="{{ route('admin.account.confirmMerchantDeliveryPaymentGenerate') }}"
                        method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                        @csrf
                        <input type="hidden" name="total_payment_parcel" id="total_payment_parcel" value="0">
                        <input type="hidden" name="total_payment_amount" id="total_payment_amount" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Delivery Payment</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table">
                                                <tr>
                                                    <th>Merchant </th>
                                                    <td colspan="2">
                                                        <select name="merchant_id" id="merchant_id"
                                                            class="form-control select2" style="width: 100%">
                                                            <option value="0">Select Merchant Company</option>
                                                            @foreach ($merchants as $merchant)
                                                                <option value="{{ $merchant->id }}"
                                                                    merchantName="{{ $merchant->name }}"
                                                                    merchantContactNumber="{{ $merchant->contact_number }}"
                                                                    merchantAddress="{{ $merchant->address }}">
                                                                    {{ $merchant->company_name }} </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Date</th>
                                                    <td colspan="2">
                                                        <input type="date" name="date" id="date"
                                                            value="{{ date('Y-m-d') }}" class="form-control " required>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width: 40%">Merchant Name</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_merchant_name">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Merchant Contact Number</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_merchant_contact_number">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Merchant Address</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_merchant_address">Not Confirm</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width: 40%">Payment Parcel</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_payment_parcel"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Total Payment Amount</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_payment_amount"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Adjustment Amount</th>
                                                    <td colspan="2">
                                                        <input type="number" name="adjustment" id="adjustment"
                                                            class="form-control" placeholder="Adjustment Amount">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="adjustment_note" id="adjustment_note" class="form-control" placeholder="Adjustment Note"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th> Transfer Reference</th>
                                                    <td colspan="2">
                                                        <input type="text" name="transfer_reference"
                                                            id="transfer_reference" class="form-control"
                                                            placeholder="Transfer Reference">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="note" id="note" class="form-control" placeholder="Delivery Payment Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset id="div_parcel" style="display: none">
                                                <legend>Delivery Payment Parcel </legend>
                                                <div class="row">
                                                    <div class="col-sm-12" id="show_cart_parcel">

                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success" id="PRRGenerate">Generate</button>
                                        <button type="reset" class="btn btn-primary">Reset</button>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12 row" style="margin-top: 20px;">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="parcel_invoice" id="parcel_invoice"
                                            class="form-control" placeholder="Enter Parcel Invoice Barcode"
                                            onkeypress="return add_parcel(event)"
                                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="merchant_order_id" id="merchant_order_id"
                                            class="form-control" placeholder="Enter Merchant Order ID"
                                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-info btn-block"
                                        onclick="return parcelResult()">
                                        Search
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-top: 20px;">
                                <table class="table table-bordered table-stripede"
                                    style="background-color:white;width: 100%">
                                    <thead>
                                        <tr
                                            style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th colspan="15" class="text-left">
                                                <button type="button" id="addParcelInvoice" class="btn btn-info">Add
                                                    Payment Amount</button>
                                            </th>
                                        </tr>
                                        <tr
                                            style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th width="5%" class="text-center">
                                                All <br>
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th width="10%" class="text-center">Invoice </th>
                                            <th width="10%" class="text-center">Merchant Order </th>
                                            <th width="10%" class="text-center">Company Name</th>
                                            <th width="10%" class="text-center">Customer</th>
                                            <th width="10%" class="text-center">Status</th>
                                            <th width="10%" class="text-center">Collected</th>
                                            <th width="10%" class="text-center">Weight Charge </th>
                                            <th width="10%" class="text-center">Delivery </th>
                                            <th width="5%" class="text-center">COD Charge</th>
                                            <th width="5%" class="text-center">Return Charge</th>
                                            <th width="5%" class="text-center">Child Commission</th>
                                            <th width="5%" class="text-center">Referral Commission</th>
                                            <th width="5%" class="text-center">Total Charge</th>
                                            <th width="5%" class="text-center">Payable</th>
                                        </tr>
                                    </thead>

                                    <tbody id="show_payment_parcel">
                                        @if ($parcels->count() > 0)
                                            @php
                                                $t_collected_amount = 0;
                                                $t_weight_package_charge = 0;
                                                $t_delivery_charge = 0;
                                                $t_cod_charge = 0;
                                                $t_returnCharge = 0;
                                                $t_payable_amount = 0;
                                                $t_charge = 0;
                                                $t_commissionCharge = 0;
                                            @endphp
                                            @foreach ($parcels as $parcel)
                                                @php
                                                    //  dd($parcel);
                                                    $s_charge = 0;
                                                    $s_collected = 0;
                                                    $returnCharge = 0;
                                                    if ($parcel->delivery_type == 4) {
                                                        $returnCharge = $parcel->merchant_service_area_return_charge;
                                                    } elseif (
                                                        $parcel->suborder &&
                                                        $parcel->exchange == 'yes' &&
                                                        $parcel->parent_delivery_type == 1
                                                    ) {
                                                        $returnCharge = $parcel->merchant_service_area_return_charge;
                                                    } elseif (
                                                        $parcel->suborder &&
                                                        $parcel->exchange == 'yes' &&
                                                        $parcel->parent_delivery_type == 2
                                                    ) {
                                                        $returnCharge = $parcel->merchant_service_area_return_charge;
                                                    } elseif (
                                                        $parcel->suborder &&
                                                        $parcel->exchange == 'no' &&
                                                        $parcel->parent_delivery_type == 2
                                                    ) {
                                                        $returnCharge = $parcel->merchant_service_area_return_charge;
                                                    }

                                                    $change =
                                                        $parcel->customer_collect_amount -
                                                        $parcel->weight_package_charge -
                                                        $parcel->delivery_charge;

                                                    // $payable_amount =
                                                    //     $change -
                                                    //     $parcel->cod_charge -
                                                    //     $returnCharge +
                                                    //     $parcel->cancel_amount_collection;

                                                @endphp
                                                <tr style="background-color: #f4f4f4;">
                                                    <td class="text-center">
                                                        <input type="checkbox" id="checkItem" class="parcelId"
                                                            value="{{ $parcel->id }}">
                                                    </td>
                                                    <td class="text-center">
                                                        {{-- {{ $returnCharge }},
                                                        {{ $parcel->suborder }},
                                                        {{ $parcel->delivery_type }},
                                                        {{ $parcel->parent_delivery_type }}, --}}
                                                        {{ $parcel->parcel_invoice }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->merchant_order_id }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->merchant->company_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->customer_name }} <br>
                                                        {{ $parcel->customer_contact_number }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type, $parcel->parcel_invoice)['status_name'] }}
                                                    </td>

                                                    <td class="text-right">
                                                        @if (in_array($parcel->status, [24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36]) && $parcel->delivery_type == 4)
                                                            @php
                                                                $t_collected_amount +=
                                                                    $parcel->cancel_amount_collection;

                                                                $s_collected += $parcel->cancel_amount_collection;
                                                            @endphp
                                                            {{ number_format($parcel->cancel_amount_collection, 2) }}
                                                        @elseif($parcel->status == 25 && ($parcel->delivery_type == 1 || $parcel->delivery_type == 2))
                                                            {{ number_format($parcel->customer_collect_amount, 2) }}
                                                            @php
                                                                $t_collected_amount += $parcel->customer_collect_amount;
                                                                $s_collected += $parcel->customer_collect_amount;
                                                            @endphp
                                                        @else
                                                            {{ number_format(0, 2) }}
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($parcel->weight_package_charge, 2) }} <br>
                                                        ({{ $parcel->weight_package->name }})
                                                        @php
                                                            $t_weight_package_charge += $parcel->weight_package_charge;
                                                        @endphp
                                                    </td>
                                                    <input type="hidden" id="weight_charge{{ $parcel->id }}"
                                                        value="{{ floatval($parcel->weight_package_charge) }}"
                                                        parcel_id="{{ $parcel->id }}"
                                                        class="form-control text-center weight_charge" step="any" />
                                                    <td class="text-right">
                                                        {{ number_format($parcel->delivery_charge, 2) }} <br>
                                                        @php
                                                            $t_delivery_charge += $parcel->delivery_charge;
                                                        @endphp
                                                    </td>
                                                    <input type="hidden" id="delivery_charge{{ $parcel->id }}"
                                                        value="{{ floatval($parcel->delivery_charge) }}"
                                                        parcel_id="{{ $parcel->id }}"
                                                        class="form-control text-center delivery_charge" step="any" />
                                                    <td class="text-right">
                                                        {{-- {{ number_format($parcel->cod_charge,2) }} --}}

                                                        <input type="number" id="cod_charge{{ $parcel->id }}"
                                                            value="{{ floatval($parcel->cod_charge) }}"
                                                            parcel_id="{{ $parcel->id }}"
                                                            class="form-control text-center cod_charge" step="any" />
                                                        @php
                                                            $t_cod_charge += $parcel->cod_charge;
                                                        @endphp
                                                    </td>
                                                    @php
                                                        $s_charge +=
                                                            $parcel->weight_package_charge +
                                                            $parcel->delivery_charge +
                                                            $parcel->cod_charge +
                                                            $returnCharge;

                                                        $t_charge += $s_charge;
                                                    @endphp
                                                    <td class="text-right">
                                                        <input type="number" id="return_charge{{ $parcel->id }}"
                                                            value="{{ $returnCharge }}" parcel_id="{{ $parcel->id }}"
                                                            class="form-control text-center return_charge"
                                                            step="any" />

                                                        <input type="hidden" id="total_charge_amount{{ $parcel->id }}"
                                                            value="{{ $s_charge }}" />

                                                        @php
                                                            $t_returnCharge += $returnCharge;
                                                        @endphp
                                                    </td>
                                                    <td class="text-right">

                                                        @php
                                                            $commission = $parcel->merchant->parent_merchant_commission
                                                                ? ($parcel->merchant->parent_merchant_commission *
                                                                        $parcel->customer_collect_amount) /
                                                                    100
                                                                : 0;
                                                            $t_commissionCharge += $commission;
                                                        @endphp

                                                        <input type="number" id="commission_charge{{ $parcel->id }}"
                                                            value="{{ $commission }}" parcel_id="{{ $parcel->id }}"
                                                            class="form-control text-center commission_charge"
                                                            step="any" />
                                                    </td>
                                                    <td class="text-right">
                                                        0
                                                    </td>
                                                    <td class="text-right " id="view_payable_amount{{ $parcel->id }}">

                                                        {{ number_format($s_charge, 2) }}

                                                    </td>
                                                    <input type="hidden" id="collected{{ $parcel->id }}"
                                                        value="{{ floatval($s_collected) }}"
                                                        parcel_id="{{ $parcel->id }}"
                                                        class="form-control text-center collected" step="any" />
                                                    <td class="text-right "
                                                        id="view_total_payable_amount{{ $parcel->id }}">
                                                        @php
                                                            $x = $s_collected - $s_charge;
                                                        @endphp
                                                        {{ number_format($x, 2) }}
                                                        @php
                                                            //$t_payable_amount += $payable_amount;
                                                            // $t_payable_amount += ($t_collected_amount - $t_charge);
                                                            $t_payable_amount += $x;
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="6"></td>
                                                <td id="total_collected" style="text-align: right">
                                                    {{ $t_collected_amount }}</td>
                                                <td id="total_weight_charge" style="text-align: right">
                                                    {{ $t_weight_package_charge }}</td>
                                                <td id="total_delivery_charge" style="text-align: right">
                                                    {{ $t_delivery_charge }}</td>
                                                <td id="total_cod_charge" style="text-align: right">{{ $t_cod_charge }}
                                                </td>
                                                <td id="total_return_charge" style="text-align: right">
                                                    {{ $t_returnCharge }}</td>
                                                <td id="total_commission_charge" style="text-align: right">
                                                    {{ $t_commissionCharge }}</td>
                                                <td style="text-align: right">
                                                    {{ 0 }}
                                                </td>
                                                <td id="total_charge" style="text-align: right">
                                                    {{ $t_charge }}
                                                </td>
                                                <td id="total_payable_amount" style="text-align: right">
                                                    {{ $t_payable_amount }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <style>
        .table td,
        .table th {
            padding: .1rem !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        window.onload = function() {

            $('#merchant_id').on('change', function() {
                var merchant = $("#merchant_id option:selected");
                var merchant_id = merchant.val();

                if (merchant_id == 0) {
                    $("#view_merchant_name").html('Not Confirm');
                    $("#view_merchant_contact_number").html('Not Confirm');
                    $("#view_merchant_address").html('Not Confirm');
                } else {
                    $("#view_merchant_name").html(merchant.attr('merchantName'));
                    $("#view_merchant_contact_number").html(merchant.attr('merchantContactNumber'));
                    $("#view_merchant_address").html(merchant.attr('merchantAddress'));
                }
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('admin.account.merchantDeliveryPaymentParcelClearCart') }}",
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function(response) {
                        $("#show_cart_parcel").html(response);
                        return false;
                    }
                });
            });


            $('#addParcelInvoice').on('click', function() {
                var parcel_return_charges = [];
                var parcel_cod_charges = [];
                var parcel_invoices = $('.parcelId:checkbox:checked').map(function() {
                    var invoice = this.value;
                    parcel_cod_charges.push($(`#cod_charge${invoice}`).val());
                    parcel_return_charges.push($(`#return_charge${invoice}`).val());

                    return this.value;
                }).get();

                if (parcel_invoices.length == 0) {
                    toastr.error("Please Select Parcel Invoice ");
                    return false;
                }

                var merchant_id = $("#merchant_id option:selected").val();
                if (merchant_id == 0) {
                    toastr.error("Please Select Merchant");
                    return false;
                }

                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        merchant_id: merchant_id,
                        parcel_invoices: parcel_invoices,
                        parcel_return_charges: parcel_return_charges,
                        parcel_cod_charges: parcel_cod_charges,
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('admin.account.merchantDeliveryPaymentParcelAddCart') }}",
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function(response) {
                        $("#show_cart_parcel").html(response);
                        $("#div_parcel").show();
                        $('input:checkbox').prop('checked', false);
                        return false;
                    }
                });

            });

            $('#checkAll').click(function() {
                $('input:checkbox').prop('checked', this.checked);
            });

            $(document).on("change", '.return_charge', function() {
                var parcel_id = returnNumber($(this).attr('parcel_id'));

                calculatePayableChange(parcel_id);
            });

            $(document).on("change", '.cod_charge', function() {
                var parcel_id = returnNumber($(this).attr('parcel_id'));

                calculatePayableChange(parcel_id);
            });
        }

        function calculatePayableChange(parcel_id) {
            var collected = returnNumber($(`#collected${parcel_id}`).val());
            var cod_charge = returnNumber($(`#cod_charge${parcel_id}`).val());
            var delivery_charge = returnNumber($(`#delivery_charge${parcel_id}`).val());
            var weight_charge = returnNumber($(`#weight_charge${parcel_id}`).val());
            //var total_charge_amount = returnNumber($(`#total_charge_amount${parcel_id}`).val());
            var return_charge = returnNumber($(`#return_charge${parcel_id}`).val());
            var total_charge = cod_charge + delivery_charge + weight_charge + return_charge;
            //console.log(cod_charge, delivery_charge, weight_charge, return_charge, total_charge, collected);
            $(`#view_payable_amount${parcel_id}`).text(total_charge.toFixed(2));
            var totalCollected = collected - total_charge;
            $(`#view_total_payable_amount${parcel_id}`).text(totalCollected.toFixed(2));


            let total_collected = 0;
            let total_cod_charge = 0;
            let total_return_charge = 0;
            let total_commission_charge = 0;
            let total_delivery_charge = 0;
            let total_weight_charge = 0;
            let grand_total_charge = 0;
            let grand_payable_amount = 0;

            $('.collected').each(function() {
                let value = parseFloat($(this).val()) || 0;
                total_collected += value;
            });

            $('.cod_charge').each(function() {
                let value = parseFloat($(this).val()) || 0;
                total_cod_charge += value;
            });

            $('.return_charge').each(function() {
                let value = parseFloat($(this).val()) || 0;
                total_return_charge += value;
            });

            $('.commission_charge').each(function() {
                let value = parseFloat($(this).val()) || 0;
                total_commission_charge += value;
            });

            $('.delivery_charge').each(function() {
                let value = parseFloat($(this).val()) || 0;
                total_delivery_charge += value;
            });

            $('.weight_charge').each(function() {
                let value = parseFloat($(this).val()) || 0;
                total_weight_charge += value;
            });

            grand_total_charge = total_cod_charge + total_return_charge + total_delivery_charge + total_weight_charge +
                total_commission_charge;
            grand_payable_amount = total_collected - grand_total_charge;

            $('#total_cod_charge').text(total_cod_charge.toFixed(2));
            $('#total_return_charge').text(total_return_charge.toFixed(2));
            $('#total_charge').text(grand_total_charge.toFixed(2));
            $('#total_payable_amount').text(grand_payable_amount.toFixed(2));
        }


        setInterval(function() {
            var cart_total_item = returnNumber($("#cart_total_item").val());
            var cart_total_amount = returnNumber($("#cart_total_amount").val());
            $("#view_total_payment_parcel").html(cart_total_item);
            $("#total_payment_parcel").val(cart_total_item);

            $("#view_total_payment_amount").html(cart_total_amount);
            $("#total_payment_amount").val(cart_total_amount);
        }, 300);

        function add_parcel(event) {
            if (event.which == 13) {
                parcelResult();
                return false;
            }
        }

        function delete_parcel(itemId) {
            $.ajax({
                cache: false,
                type: 'POST',
                data: {
                    itemId: itemId,
                    _token: "{{ csrf_token() }}",
                },
                url: "{{ route('admin.account.merchantDeliveryPaymentParcelDeleteCart') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $('#show_cart_parcel').html(response);
                }
            });
        }

        function parcelResult() {
            var parcel_invoice = $("#parcel_invoice").val();
            var merchant_order_id = $("#merchant_order_id").val();
            var merchant_id = $("#merchant_id option:selected").val();

            if (merchant_id == 0) {
                toastr.error("Please Select Merchant");
                return false;
            }
            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    merchant_id: merchant_id,
                    parcel_invoice: parcel_invoice,
                    merchant_order_id: merchant_order_id,
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ route('admin.account.returnMerchantDeliveryPaymentParcel') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $("#show_payment_parcel").html(response);
                    $("#parcel_invoice").val('');
                    $("#merchant_order_id").val('');
                    return false;
                }
            });
        }


        function createForm() {
            $('#PRRGenerate').attr('disabled', true);
            let total_payment_parcel = returnNumber($('#total_payment_parcel').val());
            if (total_payment_parcel == 0) {
                toastr.error("Please Enter Delivery Payment Parcel..");
                return false;
            }
        }
    </script>
@endpush
