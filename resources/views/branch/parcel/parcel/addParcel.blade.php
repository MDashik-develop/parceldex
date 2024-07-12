@extends('layouts.branch_layout.branch_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Add Parcel</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add Parcel</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Add New Parcel </h3>

                            <div class="card-tools">


                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>

                                <a href="{{ route('branch.parcel.merchantBulkParcelImport') }}"
                                    class="btn btn-success float-right" style="margin-right: 20px;">
                                    <i class="fas fa-file-excel"></i> Merchant Bulk Parcel Import
                                </a>
                            </div>


                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <form role="form" action="{{ route('branch.parcel.store') }}" method="POST"
                                    enctype="multipart/form-data" onsubmit="return createForm()">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <fieldset>
                                                        <legend>Merchant Information</legend>
                                                        <div class="form-group">
                                                            <label for="merchant_id"> Merchant</label>
                                                            <select name="merchant_id" id="merchant_id"
                                                                class="form-control select2" style="width: 100%" required>
                                                                <option value="0">Select Company</option>
                                                                @foreach ($merchants as $merchant)
                                                                    <option value="{{ $merchant->id }}">
                                                                        {{ $merchant->company_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <table class="table merchant_info">
                                                            <tr>
                                                                <th style="width: 40%">Merchant Company </th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%"> </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Merchant Name</th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%"> </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Merchant Contact </th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%"> </td>
                                                            </tr>
                                                        </table>
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12">
                                                    <fieldset>
                                                        <legend>Customer Information </legend>
                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="customer_contact_number">Customer Contact
                                                                        Number <code>*</code></label>
                                                                    <input type="text" name="customer_contact_number"
                                                                        id="customer_contact_number"
                                                                        value="{{ old('customer_contact_number') }}"
                                                                        class="form-control"
                                                                        placeholder="Customer Contact Number" required>
                                                                </div>
                                                            </div>

                                                            {{-- <span id="complete" style="margin-right: 5px; margin-left: 20px;">   </span> <span id="p_complete" style="margin-right: 10px;"></span> 
                                            
                                                            <span id="pending" style="margin-right: 5px;"></span> <span id="p_pending" style="margin-right: 10px;"></span> 
                                            
                                                            <span id="cancel" style="margin-right: 5px;"></span> <span id="p_cancel"></span>  --}}
                                                            <span class="font-weight-bold text-success" id="complete"
                                                                style="margin-right: 5px; margin-left: 20px;"> </span> <span
                                                                class="font-weight-bold text-success" id="p_complete"
                                                                style="margin-right: 10px;"></span>

                                                            <span class="font-weight-bold text-warning" id="pending"
                                                                style="margin-right: 5px;"></span> <span
                                                                class="font-weight-bold text-warning" id="p_pending"
                                                                style="margin-right: 10px;"></span>

                                                            <span class="font-weight-bold text-danger" id="cancel"
                                                                style="margin-right: 5px;"></span> <span
                                                                class="font-weight-bold text-danger" id="p_cancel"></span>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="customer_contact_number2">Customer
                                                                        Alternative
                                                                        Contact Number</label>
                                                                    <input type="text" name="customer_contact_number2"
                                                                        id="customer_contact_number2"
                                                                        value="{{ old('customer_contact_number2') }}"
                                                                        class="form-control"
                                                                        placeholder="Customer Alternative Contact Number">
                                                                </div>
                                                            </div>

                                                            <span class="font-weight-bold text-success" id="complete2"
                                                                style="margin-right: 5px; margin-left: 20px;"> </span> <span
                                                                class="font-weight-bold text-success" id="p_complete2"
                                                                style="margin-right: 10px;"></span>

                                                            <span class="font-weight-bold text-warning" id="pending2"
                                                                style="margin-right: 5px;"></span> <span
                                                                class="font-weight-bold text-warning" id="p_pending2"
                                                                style="margin-right: 10px;"></span>

                                                            <span class="font-weight-bold text-danger" id="cancel2"
                                                                style="margin-right: 5px;"></span> <span
                                                                class="font-weight-bold text-danger"
                                                                id="p_cancel2"></span>


                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="customer_name">Customer Name
                                                                        <code>*</code></label>
                                                                    <input type="text" name="customer_name"
                                                                        id="customer_name"
                                                                        value="{{ old('customer_name') }}"
                                                                        class="form-control" placeholder="Customer Name"
                                                                        required>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="customer_address">Customer Address
                                                                        <code>*</code></label>
                                                                    <input type="text" name="customer_address"
                                                                        id="customer_address"
                                                                        value="{{ old('customer_address') }}"
                                                                        class="form-control"
                                                                        placeholder="Customer Address" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="district_id"> Districts
                                                                        <code>*</code></label>
                                                                    <select name="district_id" id="district_id"
                                                                        class="form-control select2" style="width: 100%"
                                                                        disabled>
                                                                        <option value="0">Select District</option>
                                                                        @foreach ($districts as $district)
                                                                            <option value="{{ $district->id }}">
                                                                                {{ $district->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-md-4 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="upazila_id"> Thana/Upazila  <code>*</code></label>
                                                                    <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%" disabled>
                                                                        <option value="0">Select Thana/Upazila</option>
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="area_id"> Area <code></code> </label>
                                                                    <select name="area_id" id="area_id"
                                                                        class="form-control select2" style="width: 100%"
                                                                        disabled>
                                                                        <option value="0">Select Area</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-12">
                                                    <fieldset>
                                                        <legend>Parcel Information </legend>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="merchant_order_id">Merchant Order ID
                                                                    </label>
                                                                    <input type="text" name="merchant_order_id"
                                                                        id="merchant_order_id"
                                                                        value="{{ old('merchant_order_id') }}"
                                                                        class="form-control"
                                                                        placeholder="Merchant Order ID">
                                                                </div>
                                                            </div>

                                                            <!--<div class="col-md-6">-->
                                                            <!--    <div class="form-group">-->
                                                            <!--        <label for="shop_id"> Select Shop <code></code> </label>-->
                                                            <!--        <select name="shop_id" id="shop_id" class="form-control select2" style="width: 100%" disabled>-->
                                                            <!--            <option value="0">---- Select ----</option>-->
                                                            <!--        </select>-->

                                                            <!--    </div>-->
                                                            <!--</div>-->

                                                            {{-- <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="select_pickup_address"> Select Pickup Address </label>
                                                                    <select name="select_pickup_address" id="select_pickup_address" class="form-control select2" style="width: 100%" disabled>
                                                                        <option value="0">---- Select ----</option>
                                                                        <option value="1">Business Address</option>
                                                                        <option value="2">Full Address </option>
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                            {{-- <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="pickup_address">Pickup Address </label>
                                                                    <input type="text" name="pickup_address" id="pickup_address" value="{{ old('pickup_address') }}" class="form-control" placeholder="Pickup Address" required>
                                                                </div>
                                                            </div> --}}

                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="service_type_id"> Service Type
                                                                        <code></code></label>
                                                                    <select name="service_type_id" id="service_type_id"
                                                                        class="form-control select2" style="width: 100%"
                                                                        disabled>
                                                                        <option value="0">Select Service Type</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="item_type_id"> Item Type
                                                                        <code>*</code></label>
                                                                    <select name="item_type_id" id="item_type_id"
                                                                        class="form-control select2" style="width: 100%"
                                                                        disabled>
                                                                        <option value="0">Select Item Type</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="weight_package_id"> Weight Package
                                                                        <code>*</code></label>
                                                                    <select name="weight_package_id"
                                                                        id="weight_package_id"
                                                                        class="form-control select2" style="width: 100%"
                                                                        disabled>
                                                                        <option value="0" data-charge="0">Select
                                                                            Weight Package </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-md-6 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="delivery_option_id"> Delivery Option  <code>*</code></label>
                                                                    <select name="delivery_option_id" id="delivery_option_id" class="form-control select2" style="width: 100%">
                                                                        <option value="1">Cash On Delivery</option>
                                                                        <option value="2">Bkash </option>
                                                                        <option value="3">Bank </option>
                                                                        <option value="4">Card </option>
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                            {{-- <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="product_details">Product(s) Brief  <code>*</code></label>
                                                                    <input type="text" name="product_details" id="product_details" value="{{ old('product_details') }}" class="form-control" placeholder="Product Details " required>
                                                                </div>
                                                            </div> --}}
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="total_collect_amount">Total Collection
                                                                        Amount</label>
                                                                    <input type="number" name="total_collect_amount"
                                                                        id="total_collect_amount"
                                                                        value="{{ old('total_collect_amount') }}"
                                                                        class="form-control" placeholder="0.00">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="product_value">Product
                                                                        Value<code>*</code></label>
                                                                    <input type="number" name="product_value"
                                                                        id="product_value"
                                                                        value="{{ old('product_value') }}"
                                                                        class="form-control" placeholder="1200.00"
                                                                        min="1" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="weight_package_id">Exchange
                                                                        <code>*</code> </label>
                                                                    <select name="exchange" class="form-control select2"
                                                                        style="width: 100%">
                                                                        <option value="yes">Yes
                                                                        </option>
                                                                        <option selected value="no">No
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="product_details">Product
                                                                        Details<code>*</code></label>
                                                                    <input type="text" name="product_details"
                                                                        id="product_details"
                                                                        value="{{ old('product_details') }}"
                                                                        class="form-control"
                                                                        placeholder="product details">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="parcel_note">Remark</label>
                                                                    <textarea name="parcel_note" id="parcel_note" class="form-control" placeholder="Parcel Remark"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="col-md-12 text-center">
                                                    <input type="hidden" name="select_pickup_address"
                                                        id="select_pickup_address" value="1">
                                                    <input type="hidden" name="delivery_option_id"
                                                        id="delivery_option_id" value="1">
                                                    <input type="hidden" name="pickup_address" id="pickup_address"
                                                        value="">

                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                    <button type="reset" class="btn btn-primary">Reset</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <fieldset>
                                                        <legend>Parcel Charge </legend>
                                                        <table class="table ">
                                                            <tr>
                                                                <th style="width: 40%">Weight Package</th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_weight_package">Not Confirm </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Service Type</th>
                                                                <td style="width: 10%"> :</td>
                                                                <td style="width: 50%">
                                                                    <span id="view_service_type">Not Confirm </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Item Type</th>
                                                                <td style="width: 10%"> :</td>
                                                                <td style="width: 50%">
                                                                    <span id="view_item_type">Not Confirm </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Collection Amount </th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_collection_amount">0.00</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Cod Percent </th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_cod_percent">
                                                                        0 %
                                                                    </span>
                                                                    <input type="hidden" id="confirm_cod_percent"
                                                                        name="cod_percent" value="0">
                                                                    <input type="hidden"
                                                                        id="confirm_merchant_cod_percent" value="0">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Weight Charge </th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_weight_package_charge">0.00</span>
                                                                    <input type="hidden"
                                                                        id="confirm_weight_package_charge"
                                                                        name="weight_package_charge" value="0">
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th style="width: 40%">Cod Charge</th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_cod_charge">0.00</span>
                                                                    <input type="hidden" id="confirm_cod_charge"
                                                                        name="cod_charge" value="0">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Delivery Charge</th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_delivery_charge">0.00</span>
                                                                    <input type="hidden" id="confirm_delivery_charge"
                                                                        name="delivery_charge" value="0">
                                                                    <input type="hidden"
                                                                        id="confirm_merchant_service_area_charge"
                                                                        name="merchant_service_area_charge"
                                                                        value="0">
                                                                    <input type="hidden"
                                                                        id="confirm_merchant_service_area_return_charge"
                                                                        name="merchant_service_area_return_charge"
                                                                        value="0">
                                                                    <input type="hidden"
                                                                        id="only_merchant_service_area_charge"
                                                                        name="only_merchant_service_area_charge"
                                                                        value="0">

                                                                    <input type="hidden" id="item_type_charge"
                                                                        name="item_type_charge" value="0">
                                                                    <input type="hidden" id="service_type_charge"
                                                                        name="service_type_charge" value="0">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th style="width: 40%">Total Charge </th>
                                                                <td style="width: 10%"> : </td>
                                                                <td style="width: 50%">
                                                                    <span id="view_total_charge">0.00</span>
                                                                    <input type="hidden" id="confirm_total_charge"
                                                                        name="total_charge" value="0">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </fieldset>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
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


    <!-- For getting customer Info -->
    <script>
        $('#customer_contact_number').on('input', function() {

            var phone = $("#customer_contact_number").val();

            $.ajax({
                type: "GET",
                url: ("{{ route('merchant.customer.info') }}"),
                data: {
                    phone: phone,
                },
                success: function(response) {

                    if (response.customerParcel == 0) {
                        $('#p_complete').html("");
                        $('#pending').html("");
                        $('#p_pending').html("");
                        $('#cancel').html("");
                        $('#p_cancel').html("");
                        $('#complete').html("New Customer");
                    } else {
                        $('#complete').html(response.totalDeliveryComplete);
                        $('#p_complete').html(response.percenrtComplete);
                        $('#pending').html(response.totalDeliveryPending);
                        $('#p_pending').html(response.percenrtPending);
                        $('#cancel').html(response.totalDeliveryCancel);
                        $('#p_cancel').html(response.percenrtCancel);
                    }
                    $('#customer_name').val(response.customer.customer_name);
                    $('#customer_address').val(response.customer.customer_address);
                    $('#customer_details').removeClass('d-none');


                }
            });
        });


        $('#customer_contact_number2').on('input', function() {

            var phone = $("#customer_contact_number2").val();

            $.ajax({
                type: "GET",
                url: ("{{ route('merchant.customer.info') }}"),
                data: {
                    phone2: phone,
                },
                success: function(response) {

                    if (response.customerParcel == 0) {
                        $('#p_complete2').html("");
                        $('#pending2').html("");
                        $('#p_pending2').html("");
                        $('#cancel2').html("");
                        $('#p_cancel2').html("");
                        $('#complete2').html("New Customer");
                        //   $('#district_id2').val(0).change();
                        //   $('#area_id2').val(0).change();
                    } else {
                        $('#complete2').html(response.totalDeliveryComplete);
                        $('#p_complete2').html(response.percenrtComplete);
                        $('#pending2').html(response.totalDeliveryPending);
                        $('#p_pending2').html(response.percenrtPending);
                        $('#cancel2').html(response.totalDeliveryCancel);
                        $('#p_cancel2').html(response.percenrtCancel);
                    }

                    // $('#customer_name').val(response.customer.customer_name);
                    // $('#customer_address').val(response.customer.customer_address);
                    // $('#district_id').val(response.customer.district_id).change();
                    // $('#customer_details').removeClass('d-none');
                    // setTimeout(function() {
                    //     $('#area_id').val(response.customer.area_id).change();
                    // }, 500);
                }
            });
        });
    </script>
    <!-- For getting customer Info -->


    <!-- For getting customer Info -->
    <!--<script>
        -- >
        <
        !--$('#customer_contact_number').on('change', function() {
            -- >

            <
            !--
            var phone = $("#customer_contact_number").val();
            -- >

            <
            !--$.ajax({
                -- >
                <
                !--type: "GET",
                -- >
                <
                !--url: ("{{ route('merchant.customer.info') }}"),
                -- >
                <
                !--data: {
                    -- >
                    <
                    !--phone: phone,
                    -- >
                    <
                    !--
                },
                -- >
                <
                !--success: function(response) {
                        -- >
                        <
                        !--$('#customer_name').val(response.customer_name);
                        -- >
                        <
                        !--$('#customer_address').val(response.customer_address);
                        -- >
                        <
                        !--
                    }-- >
                    <
                    !--
            });
            -- >
            <
            !--
        });
        -- >
        <
        !--
    </script>-->
    <!-- For getting customer Info -->


    <script>
        window.onload = function() {

            $("#merchant_id").on("change", function() {
                var mid = $(this).val();
                var html = "";
                $("#district_id").val(0).change().attr('disabled', true);
                $("#select_pickup_address").val(0).change().attr('disabled', true);

                if (mid != "" && mid != 0) {
                    $.ajax({
                        cache: false,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            merchant_id: mid,
                            _token: "{{ csrf_token() }}"
                        },
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: "{{ route('branch.getMerchantInfo') }}",
                        success: function(response) {
                            if (response.success) {

                                $("#district_id").val(0).attr('disabled', false);
                                $("#pickup_address").val(response.merchant_data.address);
                                $("#select_pickup_address").val(0).attr('disabled', false);
                                $("#shop_id").html(response.merchant_shop_option).val(0).attr(
                                    'disabled', false);

                                html += "<tr>\n" +
                                    "<th style=\"width: 40%\">Merchant Company </th>\n" +
                                    "<td style=\"width: 10%\"> : </td>\n" +
                                    "<td style=\"width: 50%\"> " +
                                    "<input type=\"hidden\" id=\"merchant_full_address\" value=\" " +
                                    response.merchant_data.address + "\">" +
                                    "<input type=\"hidden\" id=\"merchant_business_address\" value=\"" +
                                    response.merchant_data.business_address + "\">" +
                                    "" + response.merchant_data.company_name + " </td>\n" +
                                    "</tr>\n" +
                                    "<tr>\n" +
                                    "<th style=\"width: 40%\">Merchant Name</th>\n" +
                                    "<td style=\"width: 10%\"> : </td>\n" +
                                    "<td style=\"width: 50%\"> " + response.merchant_data.name +
                                    " </td>\n" +
                                    "</tr>\n" +
                                    "<tr>\n" +
                                    "<th style=\"width: 40%\">Merchant Contact </th>\n" +
                                    "<td style=\"width: 10%\"> : </td>\n" +
                                    "<td style=\"width: 50%\"> " + response.merchant_data
                                    .contact_number + " </td>\n" +
                                    "</tr>";

                                $(".merchant_info").html(html);

                                var cod_val = response.merchant_data.cod_charge;
                                if (cod_val !== null && cod_val !== 0 && "" !== cod_val) {
                                    $("#confirm_cod_percent").val(cod_val);
                                    $("#view_cod_percent").html(cod_val + "%");
                                } else {
                                    $("#confirm_cod_percent").val(0);
                                    $("#view_cod_percent").html(0 + "%");
                                }
                            } else {
                                toastr.error("something is wrong");
                                $("#district_id").val(0).attr('disabled', true);
                                // $("#upazila_id").val(0).attr('disabled', true);
                                $("#select_pickup_address").val(0).attr('disabled', true);
                                $("#weight_package_id").val(0).attr('disabled', true);
                                $("#area_id").val(0).attr('disabled', true);
                            }
                        }
                    });
                } else {
                    $("#district_id").val(0).attr('disabled', true);
                    // $("#upazila_id").val(0).attr('disabled', true);
                    $("#weight_package_id").val(0).attr('disabled', true);
                    $("#area_id").val(0).attr('disabled', true);
                }

            });


            $('#district_id').on('change', function() {
                $("#view_delivery_charge").html(0);
                $("#delivery_charge").val(0);
                $("#confirm_weight_package_charge").val(0);
                $("#confirm_merchant_service_area_charge").val(0);
                $("#view_service_type").html("Not Confirm");
                $("#view_item_type").html("Not Confirm");

                // $("#upazila_id").val(0).attr('disabled', true);
                $("#area_id").val(0).attr('disabled', true);
                $("#weight_package_id").val(0).change().attr('disabled', true);

                var merchant_id = $("#merchant_id option:selected").val();
                var district_id = $("#district_id option:selected").val();
                var cod_percent = $("#confirm_cod_percent").val();
                var merchant_cod_percent = $("#confirm_merchant_cod_percent").val();
                if (district_id != "" && district_id != 0) {
                    $.ajax({
                        cache: false,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            merchant_id: merchant_id,
                            district_id: district_id,
                            _token: "{{ csrf_token() }}"
                        },
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: "{{ route('merchant.returnMerchantUpazilaWeightPackageOptionAndCharge') }}",
                        success: function(response) {
                            if (response.success) {
                                // $("#upazila_id").html(response.upazilaOption).attr('disabled', false);
                                $("#area_id").html(response.areaOption).attr('disabled', false);
                                $("#service_type_id").html(response.serviceTypeOption).attr(
                                    'disabled', false);
                                $("#item_type_id").html(response.itemTypeOption).attr('disabled',
                                    false);
                                $("#weight_package_id").html(response.weightPackageOption).attr(
                                    'disabled', false);

                                // Delivery Charge Comes from Service Area default_charge Or Merchant Set Service Charge
                                $("#only_merchant_service_area_charge").val(response.charge);
                                $("#confirm_merchant_service_area_charge").val(response.charge);
                                $("#confirm_merchant_service_area_return_charge").val(response
                                    .return_charge);

                                // Delivery Charge Comes from Service Area default_charge Or Merchant Set Service Charge
                                $("#confirm_delivery_charge").val(response.charge);
                                $("#view_delivery_charge").html(returnNumber(response.charge)
                                    .toFixed(2));

                                // console.log((merchant_cod_percent != "" || merchant_cod_percent != "0") && response.cod_charge != 0);

                                if (merchant_cod_percent != "" && merchant_cod_percent != "0" &&
                                    response.cod_charge != 0) {
                                    $("#confirm_cod_percent").val(merchant_cod_percent);
                                    $("#view_cod_percent").html(merchant_cod_percent + "%");
                                } else {
                                    $("#confirm_cod_percent").val(response.cod_charge);
                                    $("#view_cod_percent").html(response.cod_charge + "%");
                                }

                                calculate_total_charge();
                            } else {
                                toastr.error("something is wrong");
                            }
                        }
                    });
                }
            });

            // $('#upazila_id').on('change', function(){
            //     var upazila_id   = $("#upazila_id option:selected").val();
            //     $("#area_id").val(0).change().attr('disabled', true);
            //     $.ajax({
            //         cache     : false,
            //         type      : "POST",
            //         dataType  : "JSON",
            //         data      : {
            //             upazila_id : upazila_id,
            //             _token  : "{{ csrf_token() }}"
            //         },
            //         error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            //         url       : "{{ route('area.areaOption') }}",
            //         success   : function(response){
            //             $("#area_id").html(response.option).attr('disabled', false);
            //         }
            //     })
            // });


            $('#service_type_id').on('change', function() {
                var service_type_id = $("#service_type_id option:selected").val();

                var old_delivery_charge = returnNumber($("#only_merchant_service_area_charge").val());
                var item_type_charge = returnNumber($("#item_type_id option:selected").attr('data-charge'));
                var service_type_charge = returnNumber($("#service_type_id option:selected").attr(
                    'data-charge'));
                var service_type_title = $("#service_type_id option:selected").text();

                var charge = old_delivery_charge + item_type_charge + service_type_charge;
                $("#confirm_delivery_charge").val(charge);

                $("#service_type_charge").val(service_type_charge);
                $("#item_type_charge").val(item_type_charge);

                $("#view_service_type").html(service_type_title);
                if (service_type_id == 0) {
                    $("#view_service_type").html("Not Confirm");
                }

                $("#view_delivery_charge").html(returnNumber(charge).toFixed(2));


                calculate_total_charge();
            });

            $('#item_type_id').on('change', function() {
                var item_type_id = $("#item_type_id option:selected").val();
                var old_delivery_charge = returnNumber($("#only_merchant_service_area_charge").val());
                var item_type_charge = returnNumber($("#item_type_id option:selected").attr('data-charge'));
                var service_type_charge = returnNumber($("#service_type_id option:selected").attr(
                    'data-charge'));
                var item_type_title = $("#item_type_id option:selected").text();

                var charge = old_delivery_charge + item_type_charge + service_type_charge;
                $("#confirm_delivery_charge").val(charge);
                $("#view_delivery_charge").html(returnNumber(charge).toFixed(2));

                $("#service_type_charge").val(service_type_charge);
                $("#item_type_charge").val(item_type_charge);

                $("#view_item_type").html(item_type_title);
                if (item_type_id == 0) {
                    $("#view_item_type").html("Not Confirm");
                }

                calculate_total_charge();
            });


            /** Pickup Address */
            $("#shop_id").on("change", function() {

                var shop_id = $(this).val();
                var address = $("#shop_id option:selected").data('shop_address');

                if (shop_id != "" && shop_id != 0) {
                    $("#select_pickup_address").val(0).change();
                    $("#pickup_address").val(address);
                } else {
                    $("#pickup_address").val("");
                }
            });

            $("#select_pickup_address").on("change", function() {

                var address_id = $(this).val();
                var full_address = $("#merchant_full_address").val();
                var business_address = $("#merchant_business_address").val();

                if (address_id != "" && address_id != 0) {

                    $("#shop_id").val(0).change();

                    if (address_id == 1) {
                        $("#pickup_address").val(business_address);
                    } else {
                        $("#pickup_address").val(full_address);
                    }
                } else {
                    $("#pickup_address").val("");
                }
            });



            $('#weight_package_id').on('change', function() {
                var weight_package_id = $("#weight_package_id option:selected").val();
                var weight_package_name = $("#weight_package_id option:selected").text();
                var charge = returnNumber($("#weight_package_id option:selected").attr('data-charge'));
                var merchant_service_area_charge = returnNumber($("#confirm_merchant_service_area_charge")
                .val());

                // $("#confirm_weight_package_charge").val(charge);
                // if(merchant_service_area_charge == 0){
                //     $("#view_delivery_charge").html(charge.toFixed(2));
                //     $("#confirm_delivery_charge").val(charge);
                // }

                if (weight_package_id != 0) {
                    $("#view_weight_package").html(weight_package_name);
                    $("#view_weight_package_charge").html(charge.toFixed(2));
                    $("#confirm_weight_package_charge").val(charge.toFixed(2));
                } else {
                    $("#view_weight_package").html("Not Confirm");
                    $("#view_weight_package_charge").html("0.00");
                    $("#confirm_weight_package_charge").val(0);
                }
                calculate_total_charge();
            });

            $('#total_collect_amount').keyup(function() {
                calculate_total_charge();
            });
        }

        function calculate_total_charge() {
            var cod_percent = returnNumber($("#confirm_cod_percent").val());
            var total_collect_amount = returnNumber($("#total_collect_amount").val());
            $("#view_collection_amount").html(total_collect_amount.toFixed(2));


            var cod_charge = 0;
            if (cod_percent == 0 && total_collect_amount == 0) {
                $("#view_cod_charge").html("0.00");
                $("#confirm_cod_charge").val(0);
            } else {
                cod_charge = (total_collect_amount / 100) * cod_percent;
                $("#view_cod_charge").html(cod_charge.toFixed(2));
                $("#confirm_cod_charge").val(cod_charge);
            }


            var delivery_charge = returnNumber($("#confirm_delivery_charge").val());
            var weight_package_charge = returnNumber($("#confirm_weight_package_charge").val());

            console.log(cod_charge, delivery_charge, weight_package_charge);

            var total_charge = cod_charge + delivery_charge + weight_package_charge;
            $("#view_total_charge").html(total_charge.toFixed(2));
            $("#confirm_total_charge").val(total_charge);
        }

        function createForm() {
            let district_id = $('#district_id').val();
            if (district_id == '0') {
                toastr.error("Please Select District..");
                return false;
            }

            let item_type_id = $('#item_type_id').val();
            if (item_type_id == '0') {
                $("#select2-item_type_id-container").parent().css('border-color', 'red');
                toastr.error("Please Select Item Type..");
                return false;
            } else {
                $("#select2-district_id-container").parent().css('border-color', '#ced4da');
            }

            let weight_package_id = $('#weight_package_id').val();
            if (weight_package_id == '0') {
                $("#select2-weight_package_id-container").parent().css('border-color', 'red');
                toastr.error("Please Select Weight Package..");
                return false;
            } else {
                $("#select2-district_id-container").parent().css('border-color', '#ced4da');
            }



            // let upazila_id = $('#upazila_id').val();
            // if(upazila_id == '0'){
            //     toastr.error("Please Select Thana/Upazila..");
            //     return false;
            // }
            //            let area_id = $('#area_id').val();
            //            if(area_id == '0'){
            //                toastr.error("Please Select Area..");
            //                return false;
            //            }

            let branch_id = $('#branch_id').val();
            if (branch_id == '0') {
                toastr.error("Please Select Branch..");
                return false;
            }
        }
    </script>
@endpush
