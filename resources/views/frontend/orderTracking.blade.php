@extends('layouts.frontend.app')

@section('content')
    <!-- Breadcroumb Area -->
    <div class="breadcroumb-area bread-bg " style="background-color: #3dc5f0; margin-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-centered">
                    {{-- <form id="tracking-form" role="search" action="{{ route('frontend.orderTracking') }}" --}}
                    {{-- method="POST"> --}}
                    {{-- @csrf --}}
                    {{-- <div class="form-group" > --}}
                    {{-- <div class="input-group mb-3" style="font-size: 55px;" id="trackingInputBox"> --}}
                    {{-- <input class="form-control" placeholder="Enter tracking number" --}}
                    {{-- type="text" --}}
                    {{-- name="trackingBox" --}}
                    {{-- id="trackingBox" --}}
                    {{-- style="font-size: 30px; border-top-left-radius: 26px; border-bottom-left-radius: 26px;"> --}}
                    {{-- <div class="input-group-append"> --}}
                    {{-- <button class="btn btn-default btn-parcels" type="submit" id="trackingBtn"> --}}
                    {{-- <div class="fa fa-binoculars"></div> --}}
                    {{-- <span class="hidden-xs" > --}}
                    {{-- Track package --}}
                    {{-- </span> --}}
                    {{-- </button> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </form> --}}

                    <form id="tracking-form" class="track-form p-4 shadow" action="{{ route('frontend.orderTracking') }}"
                        method="POST" style="margin-top: 10px;">
                        @csrf
                        <div class="d-flex flex-column flex-md-row">
                            <div class="flex-fill">
                                <div class="track-input d-flex align-items-center">
                                    <label for="trackingBox">
                                        <img height="30" src="{{ asset('assets/img/track-search.jpg') }}"
                                            alt="treack search">
                                    </label>
                                    <input name="trackingBox" id="trackingBox" type="text" class="w-100"
                                        value="{{ $trackingBox ?? '' }}" placeholder="Type your track number">
                                    <input type="submit" class="btn btn-info" value="Track Parcel">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($parcel)
        <!-- about start-->
        <div id="about" class="about-main-block theme-2 d-none">
            <div class="content" style="margin: 20px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Order Tracking</legend>
                                <div class="row mt-2">
                                    <div class="col-md-2 col-lg-2 col-sm-12">
                                        <h4 class="underline" style="font-weight: bold">Tracking ID</h4>
                                        <p style="color: #000000">{{ $parcel->parcel_invoice }}</p>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <ul class="events">
                                            @php
                                                $finalStatus = [];
                                            @endphp

                                            @foreach ($parcelLogs as $parcelLog)
                                                @php
                                                    $to_user = '';
                                                    $from_user = '';
                                                    $status = '';
                                                    $date =
                                                        \Carbon\Carbon::parse($parcelLog->date)->format('F jS, Y') .
                                                        ' ' .
                                                        \Carbon\Carbon::parse($parcelLog->time)->format('h:i a');
                                                    switch ($parcelLog->status) {
                                                        case 5:
                                                        case 6:
                                                        case 7:
                                                        case 4:
                                                        case 8:
                                                        case 9:
                                                        case 1:
                                                            $status = 'Merchant Send Pickup Request';
                                                            $finalStatus[1] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 2:
                                                            $status = 'Parcel Hold';
                                                            $finalStatus[2] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 3:
                                                            $status = 'Parcel Cancel';
                                                            $finalStatus[3] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 11:
                                                        case 12:
                                                        case 13:
                                                        case 10:
                                                            $status = 'Pickup Complete';
                                                            $finalStatus[11] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 16:
                                                        case 17:
                                                        case 14:
                                                            $status =
                                                                'Delivery Branch(' .
                                                                optional($parcelLog->delivery_branch)->name .
                                                                ') Receive Parcel';
                                                            $finalStatus[14] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 15:
                                                            $status = 'Assign Delivery Branch Reject';
                                                            $finalStatus[15] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 18:
                                                            $status = 'Delivery Run Cancel';
                                                            $finalStatus[18] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 19:
                                                            $status =
                                                                'Delivery Rider (' .
                                                                $parcelLog->delivery_rider->name .
                                                                ' - ' .
                                                                $parcelLog->delivery_rider->contact_number .
                                                                ') Start Run';
                                                            $finalStatus[19] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 20:
                                                            $status = 'Delivery Run Rider Reject';
                                                            $finalStatus[20] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 25:
                                                            if ($parcel->delivery_type == 3) {
                                                                $status =
                                                                    'Delivery Rescheduled (Date: ' .
                                                                    \Carbon\Carbon::parse(
                                                                        $parcelLog->reschedule_parcel_date,
                                                                    )->format('F jS, Y') .
                                                                    ')';
                                                                $finalStatus[24] = [
                                                                    'date' => $date,
                                                                    'status' => $status,
                                                                ];
                                                                break;
                                                            }
                                                        case 25:
                                                            if ($parcel->delivery_type == 4) {
                                                                $status =
                                                                    'Delivery Cancel (Note: ' .
                                                                    $parcel->parcel_note .
                                                                    ')';
                                                                $finalStatus[24] = [
                                                                    'date' => $date,
                                                                    'status' => $status,
                                                                ];
                                                                break;
                                                            }
                                                        case 25:
                                                            if ($parcel->delivery_type == 2) {
                                                                $status = 'Delivery  Complete (Partial)';
                                                                $finalStatus[24] = [
                                                                    'date' => $date,
                                                                    'status' => $status,
                                                                ];
                                                                break;
                                                            }
                                                        case 25:
                                                        case 21:
                                                            $status = 'Delivery  Complete';
                                                            $finalStatus[25] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 22:
                                                            $status = 'Delivery Rider Partial Delivery';
                                                            $finalStatus[22] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 23:
                                                            $status = 'Delivery Rider Reschedule';
                                                            $finalStatus[23] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 24:
                                                            $status = 'Delivery Rider Return';
                                                            $finalStatus[24] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 26:
                                                            $status = 'Return Branch Assign';
                                                            $finalStatus[26] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 27:
                                                            $status = 'Return Branch Assign Cancel';
                                                            $finalStatus[27] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 28:
                                                            $status = 'Return Branch Assign Received';
                                                            $finalStatus[28] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 29:
                                                            $status = 'Return Branch Assign Reject';
                                                            $finalStatus[29] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 30:
                                                            $status = 'Return Branch   Run Create';
                                                            $finalStatus[30] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 31:
                                                            $status = 'Return Branch  Run Start';
                                                            $finalStatus[31] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 32:
                                                            $status = 'Return Branch  Run Cancel';
                                                            $finalStatus[32] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 33:
                                                            $status = 'Return Rider Accept';
                                                            $finalStatus[33] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 34:
                                                            $status = 'Return Rider Reject';
                                                            $finalStatus[34] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 35:
                                                            $status = 'Return Rider Complete';
                                                            $finalStatus[35] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                        case 36:
                                                            $status = 'Return Branch  Run Complete';
                                                            $finalStatus[36] = [
                                                                'date' => $date,
                                                                'status' => $status,
                                                            ];
                                                            break;
                                                    }
                                                @endphp
                                            @endforeach
                                            {{-- @dd($finalStatus) --}}
                                            @foreach ($finalStatus as $item)
                                                <li>
                                                    <time></time>
                                                    <span><strong>{{ $item['status'] }}</strong> {{ $item['date'] }}</span>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <h4 style="font-weight: bold">Customer And Order Information</h4>
                                        <table class="table table-striped borderless" style="font-size: 100%;">
                                            <tr>
                                                <th style="width: 40%">Parcel ID</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%">{{ $parcel->parcel_invoice }}</td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Name</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 40%"> Address</th>
                                                <td style="width: 10%"> :</td>
                                                <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- about end-->
    @else
    @endif
        

    <section class="container mt-4">
        {{-- <h4 class="text-center text-white w-50 mx-auto mt-3 py-3 title">Parcel View (Admin, Accounts,
          Branch, Mercahnt Panel)
        </h4> --}}
        <div class="d-flex flex-wrap justify-content-between section1 py-3 px-4">
            <div>
                <h4 class="font-weight-bold">Consignment ID - {{ $parcel->parcel_invoice }}</h4>
                <h5>Created Date- {{ $parcel->created_at->format('d-m-Y') }}</h5>
            </div>
            @php
                $deliveryStatus = [
                    1 => 'Delivered',
                    2 => 'Partial Delivered',
                    3 => 'Rescheduled',
                    4 => 'Return',
                ];

                $deliveryStatusDate = App\Models\ParcelLog::where('parcel_id', $parcel->id)
                    ->where('delivery_type', $parcel->delivery_type)
                    ->first();
            @endphp
            <div>
                <h4 class="font-weight-bold">Delivery Status - {{ $deliveryStatus[$parcel->delivery_type] }}</h4>
                <h5>Action Date - {{ \Carbon\Carbon::parse($deliveryStatusDate?->date)->format('d/m/Y') }}</h5>
            </div>
            <div>
                <h4 class="font-weight-bold">Payment Status - {{ $parcel->payment_type == 5 ? 'Paid' : 'Unpaid' }}
                    </h3>
                    @if ($parcel->payment_type == 5)
                        <h5>Action Date & Payment ID -
                            {{ $parcel?->merchantDeliveryPayment?->created_at->format('d-m-Y h:i A') }}</h5>
                    @endif
            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-between section2 mt-4">
            <div>
                <h4 class="font-weight-bold">Merchant Details</h4>
                <h4 class="font-weight-bold">{{ $parcel->merchant_shops->shop_name }} -
                    {{ $parcel->merchant->contact_number }}</h4>
                <h5>{{ $parcel->merchant->address }}</h5>
                <h5>{{ $parcel->pickup_address }}</h5>
            </div>
            <div class="section2-right">
                <h4 class="font-weight-bold">Parcel Details</h4>
                <div class="d-flex">
                    <div class="section2-card">
                        <h5 class="d-flex justify-content-between">Merchant Oder ID <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5>{{ $parcel->merchant_order_id ?? ' --- ' }}</h5>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="section2-card">
                        <h5 class="d-flex justify-content-between">Products Details <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5>{{ $parcel->product_details ?? ' --- ' }}</h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div style="width: 250px;">
                        <h5 class="d-flex justify-content-between">Exchange Parcel <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5> {{ $parcel->exchange }} </h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div style="width: 250px;">
                        <h5 class="d-flex justify-content-between">Product Weight <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5> {{ $parcel->weight_package->name }} </h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div style="width: 250px;">
                        <h5 class="d-flex justify-content-between">Amount to be Collect <span class="px-4">:</span></h5>
                    </div>
                    <div>
                        <h5><strong>{{ number_format($parcel->total_collect_amount, 2) }} Tk</strong></h5>
                    </div>
                </div>

                <div class="d-flex">
                    <div style="width: 250px;">
                        <h5 class="d-flex justify-content-between">Collected Amount <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5><strong>{{ number_format($parcel->customer_collect_amount, 2) }} Tk</strong></h5>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-flex flex-wrap justify-content-between section3 mt-4">
            <div>
                <h4 class="font-weight-bold">Customer Detials</h4>
                <h4 class="font-weight-bold">{{ $parcel->customer_name }}</h4>
                <h5>{{ $parcel->customer_contact_number }}</h5>
                @if ($parcel->customer_contact_number2)
                    <h5>{{ $parcel->customer_contact_number2 }}</h5>
                @endif
                <h5>{{ $parcel->customer_address }}</h5>
                <h5>{{ $parcel->district->name }}</h5>
                <h5>{{ $parcel->area->name }}</h5>
            </div>
            <div class="section3-right">
                <h4 class="font-weight-bold">Service Charge Detials</h4>
                <div class="d-flex">
                    <div class="section3-card">
                        <h5 class="d-flex justify-content-between">Delivery Charge <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5>{{ number_format($parcel->delivery_charge, 2) }} Tk</h5>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="section3-card">
                        <h5 class="d-flex justify-content-between">Aditional Weight Charge <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5> {{ number_format($parcel->weight_package_charge) }} Tk</h5>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="section3-card">
                        <h5 class="d-flex justify-content-between">COD Charge <span class="px-4">:</span></h5>
                    </div>
                    <div>
                        <h5> {{ number_format($parcel->cod_charge, 2) }} Tk</h5>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="section3-card">
                        <h5 class="d-flex justify-content-between">Return Charge <span class="px-4">:</span>
                        </h5>
                    </div>
                    <div>
                        <h5> {{ number_format($parcel->return_charge, 2) }} Tk</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="px-lg-5 py-2 text-white text-center parcel-log">
                <p class="m-0">(ID)-Parcel Log</p>
            </div> --}}

        {{-- @foreach ($parcelLogs as $parcelLog)
                            @php
                                $parcelLogStatus = returnParcelLogStatusNameForAdmin(
                                    $parcelLog,
                                    $parcel->delivery_type,
                                );

                                $to_user = $parcelLogStatus['to_user'];
                                $from_user = $parcelLogStatus['from_user'];
                                $status = $parcelLogStatus['status_name'];

                            @endphp
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($parcelLog->date)->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($parcelLog->time)->format('H:i:s') }}
                                </td>
                                <td> {{ $status }} </td>
                                <td> {{ $to_user }} </td>
                                <td> {{ $from_user }} </td>
                            </tr>
                        @endforeach --}}

        @php
            $logsGroupedByDate = $parcelLogs
                ->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
                })
                ->sortKeysDesc();
        @endphp

        @foreach ($logsGroupedByDate as $key => $items)
            <div class="d-flex flex-wrap align-items-center">
                <h5 class="px-4 py-1 mt-3" style="border:2px solid green; border-radius: 10px;">
                    {{ \Carbon\Carbon::parse($key)->format('jS F, Y') }}
                </h5>
            </div>

            <div class="d-flex justify-content-end align-items-center flex-column text-left mt-2 position-relative"
                style="gap: 10px;">
                @foreach ($items as $item)
                    @php
                        $parcelLogStatus = returnParcelLogStatusNameForAdmin($item, $parcel->delivery_type);

                        if (!isset($parcelLogStatus['to_user'])) {
                            continue;
                        }

                        $to_user = $parcelLogStatus['to_user'];
                        $from_user = $parcelLogStatus['from_user'];
                        $status = $parcelLogStatus['status_name'];
                        $sub_title = $parcelLogStatus['sub_title'];
                    @endphp

                    <div class="d-flex flex-wrap align-items-center py-2 px-3 w-100"
                        style="background-color: rgb(211, 204, 194); border: 2px solid #a2a2a2;">
                        <div class="d-flex align-items-center flex-wrap section4-card">
                            <h5>{{ \Carbon\Carbon::parse($item->time)->format('h:i A') }}</h5>
                            <div>
                                <h4>{{ $status }}
                                </h4>
                                <h5 class="font-weight-normal">{{ $sub_title }} <span
                                        style="font-size: 18px; color: red;">
                                    </span></h5>
                            </div>
                        </div>
                        <h6>By – {{ $to_user }}
                        </h6>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- <div class="d-flex flex-wrap align-items-center my-4 section5">
            <button class="px-5 py-2 text-white text-center section5-btn1">
                <p class="m-0"><strong>See all Updates</strong></p>
            </button>
            <button class="px-5 py-2 text-center ml-5 section5-btn2">
                <p class="m-0"><strong>See Lates Updates</strong></p>
            </button>
        </div> --}}

        <div class="section6 mt-3">
            <h5 class="fs-1 font-weight-bold">Share Tracking Details</h5>
            <div class="d-flex flex-wrap justify-content-between w-100 " style="gap: 30px;">
                <div id="" class="d-flex flex-column  justify-content-center  w-100"
                    style=" border: 2px dotted #f87326; background-color: #eac0a8; padding: 10px;">
                    <p id="trackingText" class="p-0 m-0 font-weight-bold fs-1">
                        {{ route('frontend.orderTracking') . '?trackingBox=' . $parcel->parcel_invoice }}</p>
                </div>
                <button id="copyButton" class="text-white font-weight-bolder py-2 px-4 border-0"
                    style="background-color: #f87326; font-size: 18px; border-radius: 5px;">COPY</button>
            </div>
            <p class="w-lg-50" style="color: #959290;">You can share the above public link with the recipient.
                They can view the
                tracking
                details from the link.
            </p>
        </div>
    </section>

    <section class="mb-4 container d-none">
        <div class="mt-4" style="border: 4px solid #020230; padding: 10px 40px;">
            <div class="d-flex flex-wrap align-items-start mt-3 section7-card">
                <p class="py-1 px-2" style="background-color: #bfbfbf; font-weight: 600;">11.Time</p>
                <div class="py-1 px-2">
                    <h4 style="font-size: 18px; font-weight: 700;">Payment has been disbursed to Merchant</h4>
                    <h4 style="font-size: 16px; font-weight: 400;">Payment Invoice ID-MPAY-1003<span
                            style="font-size: 12px; color: red;">
                            (Payment
                            Status ID:2)</span></h4>
                </div>
                <p class="py-1 px-2 m-0" style="background-color: #bfbfbf; font-weight: 600;">
                    By Action Creator
                </p>
            </div>

            <div class="d-flex flex-wrap align-items-start mt-3 section7-card">
                <p class="py-1 px-2" style="background-color: #bfbfbf; font-weight: 600;">11.Time</p>
                <div class="py-1 px-2">
                    <h4 style="font-size: 18px; font-weight: 700;">Cash Deposited to Accounts<span
                            style="font-size: 12px; color: red;">
                            (Payment
                            Status ID:5)</span></h4>
                </div>
                <p class="py-1 px-2 m-0" style="background-color: #bfbfbf; font-weight: 600;">
                    By Action Creator
                </p>
            </div>

            <div class="d-flex flex-wrap align-items-start mt-3 section7-card">
                <p class="py-1 px-2" style="background-color: #bfbfbf; font-weight: 600;">11.Time</p>
                <div class="py-1 px-2">
                    <h4 style="font-size: 18px; font-weight: 700;">On the Way to Delivery<span
                            style="font-size: 12px; color: red;">
                            (Payment
                            Status ID:21)</span></h4>
                    <h style="font-size: 16px; font-weight: 400;">Rider Name – Branch Name – Contact No.</h>4
                </div>
                <p class="py-1 px-2 m-0" style="background-color: #bfbfbf; font-weight: 600;">
                    By Action Creator
                </p>
            </div>
            <div class="d-flex flex-wrap align-items-start mt-3 section7-card">
                <p class="py-1 px-2" style="background-color: #bfbfbf; font-weight: 600;">11.Time</p>
                <div class="py-1 px-2">
                    <h4 style="font-size: 18px; font-weight: 700;">Assigned to Rider <span
                            style="font-size: 12px; color: red;">
                            (Payment
                            Status ID:19)</span></h4>
                </div>
                <p class="py-1 px-2 m-0" style="background-color: #bfbfbf; font-weight: 600;">
                    By Action Creator
                </p>
            </div>
        </div>
    </section>


