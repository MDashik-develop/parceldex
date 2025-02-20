@extends('layouts.backend')

@section('main')
    <!-- breadcame start -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light bg-success rounded-circle"><i
                                            class="fas fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @php
        $client_id =key_exists('client_id',$filter)? $filter['client_id']:"";
        $payment_method_id =key_exists('payment_method_id',$filter)? $filter['payment_method_id']:"";
            $supplier_id =key_exists('supplier_id',$filter)? $filter['supplier_id']:"";
            $status =key_exists('status',$filter)? $filter['status']:"";
            $from_date =key_exists('from_date',$filter)? $filter['from_date']:"";
            $to_date =key_exists('to_date',$filter)? $filter['to_date']:date("Y-m-d");

            $status_name = null;
if ($status==0){
    $status_name="Pending";
} elseif ($status==1){
    $status_name="Approved";
}elseif ($status==2){
    $status_name="Rejected";
}elseif ($status==3){
    $status_name="Ordered";
}elseif ($status==4){
    $status_name="Completed";
}else{
    $status_name="All";
}
  $filter_client="";
if ($client_id){
    $filter_client=\App\Models\Client::where('id',$client_id)->first();
}
  $filter_payment_method="";
if ($payment_method_id){
    $filter_payment_method=\App\Models\PaymentMethod::where('id',$payment_method_id)->first();
}
  $filter_supplier="";
