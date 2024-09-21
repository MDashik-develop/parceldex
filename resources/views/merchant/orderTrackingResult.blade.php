<div class="content" style="margin-top: 20px;">
    <div class="container-fluid">
        <div class="parcel-details-container">
            <div class="header">
                <div class="header-item strong-header">
                    Consignment ID - {{ $parcel->parcel_invoice }}<br>Created Date -
                    {{ $parcel->created_at->format('d-m-Y') }}
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
                    <div class="header-item strong-header">Current Delivery Status -
                        {{ $deliveryStatus[$parcel->delivery_type] }}<br>Action Date -
                        {{ \Carbon\Carbon::parse($deliveryStatusDate?->date)->format('d/m/Y') }}</div>
                @endif

                <div class="header-item strong-header">Current Payment Status -
                    {{ $parcel->payment_type == 5 ? 'Paid' : 'Unpaid' }}<br>
                    @if ($parcel->payment_type)
                        Action Date & Payment ID -
                        $parcel?->merchantDeliveryPayment?->created_at->format('d-m-Y h:i A') -
                        $parcel?->merchantDeliveryPayment?->merchant_payment_invoice
                    @endif
                </div>
            </div>

            <div class="section">
                <div class="merchant-details">
                    <h3>Merchant Details</h3>
                    <p><strong>{{ $parcel->merchant->company_name }} -
                            {{ $parcel->merchant->contact_number }}</strong></p>
                    <p>{{ $parcel->merchant->address }}</p>
                    <p>{{ $parcel->pickup_address }}</p>
                </div>
                <div class="parcel-details">
                    <h3>Parcel Details</h3>
                    <p>Merchant Oder ID: {{ $parcel->merchant_order_id ?? ' --- ' }}</p>
                    <p>Products Details: {{ $parcel->product_details ?? ' --- ' }}</p>
                    <p>Exchange Parcel: {{ $parcel->exchange }}</p>
                    <p>Product Weight: {{ $parcel->weight_package->name }}</p>
                    <p>Amount to be Collect: <strong>{{ number_format($parcel->total_collect_amount, 2) }}
                            Tk</strong></p>
                    <p>Collected Amount: <strong>{{ number_format($parcel->customer_collect_amount, 2) }}
                            Tk</strong></p>
                </div>
            </div>

            <div class="section">
                <div class="customer-details">
                    <h3>Customer Details</h3>
                    <p><strong>{{ $parcel->customer_name }}</strong></p>
                    <p>{{ $parcel->customer_contact_number }}</p>
                    @if ($parcel->customer_contact_number2)
                        <p>{{ $parcel->customer_contact_number2 }}</p>
                    @endif

                    <p>{{ $parcel->customer_address }}, {{ $parcel->district->name }}, {{ $parcel->area->name }}
                    </p>
                    <p>Banasree-Banasree HUB</p>
                </div>
                <div class="service-charge-details">
                    <h3>Service Charge Details</h3>
                    <p>Delivery Charge: {{ number_format($parcel->delivery_charge, 2) }} Tk</p>
                    <p>Additional Weight Charge: {{ number_format($parcel->weight_package_charge) }} Tk</p>
                    <p>COD Charge: {{ number_format($parcel->cod_charge, 2) }} Tk</p>
                    <p>Return Charge: {{ number_format($parcel->return_charge, 2) }} Tk</p>
                </div>
            </div>

            @php
                $logsGroupedByDate = $parcelLogs
                    ->groupBy(function ($item) {
                        return \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
                    })
                    ->sortKeysDesc();
            @endphp

            @foreach ($logsGroupedByDate as $key => $items)
                <div class="parcel-log">
                    <h3><span class="log-date">{{ \Carbon\Carbon::parse($key)->format('jS F, Y') }}</span></h3>

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
                        <div class="log-item">
                            <span class="log-time">{{ \Carbon\Carbon::parse($item->time)->format('h:i A') }}</span>
                            {{ $status }}<br>
                            <span class="log-details">{{ $sub_title }} By - {{ $to_user }}</span>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="section6 mt-3">
                <h5 class="fs-1 font-weight-bold">Share Tracking Details</h5>
                <div class="d-flex flex-wrap justify-content-between w-100 " style="gap: 30px;">
                    <div id="" class="d-flex flex-column  justify-content-center"
                        style=" border: 2px dotted #f87326; background-color: #eac0a8; padding: 10px; width: 88%">
                        <p id="trackingText" class="p-0 m-0 font-weight-bold fs-1">
                            {{ route('frontend.orderTracking') . '?trackingBox=' . $parcel['tracking_id'] }}</p>
                    </div>
                    <button id="copyButton" class="text-white font-weight-bolder py-2 px-4 border-0"
                        style="background-color: #f87326; font-size: 18px; border-radius: 5px;">COPY</button>
                </div>
                <p class="w-lg-50 mt-3" style="color: #959290;">You can share the above public link with the
                    recipient.
                    They can view the
                    tracking
                    details from the link.
                </p>
            </div>

        </div>
    </div>
</div>

<style>
    .parcel-details-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        max-width: 1200px;
        margin: 0 auto;
    }

    .header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        text-align: center;
    }

    .header-item {
        flex: 1;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin: 0 5px;
    }

    .strong-header {
        font-weight: bold;
    }

    .section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .section div {
        flex: 1;
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        margin: 0 10px;
    }

    .parcel-details-container h3 {
        font-size: 22px;
        margin-top: 0;
        color: #ff6600;
    }

    .parcel-log {
        margin-top: 20px;
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
    }

    .log-date {
        display: inline-block;
        padding: 3px 10px;
        background-color: #99cc99;
        border-radius: 8px;
        color: #fff;
        font-weight: bold;
    }

    .log-item {
        margin-top: 10px;
        padding: 10px;
        border-left: 3px solid #ff6600;
        background-color: #f9f9f9;
        border-radius: 5px;
    }

    .log-time {
        font-weight: bold;
        display: block;
    }

    .log-details {
        margin-top: 5px;
        color: #555;
    }

    @media (max-width: 768px) {

        .header,
        .section {
            flex-direction: column;
        }

        .header-item,
        .section div {
            margin: 10px 0;
        }
    }
</style>
