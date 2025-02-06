@extends('layouts.admin_layout.admin_layout')

@section('content')
    @php
        $start_date = request()->start_date
            ? \Illuminate\Support\Carbon::parse(request()->start_date)->startOfDay()
            : \Illuminate\Support\Carbon::now()->startOfMonth();

        $end_date = request()->end_date
            ? \Illuminate\Support\Carbon::parse(request()->end_date)->endOfDay()
            : \Illuminate\Support\Carbon::now()->endOfDay();

        $data = \App\Models\ParcelLog::whereBetween('parcel_logs.date', [$start_date, $end_date])
            ->where('parcel_logs.status', 100)
            ->join('parcels', 'parcel_logs.parcel_id', '=', 'parcels.id')
            ->latest('parcel_logs.id')
            ->get([
                'parcel_logs.*',
                'parcels.parcel_invoice as parcel_invoice',
                'parcels.created_at as created_at',
                'parcels.customer_name as customer_name',
                'parcels.customer_contact_number as customer_contact_number',
                'parcels.customer_contact_number2 as customer_contact_number2',
                'parcels.status as status',
            ]);

        $tabledata = [];

        $key = 0;

        foreach ($data as $value) {
            $text = $value->note;

            $pattern = '/Amount to be collect has been changed from (\d+) to (\d+)/';

            if (preg_match($pattern, $text, $matches)) {
                $tabledata[$key]['id'] = $value->id;
                $tabledata[$key]['parcel_invoice'] = $value->parcel_invoice;
                $tabledata[$key]['created_at'] = date_format(date_create($value->created_at), 'd-m-Y');
                $tabledata[$key]['customer_name'] = $value->customer_name;
                $tabledata[$key]['customer_contact_number'] = $value->customer_contact_number;
                $tabledata[$key]['customer_contact_number2'] = $value->customer_contact_number2;
                $tabledata[$key]['date'] = $value->date;
                $tabledata[$key]['customer_info'] = $value->customer_info;
                $tabledata[$key]['status'] = $value->status;
                $tabledata[$key]['previous_amount'] = $matches[1];
                $tabledata[$key]['changed_amount'] = $matches[2];

                $tabledata[$key]['changed_by'] = $value->updated_by;

                $key++;
            }
        }

    @endphp

    <style>
        #yajraDatatable {
            width: 100% !important;
            max-width: 100% !important;
        }

        table#yajraDatatable {
            width: 100% !important;
            max-width: 100% !important;
        }
    </style>

    <div class="content">
        <div class="container-fluid">

            <br>

            @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success_message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Amount Changed</h3>

                            <form action="" class="form-inline float-right">
                                <label for="" class="mr-3">From Date</label>
                                <input type="date" name="start_date" id="" class="form-control mr-3"
                                    value="{{ $start_date->format('Y-m-d') }}">
                                <label for="" class="mr-3">To Date</label>
                                <input type="date" name="end_date" id="" class="form-control"
                                    value="{{ $end_date->format('Y-m-d') }}">
                            </form>

                        </div>

                        <div class="card-body">
                            <table id="yajraDatatable" class="table table-bordered table-striped"
                                style="width: 100% !important; max-width: 100% !important;">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Customer Info</th>
                                        <th>Status</th>
                                        <th>Previous Amount</th>
                                        <th>Changed Amount</th>
                                        <th>Changed Date</th>
                                        <th>Changed By</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($tabledata as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value['parcel_invoice'] }}</td>
                                            <td>{{ $value['created_at'] }}</td>
                                            <td>
                                                {{ $value['customer_name'] }}<br>
                                                {{ $value['customer_contact_number'] }}<br>
                                                {{ $value['customer_contact_number2'] }}
                                            </td>
                                            <td>{{ $value['status'] }}</td>
                                            <td>{{ $value['previous_amount'] }}</td>
                                            <td>{{ $value['changed_amount'] }}</td>
                                            <td>{{ $value['date'] }}</td>
                                            <td>{{ $value['changed_by'] }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('script_js')
    <script>
        var table = $('#yajraDatatable').DataTable({
            pageLength: 100,
        });
    </script>
@endpush