if ($supplier_id){
    $filter_supplier=\App\Models\Client::where('id',$supplier_id)->first();
}

            $totalPaymentAmount=0;
    @endphp
    <form action="{{route('report.payment')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="client_id">From</label>
                            <select name="client_id" id="client_id"
                                    class="form-control select2 @error('client_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}"
                                            {{$client_id==$client->id?'selected':''}} c_name="{{$client->name}}">
                                        {{$client->name.' - ('.$client->country->name.")"}}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id">To</label>
                            <select name="supplier_id" id="supplier_id"
                                    class="form-control select2 @error('supplier_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">Select Supplier</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->id}}"
                                            {{$supplier_id==$client->id?'selected':''}} c_name="{{$client->name}}">
                                        {{$client->name.' - ('.$client->country->name.")"}}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status"
                                    class="form-control select2 @error('status') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="0" {{$status==0?"selected":''}}>Pending</option>
                                <option value="3" {{$status==3?"selected":''}}>Order</option>
                                <option value="1" {{$status==1?"selected":''}}>Approved</option>
                                <option value="4" {{$status==4?"selected":''}}>Completed</option>
                                <option value="2" {{$status==2?"selected":''}}>Rejected</option>
                            </select>
                            @error('status')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payment_method_id">Payment Method</label>
                            <select name="payment_method_id" id="payment_method_id"
                                    class="form-control select2 @error('payment_method_id') is-invalid @enderror"
                                    onchange="this.form.submit()">
                                <option value="">All</option>
                                @foreach($methods as $method)
                                <option value="{{$method->id}}" {{$payment_method_id==$method->id?"selected":''}}>{{$method->name}}</option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" value="{{$from_date}}" name="from_date" id="from_date"
                                   class="form-control @error('from_date') is-invalid @enderror"
                                   onchange="this.form.submit()">
                            @error('from_date')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" id="to_date" value="{{$to_date}}"
                                   class="form-control @error('to_date') is-invalid @enderror"
                                   onchange="this.form.submit()" placeholder="DD/MM/YYYY">
                            @error('to_date')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center" style="margin-top: 28px;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                <button type="button" class="btn btn-success btn-sm" id="printBtn">
                    <i class="fa fa-print"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="pdfBtn" title="Download pdf">
                    <i class="fa fa-file"></i>
                </button>
            </div>
        </div>
    </form>
    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Payment Report</legend>
        <div class="row mx-2">
            <div class="col-md-6">
                <p class=""><strong> Report Date: </strong>{{date("d M, Y")}}</p>
                @if($filter_supplier)
                    <p><strong>To: </strong>{{$filter_supplier->name}}</p>
                @endif
                @if($filter_client)
                    <p><strong>From: </strong>{{$filter_client->name}}</p>
                @endif
                @if($filter_payment_method)
                    <p><strong>Payment Method: </strong>{{$filter_payment_method->name}}</p>
                @endif
            </div>
            <div class="col-md-6 text-right">
                @if($status_name)
                    <p><strong>Status: </strong>{{$status_name}}</p>
                @endif
                @if($from_date)
                    <p><strong>From Date: </strong>{{$from_date}}</p>
                @endif
                @if($to_date)
                    <p><strong>To Date: </strong>{{$to_date}}</p>
                @endif
            </div>


        </div>

        <!-- data table start -->
        <div class="data_table my-4">
            <div class="content_section table-responsive">
                <table class="table table-bordered table-striped" id="client_ledger" {{--style="font-size: 13px;"--}}>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Payment No</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Remarks</th>
                        <th>Created By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $key=>$item)
                        <tr>
                            <td class="text-center">{{$key+1}}</td>
                            <td class="text-center">{{date('d M, Y',strtotime($item->date))}}</td>
                            <td class="text-center">{{$item->payment_no}}</td>
                            <td>{{$item->client?$item->client->name:"---"}}</td>
                            <td>{{$item->supplier?$item->supplier->name:"---"}}</td>
                            <td class="text-center">
                                @if ($item->status == 1)
                                    <span class="badge badge-info">Approved</span>
                                @elseif ($item->status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($item->status == 2)
                                    <span class="badge badge-danger">Rejected</span>
                                @elseif ($item->status == 3)
                                    <span class="badge badge-primary">Order</span>
                                @elseif ($item->status == 4)
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </td>
                            <td class="text-center">{{number_format($item->amount,2)}}</td>
                            <td class="text-center">{{$item->payment_method->name}}</td>
                            <td class="text-center">{{$item->remarks}}</td>
                            <td class="text-center">{{$item->created_user->name}}</td>
                        </tr>
                        @php
                            $totalPaymentAmount+=$item->amount;
                        @endphp
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="6" style="text-align: right">Totals:</th>
                        <th class="text-center">{{number_format($totalPaymentAmount)}}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- end -->
    </fieldset>
    <!-- Modal -->
    <div class="modal fade" id="viewModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="showResult">
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script type="text/javascript">
        window.onload = function () {
            $(document).on('click', '#printBtn', function () {
                var status = $('#status option:selected').val();
                var client_id = $('#client_id option:selected').val();
                var supplier_id = $('#supplier_id option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.paymentPrint') !!}',
                    data: {
                        status: status,
                        from_date: from_date,
                        to_date: to_date,
                        client_id: client_id,
                        supplier_id: supplier_id
                    },
                    dataType: 'html',
                    success: function (html) {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(html);
                        w.document.close();
                        w.window.print();
                        w.window.close();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('#client_ledger').on('click', '.view-modal', function () {
                var transaction_id = $(this).attr('transaction_id');
                var url = "{{ route('transaction.view', ":transaction_id") }}";
                url = url.replace(':transaction_id', transaction_id);
                $('#showResult').html('');
                if (transaction_id.length != 0) {
                    $.ajax({
                        cache: false,
                        type: "GET",
                        error: function (xhr) {
                            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                        },
                        url: url,
                        success: function (response) {
                            console.log(response);
                            $('#showResult').html(response);
                        },
                    })
                }
            });


            $(document).on('click', '#pdfBtn', function () {
                var currency_id = $('#currency_id option:selected').val();
                var c_name = $('#currency_id option:selected').attr('c_name');
                var transaction_for = $('#transaction_for option:selected').val();
                var transaction_type = $('#transaction_type option:selected').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                $.ajax({
                    type: 'GET',
                    url: '{!! route('report.paymentPdf') !!}',
                    data: {
                        currency_id: currency_id,
                        from_date: from_date,
                        to_date: to_date,
                        transaction_for: transaction_for,
                        transaction_type: transaction_type
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (html) {
                        // console.log(html)
                        var blob = new Blob([html]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "report_"+ c_name + "_" + from_date + "_to_" + to_date + "_" + ".pdf";
                        link.click();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });


        }
    </script>
    <!-- data table -->
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('assets/backend/vendor/js/data-table/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#client_ledger').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, 150, -1],
                    [10, 25, 50, 100, 150, 'All'],
                ]
            });
        });
    </script>
@endsection
@section('style')
    <!-- data table -->
    {{--    <link rel="stylesheet" href="{{asset('assets/backend/vendor/css/data-table/dataTables.bootstrap4.min.css')}}">--}}
@endsection
