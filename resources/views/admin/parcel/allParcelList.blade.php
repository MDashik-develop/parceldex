@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">All Parcel List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Parcels List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> All Parcels List </h3>

                            <div class="row" style="margin-top: 40px">

                                {{-- <div class="col-md-3"> --}}
                                {{-- <label for="rider_id">Rider</label> --}}
                                {{-- <select name="rider_id" id="rider_id" class="form-control select2" style="width: 100%" > --}}
                                {{-- <option value="0" >Select Rider </option> --}}
                                {{-- @foreach ($riders as $rider) --}}
                                {{-- <option value="{{ $rider->id }}" > {{ $rider->name }} </option> --}}
                                {{-- @endforeach --}}
                                {{-- </select> --}}
                                {{-- </div> --}}
                                {{-- <div class="col-md-3"> --}}
                                {{-- <label for="run_status">Run Status</label> --}}
                                {{-- <select name="run_status" id="run_status" class="form-control select2" style="width: 100%" > --}}
                                {{-- <option value="0" >Select Run Status </option> --}}
                                {{-- <option value="1" >Run Create </option> --}}
                                {{-- <option value="2" >Run Start </option> --}}
                                {{-- <option value="3" >Run Cancel </option> --}}
                                {{-- <option value="4" >Run Complete </option> --}}
                                {{-- </select> --}}
                                {{-- </div> --}}
                                <?php
                                
                                $filter_type = array_key_exists('filter_type', $_GET) ? $_GET['filter_type'] : 0;
                                $from_date = array_key_exists('from_date', $_GET) ? $_GET['from_date'] : '';
                                $to_date = array_key_exists('to_date', $_GET) ? $_GET['to_date'] : '';
                                $payment_status = array_key_exists('payment_status', $_GET) ? $_GET['payment_status'] : '';
                                
                                ?>

                                <div class="col-md-10">

                                    <form id="filterForm">
                                        <div class="row">

                                            <div class="col-sm-12 col-md-3">
                                                <label for="parcel_status">Parcel Status</label>
                                                <select name="parcel_status" id="parcel_status" class="form-control select2"
                                                    style="width: 100%">
                                                <option value="0">Select Parcel Status</option>
                                                <option value="0">Select Parcel Status</option>
                                                {{--<option value="1">Complete Delivery </option>--}}
                                                {{--<option value="2">Partial Delivery </option>--}}
                                                {{--<option value="3">Return Parcel </option>--}}
                                                {{--<option value="4">Waiting For Pickup</option>--}}
                                                {{--<option value="5">Waiting For Delivery </option>--}}
                                                {{--<option value="6">Cancel Parcel </option>--}}
                                                    <option value="0">Select Parcel Status</option>
                                                {{--<option value="1">Complete Delivery </option>--}}
                                                {{--<option value="2">Partial Delivery </option>--}}
                                                {{--<option value="3">Return Parcel </option>--}}
                                                {{--<option value="4">Waiting For Pickup</option>--}}
                                                {{--<option value="5">Waiting For Delivery </option>--}}
                                                {{--<option value="6">Cancel Parcel </option>--}}

                                                    <option value="1">Delivered</option>
                                                    <option value="11">Delivery Approval Pending</option>
                                                    <option value="13">Partial Delivered</option>
                                                    <option value="3">Cancelled</option>
                                                    <option value="14">Returned Parcel</option>
                                                    <option value="17">Cancel Request</option>
                                                    <option value="2">Waiting For Delivery</option>
                                                    <option value="7">Pickup Request</option>
                                                    <option value="18">In Review API</option>
                                                    <option value="6">Return Complete</option>
                                                    <option value="10">Branch Transfer</option>
                                                    <option value="8">Branch Transfer Complete</option>
                                                    <option value="9">Pickup Complete</option>

                                                </select>
                                            </div>

                                            <div class="col-sm-12 col-md-3">
                                                <label for="merchant_id"> Merchant</label>
                                                <select name="merchant_id" id="merchant_id" class="form-control select2"
                                                    style="width: 100%" required>
                                                    <option value="0">Select Company</option>
                                                    @foreach ($merchants as $merchant)
                                                        <option value="{{ $merchant->id }}">{{ $merchant->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                <label for="delivery_branch_id"> Delivery Branch</label>
                                                <select name="delivery_branch_id" id="delivery_branch_id"
                                                    class="form-control select2" style="width: 100%" required>
                                                    <option value="0">Select Branch</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>





                                            <div class="col-sm-12 col-md-3" style="margin-top: 20px">
                                                <input type="text" name="parcel_invoice" id="parcel_invoice"
                                                    class="form-control" placeholder="Enter Invoice / Order ID / P. Number"
                                                    style="font-size: 16px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                            padding: 3px 0px 3px 3px;
                                            margin: 5px 1px 3px 0px;
                                            border: 1px solid rgb(62, 196, 118);">
                                            </div>





                                            <div class="col-sm-12 col-md-3">
                                                <label for="from_date">From Date</label>
                                                <input type="date" name="from_date" id="from_date" class="form-control"
                                                    value="{{ $from_date }}" />
                                            </div>

                                            <div class="col-sm-12 col-md-3">
                                                <label for="to_date">To Date</label>
                                                <input type="date" name="to_date" id="to_date" class="form-control"
                                                    value="{{ $to_date }}" />
                                            </div>

                                            <div class="col-sm-12 col-md-3">
                                                <label for="to_date">Payment Status</label>
                                                <select name="payment_status" id="payment_status" class="form-control select2"
                                                    style="width:100%">
                                                    <option value="">All</option>
                                                    <option value="Paid">Paid</option>
                                                    <option value="Unpaid">Unpaid</option>
                                                </select>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <div class="col-sm-12 col-md-2" style="margin-top: 20px">
                                    <button type="button" name="filter" id="filter" class="btn btn-success">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <button type="button" name="refresh" id="refresh" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <button type="button" name="print" id="print" class="btn btn-primary">
                                        <i class="fas fa-print"></i>
                                    </button>

                                    <form action="{{ route('admin.parcel.excelAllParcelList') }}">
                                        @csrf
                                        <input type="hidden" id="ex_parcel_status" name="ex_parcel_status"
                                            value="0">
                                        <input type="hidden" id="ex_merchant_id" name="ex_merchant_id" value="0">
                                        <input type="hidden" id="ex_delivery_branch_id" name="ex_delivery_branch_id"
                                            value="0">
                                        <input type="hidden" id="ex_parcel_invoice" name="ex_parcel_invoice"
                                            value="">
                                        <input type="hidden" id="ex_from_date" name="ex_from_date"
                                            value="{{ $from_date }}">
                                        <input type="hidden" id="ex_to_date" name="ex_to_date"
                                            value="{{ $to_date }}">
                                        <input type="hidden" id="ex_payment_status" name="ex_payment_status"
                                            value="{{ $payment_status }}">
                                        <button type="submit" class="btn btn-primary mt-4">
                                            <i class="fas fa-file-excel"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="yajraDatatable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> SL</th>
                                            <th class="text-center"> Invoice</th>
                                            <th class="text-center"> Status</th>
                                            <th class="text-center"> Parcel</th>
                                            <th class="text-center"> Company</th>
                                            <th class="text-center"> Customer</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center"> Remarks / Notes</th>
                                            <th class="text-center"> Payment Status</th>
                                            <th class="text-center"> Return Status</th>
                                            <th class="text-center"> Action</th>
                                            <th class="text-center" width="10%">
                                                <button type="button" id="printMultiple" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                                <button type="button" id="checkAll" class="btn btn-success btn-sm">
                                                    {{-- <i class="fas fa-check"></i> --}}
                                                    <i class="fas fa-square" id="checkAllIcon"></i>
                                                </button>

                                                </form>

                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="showResult">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('style_css')
    <style>
        th,
        td p {
            margin-bottom: 0;
            white-space: nowrap;
        }

        th,
        td .parcel_status {
            white-space: nowrap;
        }

        div.dataTables_wrapper {
            margin: 0 auto;
        }

        /*
                    div.container {
                        width: 80%;
                    }
                    */
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script>
        //https://datatables.net/extensions/fixedcolumns/examples/initialisation/size_fixed.html
        window.onload = function() {


            load_data();

            function load_data(parcel_status = "", parcel_invoice = "", merchant_order_id = "",
                customer_contact_number = "", merchant_id = "", delivery_branch_id = "", from_date = "", to_date = "",
                payment_status = "") {
                var table = $('#yajraDatatable').DataTable({
                    pageLength: 100,
                    lengthMenu: [
                        [100, 200, 500, -1],
                        [100, 200, 500, 'All']
                    ],
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('admin.parcel.getAllParcelList') !!}',
                        data: {
                            parcel_status: parcel_status,
                            parcel_invoice: parcel_invoice,
                            merchant_order_id: merchant_order_id,
                            customer_contact_number: customer_contact_number,
                            merchant_id: merchant_id,
                            delivery_branch_id: delivery_branch_id,
                            from_date: from_date,
                            to_date: to_date,
                            payment_status: payment_status
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            class: "text-center"
                        },
                        {
                            data: 'parcel_invoice',
                            name: 'parcel_invoice'
                        },
                        {
                            data: 'parcel_status',
                            name: 'parcel_status',
                            searchable: false,
                            class: "text-center"
                        },
                        {
                            data: 'parcel_info',
                            name: 'parcel_info'
                        },
                        {
                            data: 'company_info',
                            name: 'company_info'
                        },
                        {
                            data: 'customer_info',
                            name: 'customer_info'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'remarks',
                            name: 'remarks'
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status',
                            searchable: false,
                            class: "text-center"
                        },
                        {
                            data: 'return_status',
                            name: 'return_status',
                            searchable: false,
                            class: "text-center"
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            class: "text-center"
                        },
                        {
                            data: 'print',
                            name: 'print',
                            orderable: false,
                            searchable: false,
                            class: "text-center"
                        }
                    ],

                    createdRow: function(row, data, index) {

                        // $('td', row).eq(2).addClass(`bg-${data['parcel_color']}`);

                        // if (data['due_amount'] > 0) {
                        //     $('td', row).eq(4).css('color','#ffffff');
                        //     $('td', row).eq(4).css('background-color', '#f4511e','color','#ffffff');
                        // } else {

                        // }
                        // $('td', row).eq(3).addClass('text-right');
                        // $('td', row).eq(4).addClass('text-right');
                        // $('td', row).eq(5).addClass('text-right');
                        // $('td', row).eq(6).addClass('text-right');
                    },


                    // "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    //     switch(aData['parcel_status']){
                    //         case 1:
                    //             $('td', nRow).css('background-color', '#dacfcf')
                    //             break;
                    //     }
                    // },
                    // columnDefs: [
                    //     {
                    //         targets: 4,
                    //         render: function (data, type, row) {
                    //             // console.log(data, type, row);
                    //             // if (type === 'display') {
                    //             //     return '<input type="checkbox" class="editor-active">';
                    //             // }
                    //             // return data;
                    //         }
                    //     }
                    // ],
                    // paging:         false,

                    scrollY: "500px",
                    scrollX: true,
                    scrollCollapse: true,
                    columnDefs: [{
                        width: 2000,
                        targets: 10
                    }],
                    fixedColumns: true,
                    order: [
                        [1, 'DESC']
                    ]
                });
            }

            $(document).on('click', '#checkAll', function() {
                var checkboxes = document.getElementsByClassName('print-check');
                var allChecked = true;

                // Check if all checkboxes are currently checked
                for (var checkbox of checkboxes) {
                    if (!$(checkbox).prop('checked')) {
                        allChecked = false;
                        break;
                    }
                }

                // Toggle checkboxes
                for (var checkbox of checkboxes) {
                    $(checkbox).prop('checked', !allChecked);
                }

                // Toggle icon
                var icon = $('#checkAllIcon');
                if (allChecked) {
                    icon.removeClass('fa-check-square').addClass('fa-square');
                } else {
                    icon.removeClass('fa-square').addClass('fa-check-square');
                }
            });

            // $(document).on('click', '#checkAll', function () {
            //     var checkboxes = document.getElementsByClassName('print-check');
            //     for (var checkbox of checkboxes) {
            //         $(checkbox).prop('checked', true);
            //     }
            // });

            $(document).on('click', '#printMultiple', function() {
                var parcel_ids = $(".print-check:checkbox:checked").map(function() {
                    return $(this).val();
                }).get();
                console.log(parcel_ids);
                /*
                var url = '{!! route('merchant.parcel.printParcelMultiple') !!}';
                var data =  {
                    parcel_ids: parcel_ids,
                };

                $.post(url, function (parcel_ids) {
                    var w = window.open('about:blank');
                    w.document.open();
                    w.document.write(data);
                    w.document.close();
                });*/

                $.ajax({
                    type: 'POST',
                    url: '{!! route('admin.parcel.printParcelMultiple') !!}',
                    data: {
                        parcel_ids: parcel_ids,
                    },
                    dataType: 'html',
                    success: function(html) {
                        console.log(html)
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(html);
                        // w.document.close();
                        // w.window.print();
                        // w.window.close();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });

            });

            $('#filter').click(function() {
                var parcel_status = $('#parcel_status option:selected').val();
                var merchant_id = $('#merchant_id option:selected').val();
                var delivery_branch_id = $('#delivery_branch_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var parcel_invoice = $('#parcel_invoice').val();
                var merchant_order_id = $('#merchant_order_id').val();
                var customer_contact_number = $('#customer_contact_number').val();
                var payment_status = $('#payment_status').val();

                $('#ex_parcel_status').val(parcel_status);
                $('#ex_merchant_id').val(merchant_id);
                $('#ex_delivery_branch_id').val(delivery_branch_id);
                $('#ex_from_date').val(from_date);
                $('#ex_to_date').val(to_date);
                $('#ex_parcel_invoice').val(parcel_invoice);
                $('#ex_merchant_order_id').val(merchant_order_id);
                $('#ex_payment_status').val(payment_status);


                $('#yajraDatatable').DataTable().destroy();
                load_data(parcel_status, parcel_invoice, merchant_order_id, customer_contact_number,
                    merchant_id, delivery_branch_id, from_date, to_date, payment_status);
            });



            $('#filterForm').submit(function() {
                var parcel_status = $('#parcel_status option:selected').val();
                var merchant_id = $('#merchant_id option:selected').val();
                var delivery_branch_id = $('#delivery_branch_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var parcel_invoice = $('#parcel_invoice').val();
                var merchant_order_id = $('#merchant_order_id').val();
                var customer_contact_number = $('#customer_contact_number').val();
                var payment_status = $('#payment_status').val();


                $('#ex_parcel_status').val(parcel_status);
                $('#ex_merchant_id').val(merchant_id);
                $('#ex_delivery_branch_id').val(delivery_branch_id);
                $('#ex_from_date').val(from_date);
                $('#ex_to_date').val(to_date);
                $('#ex_parcel_invoice').val(parcel_invoice);
                $('#ex_merchant_order_id').val(merchant_order_id);
                $('#ex_payment_status').val(payment_status);


                $('#yajraDatatable').DataTable().destroy();
                load_data(parcel_status, parcel_invoice, merchant_order_id, customer_contact_number,
                    merchant_id, delivery_branch_id, from_date, to_date, payment_status);
                return false;
            });

            $(document).on('click', '#refresh', function() {
                $("#parcel_status").val("0").trigger('change');
                $("#merchant_id").val("0").trigger('change');
                $("#delivery_branch_id").val("0").trigger('change');
                $("#parcel_invoice").val("");
                $("#merchant_order_id").val("");
                $("#customer_contact_number").val("");
                $("#from_date").val("");
                $("#to_date").val("");
                $('#yajraDatatable').DataTable().destroy();
                load_data('', '');
            });

            $(document).on('click', '#print', function() {

                var parcel_status = $('#parcel_status option:selected').val();
                var merchant_id = $('#merchant_id option:selected').val();
                var delivery_branch_id = $('#delivery_branch_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var parcel_invoice = $('#parcel_invoice').val();
                var merchant_order_id = $('#merchant_order_id').val();
                var customer_contact_number = $('#customer_contact_number').val();

                $.ajax({
                    type: 'GET',
                    url: '{!! route('admin.parcel.printAllParcelList') !!}',
                    data: {
                        parcel_status: parcel_status,
                        parcel_invoice: parcel_invoice,
                        merchant_order_id: merchant_order_id,
                        customer_contact_number: customer_contact_number,
                        merchant_id: merchant_id,
                        delivery_branch_id: delivery_branch_id,
                        from_date: from_date,
                        to_date: to_date
                    },
                    dataType: 'html',
                    success: function(html) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(html);
                        w.document.close();
                        w.window.print();
                        w.window.close();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('#yajraDatatable').on('click', '.view-modal', function() {
                var parcel_id = $(this).attr('parcel_id');
                var url = "{{ route('admin.parcel.viewParcel', ':parcel_id') }}";
                url = url.replace(':parcel_id', parcel_id);
                $('#showResult').html('');
                if (parcel_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            $('#showResult').html(response);
                        },
                    })
                }
            });

            $('#yajraDatatable').on('click', '.delete-btn', function() {
                var status_object = $(this);
                var parcel_id = status_object.attr('parcel_id');

                var sttaus = confirm("Are you sure delete this parcel?");

                if (sttaus) {

                    var url = "{{ route('admin.parcel.deleteParcel') }}";

                    $.ajax({
                        cache: false,
                        type: "DELETE",
                        dataType: "JSON",
                        data: {
                            parcel_id: parcel_id,
                            _token: "{{ csrf_token() }}"
                        },
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.success);
                                $('#yajraDatatable').DataTable().ajax.reload();
                            } else {
                                toastr.error(response.error);
                            }
                        }
                    });
                }

            });

        }
    </script>
@endpush
