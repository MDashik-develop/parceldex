@extends('layouts.merchant_layout.merchant_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Merchant API Request in Review List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Merchant API Request in Review List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Merchant API Request in Review List </h3>

                            <form action="{{ route('merchant.push.request') }}" method="GET">
                                <div class="row input-daterange" style="margin-top: 40px">

                                    {{-- <div class="col-md-3 d-none">
                                        <label for="branch_id">Merchant </label>
                                        <select name="merchant_id" id="branch_id" class="form-control select2"
                                            style="width: 100%">
                                            <option value="0">Select Merchant </option>
                                            @foreach ($merchants as $merchant)
                                                <option value="{{ $merchant->id }}">{{ $merchant->company_name }} </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    {{-- <div class="col-md-3">
                                        <label for="branch_id">Branch </label>
                                        <select name="branch_id" id="branch_id" class="form-control select2"
                                            style="width: 100%">
                                            <option value="0">Select Branch </option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="col-md-2">
                                        <label for="from_date">From Date</label>
                                        <input type="datetime-local" name="from_date" id="from_date" class="form-control"
                                            value="" />
                                    </div>
                                    <div class="col-md-2">
                                        <label for="to_date">To Date</label>
                                        <input type="datetime-local" name="to_date" id="to_date" class="form-control"
                                            value="" />
                                    </div>
                                    <div class="col-md-2" style="margin-top: 20px">
                                        <button type="submit" name="filter" id="filter" class="btn btn-success">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @if ($parcels->count() > 0)
                                    <form action="{{ route('merchant.savePushRequest') }}" method="POST">
                                        @csrf
                                        <table class="table table-style table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center"> SL </th>
                                                    <th width="10%" class="text-center">Invoice </th>
                                                    <th width="10%" class="text-center">Merchant Order ID</th>
                                                    <th width="10%" class="text-center">Product Details</th>
                                                    <th width="10%" class="text-center">Amount to be Collect</th>
                                                    <th width="10%" class="text-center">Date Time</th>
                                                    <th width="10%" class="text-center">Customer Info</th>
                                                    <th width="10%" class="text-center">District</th>
                                                    <th width="10%" class="text-center">Area</th>
                                                    <th width="10%" class="text-center">Package Weight</th>
                                                    <th width="10%" class="text-center">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($parcels as $key => $parcel)
                                                    <input type="hidden" name="parcel_id[]" value="{{ $parcel->id }}" />
                                                    <tr>

                                                        <td class="text-center"> {{ $loop->iteration }} </td>
                                                        <td class="text-center">
                                                            {{ $parcel->parcel_invoice }}
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $parcel->merchant_order_id }}
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $parcel->product_details }}
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $parcel->total_collect_amount }}
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $parcel->created_at->format('d-m-Y g:i A') }}
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $parcel->customer_name }},
                                                            {{ $parcel->customer_contact_number }},
                                                            {{ $parcel->customer_address }}
                                                        </td>

                                                        <td class="text-center">
                                                            <select name="district_id[{{ $key }}]"
                                                                class="form-control select2 district" style="width: 100%">
                                                                <option value="0">
                                                                    Select District</option>
                                                                @foreach ($districts as $district)
                                                                    <option value="{{ $district->id }}">
                                                                        {{ $district->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="area_id[{{ $key }}]"
                                                                class="form-control select2 area" style="width: 100%"
                                                                disabled>
                                                                <option value="">Select Area</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <select name="weight_package_id[{{ $key }}]"
                                                                class="form-control select2 weight_package"
                                                                style="width: 100%" disabled>
                                                                <option value="0" data-charge="0">Select
                                                                    Product Weight
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="d-none">
                                                            <input type="hidden" class="merchantid"
                                                                value="{{ $parcel->merchant_id }}" />
                                                        </td>

                                                        <td style="text-align: center">
                                                            <input type="checkbox" name="delete[{{ $key }}]">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="11" class="text-right">
                                                        <input type="submit" value="Save">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                @else
                                    <h5 class="text-center">No Data Found!</h5>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="viewModal">
                    <div class="modal-dialog modal-xl">
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
        $(document).on('change', '.district', function() {
            var $thisRow = $(this).closest('tr'); // Get the current row
            var district_id = $(this).val(); // Get the selected district ID

            $thisRow.find('.area').val(0).attr('disabled', true);
            $thisRow.find('.weight_package').val(0).change().attr('disabled', true);

            var merchant_id = $thisRow.find('.merchantid').val();

            $.ajax({
                cache: false,
                type: "POST",
                dataType: "JSON",
                data: {
                    merchant_id: merchant_id, // Ensure this value is correctly populated
                    district_id: district_id,
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ route('merchant.returnMerchantUpazilaWeightPackageOptionAndCharge') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    if (response.success) {
                        $thisRow.find('.area').html(response.areaOption).attr('disabled', false);
                        $thisRow.find('.weight_package').html(response.weightPackageOption).attr(
                            'disabled', false);
                    } else {
                        toastr.error("Something is wrong");
                    }
                }
            });
        });
    </script>
@endpush
