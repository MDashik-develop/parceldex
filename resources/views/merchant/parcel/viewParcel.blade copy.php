<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row d-none">
            <div class="col-md-12">
                <fieldset>
                    <legend>Parcel Information</legend>
                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%">Invoice </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        <span id="invoiceContainer">
                                            {{ $parcel->parcel_invoice }}
                                            <i class="fas fa-print" onclick="copyInvoice()"
                                                style="margin-left: 10px;"></i>

                                        </span>
                                    </td>
                                </tr>
                                <!--<tr>-->
                                <!--    <th style="width: 40%">Invoice </th>-->
                                <!--    <td style="width: 10%"> : </td>-->
                                <!--    <td style="width: 50%"> {{ $parcel->parcel_invoice }} </td>-->
                                <!--</tr>-->
                                <tr>
                                    <th style="width: 40%">Merchant Order ID </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->merchant_order_id }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                        {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }}
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th style="width: 40%">Product Details  </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->product_details }} </td>
                                </tr> --}}
                                @if ($parcel->service_type)
                                <tr>
                                    <th style="width: 40%">Service Type </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ optional($parcel->service_type)->title }} </td>
                                </tr>
                                @endif
                                @if ($parcel->item_type)
                                <tr>
                                    <th style="width: 40%">Item Type </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ optional($parcel->item_type)->title }} </td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="width: 40%">Product Value </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->product_value }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Product details </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->product_details }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Remark </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->parcel_note }} </td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Parcel Charge </legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%">Weight Package </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->weight_package->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Weight Charge </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->weight_package_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Delivery Charge </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->delivery_charge }} </td>
                                    </tr>

                                    {{-- @if ($parcel->cod_charge != 0 && $parcel->total_collect_amount) --}}
                                    <tr>
                                        <th style="width: 40%">COD Percent </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->cod_percent }} % </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">COD Charge </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->cod_charge }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Collection Amount </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->total_collect_amount }} </td>
                                    </tr>
                                    {{-- @endif --}}

                                    <tr>
                                        <th style="width: 40%">Total Charge </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->total_charge }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>

                    <div class="col-md-12 row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Merchant Information</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name</th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant->name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant->contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->merchant->address }} </td>
                                    </tr>
                                    <!--<tr>-->
                                    <!--    <th style="width: 40%"> Shop </th>-->
                                    <!--    <td style="width: 10%"> : </td>-->
                                    <!--    <td style="width: 50%"> {{ $parcel->merchant_shops->shop_name }} </td>-->
                                    <!--</tr>-->
                                    <tr>
                                        <th style="width: 40%"> Pickup Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->pickup_address }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Customer Information</legend>
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Name</th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->customer_name }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Contact </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->customer_contact_number }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> Address </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->customer_address }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%"> District </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->district->name }} </td>
                                    </tr>
                                    {{-- <tr>
                                        <th style="width: 40%"> Thana/Upazila </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->upazila->name }} </td>
                                    </tr> --}}
                                    <tr>
                                        <th style="width: 40%"> Area </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $parcel->area->name }} </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-12 row">
                @if (!empty($parcel->pickup_branch))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Pickup Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Pickup Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    {{ \Carbon\Carbon::parse($parcel->pickup_branch_date)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_branch->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif

                @if (!empty($parcel->pickup_rider))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Pickup Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Pickup Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    {{ \Carbon\Carbon::parse($parcel->pickup_rider_date)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->contact_number }} </td>
                            </tr>
                            <!--                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->pickup_rider->address }} </td>
                            </tr>-->
                        </table>
                    </fieldset>
                </div>
                @endif

                @if (!empty($parcel->delivery_branch))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Delivery Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    {{ \Carbon\Carbon::parse($parcel->delivery_branch_date)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_branch->name }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_branch->contact_number }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_branch->address }} </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                @endif

                @if (!empty($parcel->delivery_rider))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Delivery Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%">
                                    {{ \Carbon\Carbon::parse($parcel->delivery_rider_date)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->name ?? '' }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->contact_number ?? '' }} </td>
                            </tr>
                            <!--                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->address ?? '' }} </td>
                            </tr>-->
                        </table>
                    </fieldset>
                </div>
                @endif
            </div>
        </div>

        <section class="container">
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
                @if ($parcel->delivery_type)
                <div>
                    <h4 class="font-weight-bold">Delivery Status - {{ $deliveryStatus[$parcel->delivery_type] }}
                    </h4>
                    <h5>Action Date - {{ \Carbon\Carbon::parse($deliveryStatusDate?->date)->format('d/m/Y') }}</h5>
                </div>
                @endif
                <div>
                    <h4 class="font-weight-bold">Payment Status - {{ $parcel->payment_type == 5 ? 'Paid' : 'Unpaid' }}
                        </h3>
                        @if ($parcel->payment_type == 5)
                        <h5>Action Date & Payment ID -
                            {{ $parcel?->merchantDeliveryPayment?->created_at->format('d-m-Y h:i A') }}
                        </h5>
                        @endif
                </div>
            </div>
            <div class="d-flex flex-wrap justify-content-between section2 mt-4">
                <div>
                    <h4 class="font-weight-bold">Merchant Details</h4>
                    <h4 class="font-weight-bold">{{ $parcel->merchant_shops->shop_name }} -
                        {{ $parcel->merchant->contact_number }}
                    </h4>
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
                        <div class="xxx">
                            <h5 class="d-flex justify-content-between">Exchange Parcel <span class="px-4">:</span>
                            </h5>
                        </div>
                        <div>
                            <h5> {{ $parcel->exchange }} </h5>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="xxx">
                            <h5 class="d-flex justify-content-between">Product Weight <span class="px-4">:</span>
                            </h5>
                        </div>
                        <div>
                            <h5> {{ $parcel->weight_package->name }} </h5>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="xxx">
                            <h5 class="d-flex justify-content-between">Amount to be Collect <span
                                    class="px-4">:</span></h5>
                        </div>
                        <div>
                            <h5><strong>{{ number_format($parcel->total_collect_amount, 2) }} Tk</strong></h5>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="xxx">
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
                            <h5 class="d-flex justify-content-between">Aditional Weight Charge <span
                                    class="px-4">:</span></h5>
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
                $parcelLogStatus = returnParcelLogStatusNameForAdmin($item, $parcel->delivery_type, $parcel);

                if (!isset($parcelLogStatus['to_user'])) {
                continue;
                }

                $to_user = $parcelLogStatus['to_user'];
                $from_user = $parcelLogStatus['from_user'];
                $status = $parcelLogStatus['status_name'];
                $sub_title = $parcelLogStatus['sub_title'];
                @endphp

                <div class="d-flex flex-wrap align-items-center py-2 px-3 w-100"
                    style="background-color: #e7e6e6; border: 1px solid #b4b4b4;">
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
                    <div id="" class="d-flex flex-column  justify-content-center"
                        style=" border: 2px dotted #f87326; background-color: #eac0a8; padding: 10px; width: 86%">
                        <p id="trackingText" class="p-0 m-0 font-weight-bold fs-1">
                            {{ route('frontend.orderTracking') . '?trackingBox=' . $parcel['tracking_id'] }}
                        </p>
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

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td,
    .table-style th {
        padding: .1rem !important;
    }

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

    .xxx {
        width: 250px;
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
        width: 185px;
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
        .xxx {
            width: 200px;
        }

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
</style>

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

    function copyInvoice() {
        // Get the text content of the invoice element
        var invoiceText = document.getElementById('invoiceContainer').textContent.trim();

        // Create a temporary input element
        var tempInput = document.createElement('input');
        tempInput.value = invoiceText;

        // Append the input element to the document
        document.body.appendChild(tempInput);

        // Select the text in the input element
        tempInput.select();
        tempInput.setSelectionRange(0, 99999); /* For mobile devices */

        // Copy the selected text
        document.execCommand('copy');

        // Remove the temporary input element
        document.body.removeChild(tempInput);

        // Optionally, provide feedback to the user (e.g., alert or notification)
        alert('Invoice copied: ' + invoiceText);
    }
</script>

<script></script>