@extends('layouts.merchant_layout.merchant_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Return Rider Run List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Return Rider Runs List</li>
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
                            <h3 class="card-title"> Return Rider Run List </h3>
                        </div>
                        <div class="card-body">
                            <fieldset>
                                <legend>Return Run Parcel</legend>
                                <table class=" table table-style table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Cons ID</th>
                                            <th>Parcel Create Date</th>
                                            <th>Merchant Order ID</th>
                                            <th>Status</th>
                                            <th>Return Date</th>
                                            <th>Return Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riderRun as $item)
                                            @foreach ($item->rider_run_details as $rider_run_detail)
                                                @if ($rider_run_detail->parcel->merchant->id != auth()->guard('merchant')->user()->id)
                                                    @continue;
                                                @endif
                                                <tr>
                                                    <td class="text-center"> {{ $loop->iteration }} </td>
                                                    <td class="text-center"> {{ $rider_run_detail->parcel->parcel_invoice }}
                                                    </td>
                                                    <td class="text-center">
                                                        @switch($rider_run_detail->status)
                                                            @case(1)
                                                                Run Create
                                                            @break

                                                            @case(2)
                                                                Run Start
                                                            @break

                                                            @case(3)
                                                                Run Cancel
                                                            @break

                                                            @case(4)
                                                                Rider Accept
                                                            @break

                                                            @case(5)
                                                                Rider Reject
                                                            @break

                                                            @case(6)
                                                                Rider Reschedule
                                                            @break

                                                            @case(7)
                                                                Rider Complete
                                                            @break

                                                            @default
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($rider_run_detail->status == 7)
                                                            {{ \Carbon\Carbon::parse($rider_run_detail->complete_date_time)->format('d/m/Y H:i:s') }}
                                                            <br>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $rider_run_detail->parcel->merchant->company_name }} </td>
                                                    <td class="text-center">
                                                        {{ $rider_run_detail->parcel->merchant->contact_number }} </td>
                                                    <td class="text-center"> {{ $rider_run_detail->parcel->customer_name }}
                                                    </td>
                                                    <td class="text-center"> {{ $rider_run_detail->complete_note }} </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>
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
        td {
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
@endpush

@push('script_js')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        window.onload = function() {

            load_data();

            function load_data(run_status = '', rider_id = '', from_date = '', to_date = '') {

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
                        url: '{!! route('merchant.parcel.getReturnRiderRunList') !!}',
                        data: {
                            run_status: run_status,
                            rider_id: rider_id,
                            from_date: from_date,
                            to_date: to_date,
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            class: "text-center"
                        },
                        {
                            data: 'run_invoice',
                            name: 'run_invoice',
                            class: "text-center"
                        },
                        {
                            data: 'rider.name',
                            name: 'rider.name',
                            class: "text-center"
                        },
                        {
                            data: 'rider.address',
                            name: 'rider.address',
                            class: "text-center"
                        },
                        {
                            data: 'create_date_time',
                            name: 'create_date_time',
                            class: "text-center"
                        },
                        {
                            data: 'complete_date_time',
                            name: 'complete_date_time',
                            class: "text-center"
                        },
                        {
                            data: 'total_run_parcel',
                            name: 'total_run_parcel',
                            class: "text-center"
                        },
                        {
                            data: 'total_run_complete_parcel',
                            name: 'total_run_complete_parcel',
                            class: "text-center"
                        },
                        {
                            data: 'status',
                            name: 'status',
                            class: "text-center"
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            class: "text-center"
                        }
                    ]
                });
            }

            $(document).on('click', '#printBtn', function() {
                var run_status = $('#run_status option:selected').val();
                var rider_id = $('#rider_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('branch.parcel.printReturnRiderRunList') !!}',
                    data: {
                        run_status: run_status,
                        rider_id: rider_id,
                        from_date: from_date,
                        to_date: to_date,
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

            $('#filter').click(function() {
                var run_status = $('#run_status option:selected').val();

                var rider_id = $('#rider_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                $('#yajraDatatable').DataTable().destroy();
                load_data(run_status, rider_id, from_date, to_date);
            });

            $(document).on('click', '#refresh', function() {
                $("#run_status").val("").trigger('change');
                $("#rider_id").val("").trigger('change');
                $("#from_date").val("");
                $("#to_date").val("");
                $('#yajraDatatable').DataTable().destroy();
                load_data('', '', '', '');
            });


            $('#yajraDatatable').on('click', '.view-modal', function() {
                var rider_run_id = $(this).attr('rider_run_id');
                var url = "{{ route('branch.parcel.viewReturnRiderRun', ':rider_run_id') }}";
                url = url.replace(':rider_run_id', rider_run_id);
                $('#showResult').html('');
                if (rider_run_id.length != 0) {
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

            $('#yajraDatatable').on('click', '.run-start-btn', function() {
                var status_object = $(this);
                var rider_run_id = status_object.attr('rider_run_id');
                var url = "{{ route('branch.parcel.startReturnRiderRun') }}";
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        rider_run_id: rider_run_id,
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
                })
            });

            $('#yajraDatatable').on('click', '.run-cancel-btn', function() {
                var status_object = $(this);
                var rider_run_id = status_object.attr('rider_run_id');
                var url = "{{ route('branch.parcel.cancelReturnRiderRun') }}";
                $.ajax({
                    cache: false,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        rider_run_id: rider_run_id,
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
                })
            });

            $('#yajraDatatable').on('click', '.rider-run-reconciliation', function() {
                var rider_run_id = $(this).attr('rider_run_id');
                var url = "{{ route('branch.parcel.returnRiderRunReconciliation', ':rider_run_id') }}";
                url = url.replace(':rider_run_id', rider_run_id);
                $('#showResult').html('');
                if (rider_run_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function(xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function(response) {
                            $('#showResult').html(response);
                            $('.select2').select2();
                        },
                    })
                }
            });
        }
    </script>
@endpush