@endsection

@push('style_css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .title {
            background-color: #f87326;
            border: 2px solid green;
            border-radius: 10px;
        }

        .section1 {
            border-radius: 20px;
            border: 2px solid #f87326;
        }

        .section1 h4 {
            font-size: 25px;
        }

        .section2 {
            border-radius: 25px;
            border: 2px solid #f87326;
            padding: 20px 40px;
        }

        .section2 .section2-right {
            width: 400px;
        }

        .section2-card {
            width: 250px;
        }

        .section3 {
            border-radius: 25px;
            border: 2px solid #f87326;
            padding: 20px 40px;
        }

        .section3 .section3-right {
            width: 400px;
        }

        .section3-card {
            width: 280px;
        }

        .parcel-log {
            background-color: #f87326;
            border: 2px solid green;
            border-radius: 10px;
            width: 250px;
            margin: auto;
        }

        .section4-card {
            width: 550px;
            gap: 30px;
        }

        .section5 {
            width: 800px;
            margin-left: auto;
        }

        .section5 .section5-btn1 {
            background-color: #f87326;
            border: 2px solid green;
            border-radius: 10px;
            width: 250px;
        }

        .section5 .section5-btn2 {
            background-color: #ffffff;
            border: 2px solid green;
            border-radius: 10px;
            width: 250px;
        }

        .section6 {
            width: 900px;
        }

        .section7-card {
            gap: 32px;
            width: 780px;
        }

        .section7-card>div {
            background-color: #bfbfbf;
            font-weight: 600;
            width: 400px;
        }

        .line {
            position: absolute;
            top: -34px;
            left: 110px;
            width: 2px;
            height: 100%;
            background-color: #2246d5;
        }

        .line1 {
            width: 83px;
            height: 2px;
            background-color: #2246d5;
            position: absolute;
            left: 0;
        }

        .line1 {
            top: 20%;
        }

        @media (max-width: 768px) {
            .title {
                width: 100% !important;
            }

            .section1>div {
                width: 100%;
            }

            .section1 h4 {
                font-size: 16px;
                text-align: center;
            }

            .section1 h3 {
                font-size: 18px;
                text-align: center;
            }


            .section2 {
                padding: 10px;
            }

            .section2 .section2-right {
                width: 100%;
            }

            .section2-card {
                width: 220px;
            }

            .section3 {
                padding: 10px;
            }

            .parcel-log {
                border-radius: 10px;
                width: 200px;
                margin: 0;
            }

            .line {
                display: none;
            }


            .section4-card {
                width: 100%;
            }

            .section5 {
                width: 100%;
                margin-left: 0;
            }

            .section5 .section5-btn1 {
                width: 100%;
            }

            .section5 .section5-btn2 {
                width: 100%;
                margin-top: 8px;
            }

            .section6 {
                width: 100%;
            }

            .section6>div {
                width: 100%;
                gap: 5px;
            }

            .section7-card {
                gap: 12px;
                width: 100%;
            }

            .section7-card>div {
                width: 100%;
            }

        }

        .underline:after {
            background-color: #20b249;
            bottom: -10px;
            height: 4px;
            width: 100px;
            position: relative;
            content: "";
            display: block;
        }

        .borderless td,
        .borderless th {
            border: none;
        }

        table {
            border: none;
        }

        fieldset {
            border: 2px solid #007bff96 !important;
            margin: 0 !important;
            xmin-width: 0 !important;
            padding: 3px !important;
            position: relative !important;
            border-radius: 4px !important;
            background-color: #f5f5f5 !important;
            padding-left: 10px !important;
            margin-bottom: 7px !important;
        }

        legend {
            font-size: 18px !important;
            font-weight: bold !important;
            margin-bottom: 0px !important;
            width: 50% !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            padding: 1px 1px 1px 10px !important;
            background-color: #cceed6 !important;
        }

        .events li {
            display: flex;
            color: #1e1e1e;
        }

        .events time {
            position: relative;
            padding: 0 .5em;
        }

        .events time::after {
            content: "";
            position: absolute;
            z-index: 2;
            right: 0;
            top: 0;
            transform: translateX(50%);
            border-radius: 50%;
            background: #000000;
            border: 1px #ff0000 solid;
            width: .8em;
            height: .8em;
        }


        .events span {
            padding: 0 1.5em 1.5em 1.5em;
            position: relative;
        }

        .events span::before {
            content: "";
            position: absolute;
            z-index: 1;
            left: -0.5px;
            height: 100%;
            border-left: 2px #ff0000 solid;
        }

        .events strong {
            display: block;
            font-weight: bolder;
        }

        .events {
            margin: 1em;
        }

        .events,
        .events *::before,
        .events *::after {
            box-sizing: border-box;
            font-family: arial;
        }
    </style>
@endpush

<script>
    $(document).ready(function() {
        $('#copyButton').click(function() {
            // Get the text from the <p> tag
            var textToCopy = $('#trackingText').text();

            // Create a temporary <textarea> element
            var tempTextarea = $('<textarea>');
            $('body').append(tempTextarea);

            // Set the text inside the <textarea> and select it
            tempTextarea.val(textToCopy).select();

            // Copy the selected text to the clipboard
            document.execCommand('copy');

            // Remove the temporary <textarea> element
            tempTextarea.remove();

            // Optionally, show a message to confirm the action
            alert('Text copied to clipboard!');
        });
    });
</script>
