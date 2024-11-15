@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Generate Delivery Rider Run</h1>
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
                                                                {{-- @if ($rider->rider_runs->count() == 0) --}}
                                                                <option value="{{ $rider->id }}"
                                                                    riderContactNumber="{{ $rider->contact_number }}"
                                                                    riderAddress="{{ $rider->address }}">
                                                                    {{ $rider->name }} </option>
                                                                {{-- @elseif($rider->rider_runs->count() > 0) --}}
                                                                {{-- @foreach ($rider->rider_runs as $rider_run) --}}
                                                                {{-- @if ($loop->iteration == 1 && ($rider_run->status == 3 || $rider_run->status == 4)) --}}
                                                                {{-- <option --}}
                                                                {{-- value="{{ $rider->id }}" --}}
                                                                {{-- riderContactNumber="{{ $rider->contact_number }}" --}}
                                                                {{-- riderAddress="{{ $rider->address }}" --}}
                                                                {{-- > {{ $rider->name }} </option> --}}
                                                                {{-- @endif --}}
                                                                {{-- @endforeach --}}
                                                                {{-- @endif --}}
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


                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12 row" style="margin-top: 20px;">
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{-- <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control" placeholder="Enter Parcel Invoice Barcode" onkeypress="return scanParcelAdd(event)" style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118); --}}
                                    {{-- padding: 3px 0px 3px 3px; --}}
                                    {{-- margin: 5px 1px 3px 0px; --}}
                                    {{-- border: 1px solid rgb(62, 196, 118);"> --}}
                                    <input type="text" name="parcel_invoice" id="parcel_invoice" class="form-control"
                                        placeholder="Enter Parcel Invoice Barcode"
                                        style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" name="merchant_order_id" id="merchant_order_id"
                                        class="form-control" placeholder="Enter Merchant Order ID / Customer Number"
                                        onkeypress="return add_parcel(event)"
                                        style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-info btn-block" onclick="return parcelResult()">
                                    Search
                                </button>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top: 20px;">
                            <table class="table table-bordered table-striped table-responsive"
                                style="background-color:white;width: 100%">
                                <thead>
                                    <tr
                                        style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                        <th colspan="12" class="text-left">
                                            <button type="button" id="addParcelInvoice" class="btn btn-info">Add Parcel
                                                to Run</button>
                                        </th>
                                    </tr>
                                    <tr
                                        style="background-color: #a2bbca !important; font-family: Arial Black;font-size: 14px">
                                        <th width="5%" class="text-center">
                                            All <br>
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th width="12%" class="text-center">Invoice </th>
                                        <th width="8%" class="text-center">M Order ID </th>
                                        <th width="12%" class="text-center">Company Name</th>
                                        <!--<th width="15%" class="text-center">Merchant Name </th>-->
                                        <th width="10%" class="text-center">Merchant Number</th>
                                        <th width="12%" class="text-center">Customer Name</th>
                                        <th width="10%" class="text-center">Customer Number</th>
                                        <th width="15%" class="text-center">Customer Address</th>

                                        <th width="8%" class="text-center"> Collection Amount</th>
                                        {{--                            <th width="8%" class="text-center"> COD Charge</th> --}}
                                        <th width="8%" class="text-center"> Total Charge</th>
                                    </tr>
                                </thead>
                                <tbody id="show_parcel">
                                    @if ($parcels->count() > 0)
                                        @foreach ($parcels as $parcel)
                                            <tr style="background-color: #f4f4f4;" class="parclTR"
                                            data-parcel_invoice="{{ $parcel->parcel_invoice }}"
                                            data-parcel_id="{{ $parcel->id }}">
                                                <td class="text-center">
                                                    <input type="checkbox" id="checkItem" class="parcelId"
                                                        value="{{ $parcel->id }}">
                                                </td>


                                                @php
                                                    $date =
                                                        date('Y-m-d H:i:s') >=
                                                            date(
                                                                'Y-m-d H:i:s',
                                                                strtotime('+72 hours', strtotime($parcel->created_at)),
                                                            ) && $parcel->status < 25;
                                                @endphp


                                                @if ($date) <td class="text-center" style="    background: #e55555;
                                           
                                           
                                       
                                            padding: 4px 12px;
                                        
                                            color: #fff;" >
                                                {{ $parcel->parcel_invoice }}
                                            </td>
                                        @else
                                            <td class="text-center" >
                                                {{ $parcel->parcel_invoice }}
                                            </td> @endif





                                                <td class="text-center">
                                                    {{ $parcel->merchant_order_id ?? '---' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $parcel->merchant->company_name }}
                                                </td>
                                                <!--<td class="text-center" >-->
                                                <!--    {{ $parcel->merchant->name }}-->
                                                <!--</td>-->
                                                <td class="text-center">
                                                    {{ $parcel->merchant->contact_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $parcel->customer_name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $parcel->customer_contact_number }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $parcel->customer_address }}
                                                </td>
                                                <td class="text-center">{{ $parcel->total_collect_amount }}</td>
                                                {{--                                        <td class="text-center" >{{$parcel->cod_charge}}</td> --}}
                                                <td class="text-center">{{ $parcel->total_charge }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                console.log(parcel_invoices);

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
                        parcel_invoices.forEach(element => {
                            $('.parclTR').each(function() {
                                if ($(this).data('parcel_id') == element) {
                                    $(this)
                                        .hide(); // Hide the row if the data attribute matches
                                }
                            });
                        });
                        return false;
                    }
                });

            });

            $('#checkAll').click(function() {
                $('input:checkbox').prop('checked', this.checked);
            });

            $("#parcel_invoice").on("trigger change", function() {

                var invoice_id = $(this).val();

                var invoice_ids = [invoice_id];

                if (invoice_id != "") {

                    $.ajax({
                        cache: false,
                        type: "POST",
                        data: {
                            parcel_invoices: invoice_ids,
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('branch.parcel.deliveryRiderRunParcelAddCart') }}",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        success: function(response) {
                            $("#show_rider_run_parcel").html(response);
                            $("#div_rider_run_parcel").show();
                            $("#parcel_invoice").val("");
                            $('.parclTR').each(function() {
                                if ($(this).data('parcel_invoice') == invoice_id) {
                                    $(this)
                                        .hide(); // Hide the row if the data attribute matches
                                }
                            });
                            return false;
                        }
                    });
                }


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
                    $('.parclTR').each(function() {
                        if ($(this).data('parcel_id') == itemId) {
                            $(this).show(); // Hide the row if the data attribute matches
                        }
                    });
                }
            });
        }

        function parcelResult() {
            var parcel_invoice = $("#parcel_invoice").val();
            var merchant_order_id = $("#merchant_order_id").val();
            $.ajax({
                cache: false,
                type: "POST",
                data: {
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
