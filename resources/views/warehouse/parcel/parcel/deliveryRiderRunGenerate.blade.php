@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Generate Delivery Rider Run </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Generate Delivery Rider Run </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" action="{{ route('branch.parcel.confirmDeliveryRiderRunGenerate') }}"
                        method="POST" enctype="multipart/form-data" onsubmit="return createForm()">
                        @csrf
                        <input type="hidden" name="total_run_parcel" id="total_run_parcel" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>Delivery Run </legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table ">
                                                <tr>
                                                    <th>Rider</th>
                                                    <td colspan="2">
                                                        <select name="rider_id" id="rider_id" class="form-control select2"
                                                            style="width: 100%">
                                                            <option value="0">Select Rider </option>
                                                            @foreach ($riders as $rider)
                                                                <option value="{{ $rider->id }}"
                                                                    riderContactNumber="{{ $rider->contact_number }}"
                                                                    riderAddress="{{ $rider->address }}">
                                                                    {{ $rider->name }} </option>
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
                                                    <th style="width: 40%">Rider Name</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_rider_name">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Rider Contact Number</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_rider_contact_number">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%">Rider Address</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_rider_address">Not Confirm</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40%"> Total Run Parcel</th>
                                                    <td style="width: 5%"> : </td>
                                                    <td style="width: 55%">
                                                        <span id="view_total_run_parcel"> 0 </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea name="note" id="note" class="form-control" placeholder="Delivery Rider Run Note "></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <fieldset id="div_rider_run_parcel" style="display: none">
                                                <legend>Delivery Run Parcel </legend>
                                                <div class="row">
                                                    <div class="col-sm-12" id="show_rider_run_parcel">

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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="parcel_invoice_barcode" id="parcel_invoice_barcode"
                                            class="form-control" placeholder="Enter Parcel Invoice Barcode"
                                            onkeypress="return add_parcel(event)"
                                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control"
                                            placeholder="Enter Parcel Invoice"
                                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                            <th colspan="7" class="text-left">
                                                <button type="button" id="addParcelInvoice" class="btn btn-info">Add
                                                    Parcel to Run</button>
                                            </th>
                                        </tr>
                                        <tr
                                            style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                            <th width="10%" class="text-center">
                                                Select All <br>
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th width="15%" class="text-center">Invoice </th>
                                            <th width="15%" class="text-center">Merchant Order </th>
                                            <th width="15%" class="text-center">Merchant Name</th>
                                            <th width="15%" class="text-center">Merchant Number</th>
                                            <th width="15%" class="text-center">Customer Name</th>
                                            <th width="15%" class="text-center">Customer Number</th>
                                        </tr>
                                    </thead>
                                    <tbody id="show_parcel">
                                        @if ($parcels->count() > 0)
                                            @foreach ($parcels as $parcel)
                                                <tr style="background-color: #f4f4f4;">
                                                    <td class="text-center">
                                                        <input type="checkbox" id="checkItem" class="parcelId"
                                                            value="{{ $parcel->id }}">
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->parcel_invoice }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->merchant_order_id }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->merchant->name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->merchant->contact_number }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->customer_name }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $parcel->customer_contact_number }}
                                                    </td>
                                                </tr>
                                            @endforeach
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
            $('#rider_id').on('change', function() {
                var rider = $("#rider_id option:selected");
                var rider_id = rider.val();

                if (rider_id == 0) {
                    $("#view_rider_name").html('Not Confirm');
                    $("#view_rider_contact_number").html('Not Confirm');
                    $("#view_rider_address").html('Not Confirm');
                } else {
                    $("#view_rider_name").html(rider.text());
                    $("#view_rider_contact_number").html(rider.attr('riderContactNumber'));
                    $("#view_rider_address").html(rider.attr('riderAddress'));
                }

            });
            $('#addParcelInvoice').on('click', function() {
                var parcel_invoices = $('.parcelId:checkbox:checked').map(function() {
                    return this.value;
                }).get();
                if (parcel_invoices.length == 0) {
                    toastr.error("Please Select Parcel Invoice ");
                    return false;
                }
                $.ajax({
                    cache: false,
                    type: "POST",
                    data: {
                        parcel_invoices: parcel_invoices,
                        _token: "{{ csrf_token() }}"
                    },
                    url: "{{ route('branch.parcel.deliveryRiderRunParcelAddCart') }}",
                    error: function(xhr) {
                        alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                    },
                    success: function(response) {
                        $("#show_rider_run_parcel").html(response);
                        $("#div_rider_run_parcel").show();
                        $('input:checkbox').prop('checked', false);
                        return false;
                    }
                });

            });

            $('#checkAll').click(function() {
                $('input:checkbox').prop('checked', this.checked);
            });
        }


        setInterval(function() {
            var cart_total_item = returnNumber($("#cart_total_item").val());
            $("#view_total_run_parcel").html(cart_total_item);
            $("#total_run_parcel").val(cart_total_item);
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
                url: "{{ route('branch.parcel.deliveryRiderRunParcelDeleteCart') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $('#show_rider_run_parcel').html(response);
                }
            });
        }

        function parcelResult() {
            var parcel_invoice_barcode = $("#parcel_invoice_barcode").val();
            var parcel_invoice = $("#parcel_invoice").val();
            var merchant_order_id = $("#merchant_order_id").val();
            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    parcel_invoice_barcode: parcel_invoice_barcode,
                    parcel_invoice: parcel_invoice,
                    merchant_order_id: merchant_order_id,
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ route('branch.parcel.returnDeliveryRiderRunParcel') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $("#show_parcel").html(response);

                    $("#parcel_invoice_barcode").val('');
                    $("#parcel_invoice").val('');
                    $("#merchant_order_id").val('');
                    return false;
                }
            });
        }


        function createForm() {
            $('#PRRGenerate').attr('disabled', true);
            let rider_id = $('#rider_id').val();
            if (rider_id == '0') {
                toastr.error("Please Select Rider..");
                return false;
            }

            let total_run_parcel = returnNumber($('#total_run_parcel').val());
            if (total_run_parcel == 0) {
                toastr.error("Please Enter Delivery Run Parcel..");
                return false;
            }
        }
    </script>
@endpush
