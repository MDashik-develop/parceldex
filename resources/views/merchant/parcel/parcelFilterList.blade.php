@extends('layouts.merchant_layout.merchant_layout')

@push('style_css')
    <style>
        .table-responsive>.table-bordered {
            border: 1px solid #dee2e6;
        }
    </style>
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Parcel Filter List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Parcel Filter List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <input type="button" class="btn btn-success" onclick="printDiv('printArea')" value="Print" />
                    <div class="card" id="printArea">
                        <div class="card-body table-responsive" id="merchantParcelReport">

                            <div class="report-header" style="margin-top: 10px;">

                                <h3 class="text-center">Parcel Filter List</h3>

                                <h4 class="text-center text-capitalize">{{ str_replace('_', ' ', $type) }} </h3>
                                    <h5 class="text-center">Parcel - {{ count($parcels) }} </h3>
                            </div>
                            <table id="merchantWiseReport" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL</th>
                                        <th width="5%" class="text-center">Parcel Date</th>
                                        <th width="5%" class="text-center"> Invoice ID</th>
                                        <th width="5%" class="text-center"> Status</th>
                                        <th width="10%" class="text-center"> Customer Name </th>
                                        <th width="5%" class="text-center"> District </th>
                                        <th width="10%" class="text-center"> Area </th>
                                        <th width="15%" class="text-center"> Address </th>
                                        <th width="10%" class="text-center"> Phone </th>
                                        <th width="10%" class="text-center"> Note </th>
                                        <th width="5%" class="text-center"> Amount to be Collect</th>
                                        <th width="5%" class="text-center"> Collected</th>
                                        <th width="5%" class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                $total_parcel = 0;
                                if (count($parcels) > 0) {
                                    $i = 0;
                                    $total_collect_amount = 0;
                                    $total_delivery_charge = 0;
                                    $customer_collect_amount = 0;
                                    foreach ($parcels as $parcel) {
                                        // dd($parcel);
                                        $i++;
                                        $total_collect_amount += $parcel->total_collect_amount;
                                        $customer_collect_amount += $parcel->cancel_amount_collection != 0 ? $parcel->cancel_amount_collection : $parcel->customer_collect_amount;

                                        $parcelStatus = returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type, $parcel->parcel_invoice);
                                        $status_name = $parcelStatus['status_name'];
                                        $class = $parcelStatus['class'];


                                        $logs_note = "";

                                        if ($parcel->parcel_logs) {
                                            foreach ($parcel->parcel_logs as $parcel_log) {
                                                if ("" != $logs_note) {
                                                    $logs_note .= ",<br>";
                                                }
                                                $logs_note .= $parcel_log->note;
                                            }
                                        }
                                ?>
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($parcel->date)->format('d M Y') }}
                                        </td>
                                        <td class="text-center">{{ $parcel->parcel_invoice }}</td>
                                        <td class="text-center">
                                            <span class="text-bold badge badge-{{ $class }}"
                                                style="font-size:16px;">{{ $status_name }} </span>
                                        </td>
                                        <td class="text-center">{{ $parcel->customer_name }}</td>
                                        <td class="text-center">{{ $parcel->district->name }}</td>
                                        <td class="text-center">{{ $parcel->area->name }}</td>
                                        <td class="text-center">{{ $parcel->customer_address }}</td>
                                        <td class="text-left">{{ $parcel->customer_contact_number }}</td>
                                        <td class="text-center">{{ $logs_note }}</td>
                                        <td class="text-center">{{ $parcel->total_collect_amount }}</td>
                                        <td class="text-center">
                                            {{ $parcel->cancel_amount_collection != 0 ? $parcel->cancel_amount_collection : $parcel->customer_collect_amount }}
                                        </td>
                                        <td>
                                            <button class="btn btn-primary view-modal btn-sm" data-toggle="modal"
                                                data-target="#viewModal" parcel_id="{{ $parcel->id }}"
                                                title="Parcel View">
                                                <i class="fa fa-eye"></i> </button>
                                        </td>
                                    </tr>
                                    <?php
                                    }

                                    ?>
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <h5><b>Totals</b></h5>
                                        </td>
                                        <td class="text-center">
                                            <h5><b>{{ number_format($total_collect_amount, 2) }} </b></h5>
                                        </td>
                                        <td class="text-center">
                                            <h5><b>{{ number_format($customer_collect_amount, 2) }} </b></h5>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    echo '<tr>
                                            <td colspan="6" class="text-center">No data available here!</td>
                                          </tr>';
                                }

                                ?>
                                </tbody>

                            </table>
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
@endpush

@push('script_js')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        $('#merchantWiseReport').on('click', '.view-modal', function() {
            var parcel_id = $(this).attr('parcel_id');

            console.log(parcel_id);
            var url = "{{ route('merchant.parcel.viewParcel', ':parcel_id') }}";
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
    </script>
@endpush
