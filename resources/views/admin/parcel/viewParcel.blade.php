<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View</h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="parcel-details-container">
        <div class="header">
            <div class="header-item strong-header">
                Consignment ID - {{ $parcel->parcel_invoice }}
                - {{ $parcel->created_at->format('d-m-Y') }}
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

            @php
                $date_time = '---';
                if ($parcel->status >= 25) {
                    if ($parcel->delivery_type == 3) {
                        $date_time = date('Y-m-d', strtotime($parcel->reschedule_parcel_date));
                    } elseif ($parcel->delivery_type == 1 || $parcel->delivery_type == 2) {
                        $date_time = date('Y-m-d', strtotime($parcel->delivery_date));
                    }
                    if ($parcel->delivery_type == 4) {
                        $date_time = $parcel->date;
                    }
                } elseif ($parcel->status == 11 || $parcel->status == 13 || $parcel->status == 15) {
                    $date_time = date('Y-m-d', strtotime($parcel->pickup_branch_date));
                } else {
                    $date_time = $parcel->date;
                }
            @endphp
            {{-- @if ($parcel->delivery_type) --}}
            <div class="header-item strong-header">Current Status -
                @php
                    $parcelStatus = returnParcelStatusNameForBranch(
                        $parcel?->status,
                        $parcel?->delivery_type,
                        $parcel?->payment_type,
                        $parcel,
                    );
                @endphp
                {{ $parcelStatus['status_name'] }} - {{ $date_time }}
                {{-- {{ $deliveryStatus[$parcel?->delivery_type] }}<br>
                    {{ \Carbon\Carbon::parse($deliveryStatusDate?->date)->format('d-m-Y') }} --}}
            </div>
            {{-- @endif --}}

            <div class="header-item strong-header">Current Payment Status -
                {{ $parcel->payment_type == 5 ? 'Paid' : 'Unpaid' }}<br>
                @php
                    $x = '';

                    if ($parcel->payment_type) {
                        $x =
                            $parcel?->merchantDeliveryPayment?->created_at->format('d-m-Y h:i A') .
                            '-' .
                            $parcel?->merchantDeliveryPayment?->merchant_payment_invoice;
                    }

                @endphp

                {{ $x }}
            </div>
        </div>

        <div class="section">
            <div class="merchant-details">
                <h3>Merchant Details</h3>
                <p><strong>{{ $parcel->merchant->company_name }} -
                        {{ $parcel->merchant->contact_number }}</strong></p>
                <p>{{ $parcel?->merchant?->area?->name . ' - ' . $parcel?->merchant?->branch?->name }}</p>

                <h3 class="mt-4">Delivery Branch Details</h3>
                <p>Branch Name: {{ $parcel?->delivery_branch?->name }}</p>
            </div>

            <div class="parcel-details">
                <h3>Parcel Details</h3>
                <section style="display: flex; align-content: center;">
                    <section>
                        <p>Merchant Oder ID</p>
                        <p>Products Details</p>
                        <p>Exchange Parcel</p>
                        <p>Product Weight</p>
                        <p>Amount to be Collect</strong></p>
                        <p>Collected Amount</p>
                    </section>
                    <section class="pl-100">
                        <p>: {{ $parcel->merchant_order_id ?? ' --- ' }}</p>
                        <p>: {{ $parcel->product_details ?? ' --- ' }}</p>
                        <p>: {{ $parcel->exchange }}</p>
                        <p>: {{ $parcel->weight_package->name }}</p>
                        <p>: <strong>{{ number_format($parcel->total_collect_amount, 2) }}
                                Tk</p>
                        <p>: <strong>{{ number_format($parcel->cancel_amount_collection > 0 ? $parcel->cancel_amount_collection : $parcel->customer_collect_amount, 2) }}
                                Tk</strong></p>
                    </section>
            </div>
        </div>

        <div class="section">
            <div class="customer-details">
                <h3>Customer Details</h3>
                <p><strong>{{ $parcel->customer_name }} - {{ $parcel->customer_contact_number }}</strong></p>
                @if ($parcel->customer_contact_number2)
                    <p>Alternative Number {{ $parcel->customer_contact_number2 }}</p>
                @endif

                <p>{{ $parcel->customer_address }}, {{ $parcel->district->name }}, {{ $parcel->area->name }}</p>
                <p>Merchant Instruction: {{ $parcel->parcel_note }}</p>
            </div>
            {{-- <div class="service-charge-details">
                    <h3>Service Charge Details</h3>
                    <p>Delivery Charge: {{ number_format($parcel->delivery_charge, 2) }} Tk</p>
                    <p>Additional Weight Charge: {{ number_format($parcel->weight_package_charge) }} Tk</p>
                    <p>COD Charge: {{ number_format($parcel->cod_charge, 2) }} Tk</p>
                    <p>Return Charge: {{ number_format($parcel->return_charge, 2) }} Tk</p>
                </div> --}}
        </div>

        <div class="section">
            <div class="customer-details">
                @if ($parcel?->pickup_rider)
                    <h3>Pickup Rider Details</h3>
                    <p>Pickup Rider - {{ $parcel?->pickup_rider?->name }} - {{ $parcel?->pickup_rider?->r_id }}</p>
                @endif

                @if ($parcel?->delivery_rider)
                    <h3>Delivery Rider Details</h3>
                    @if ($parcel->status > 24)
                        <p>Delivery Rider - {{ $parcel?->delivery_rider?->name }} -
                            {{ $parcel?->delivery_rider?->r_id }}</p>
                    @else
                        <p>Delivery Rider - {{ $parcel?->delivery_rider?->name }} -
                            {{ $parcel?->delivery_rider?->r_id }} -
                            {{ $parcel?->delivery_rider?->contact_number }}</p>
                    @endif
                @endif

                @if ($parcel?->return_rider)
                    <h3>Return Rider Details</h3>
                    <p>Return Rider - {{ $parcel?->return_rider?->name }} - {{ $parcel?->return_rider?->r_id }}</p>
                @endif

            </div>
            {{-- <div class="service-charge-details">
                    <h3>Service Charge Details</h3>
                    <p>Delivery Charge: {{ number_format($parcel->delivery_charge, 2) }} Tk</p>
                    <p>Additional Weight Charge: {{ number_format($parcel->weight_package_charge) }} Tk</p>
                    <p>COD Charge: {{ number_format($parcel->cod_charge, 2) }} Tk</p>
                    <p>Return Charge: {{ number_format($parcel->return_charge, 2) }} Tk</p>
                </div> --}}
        </div>

        @php
            $logsGroupedByDate = $parcelLogs
                ->groupBy(function ($item) {
                    return \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
                })
                ->sortKeysDesc();
        @endphp

        <!-- Parcel Payment Log -->
        @if (!empty($parcelBranchPaymentDeltails->count() > 0) || !empty($parcelMerchantPaymentDeltails->count() > 0))

            @php
                $mpayment = $parcelMerchantPaymentDeltails->count();
                $bpayment = $parcelBranchPaymentDeltails->count();
            @endphp

            <div class="col-md-12">
                <fieldset>
                    <legend>Parcel Payment Log</legend>
                    <table class="table table-style">
                        <tr>
                            <th style="width: 5%"> #</th>
                            <th style="width: 10%"> Date</th>
                            <th style="width: 10%"> Time</th>
                            <th style="width: 25%"> Status</th>
                            <th style="width: 25%"> To (Action)</th>
                            <th style="width: 25%"> From</th>
                        </tr>

                        <!-- For Merchant Payment -->

                        @if (!empty($parcelMerchantPaymentDeltails->count() > 0))

                            @foreach ($parcelMerchantPaymentDeltails as $parcelMerchantPaymentDeltail)
                                @php
                                    $to_user = '';
                                    $from_user = '';
                                    $status = '';

                                    $merchant_name = $parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment
                                        ->merchant
                                        ? $parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant
                                            ->company_name
                                        : 'Default';
                                    $admin_name = $parcelMerchantPaymentDeltail->admin
                                        ? $parcelMerchantPaymentDeltail->admin->name
                                        : 'Default';

                                    switch ($parcelMerchantPaymentDeltail->status) {
                                        case 1:
                                            $status = 'Invoice Prepared';
                                            $to_user = 'Admin : ' . $admin_name;
                                            $from_user = 'Merchant : ' . $merchant_name;
                                            break;
                                        case 2:
                                            $status = 'Paid';
                                            $to_user = 'Admin : ' . $admin_name;
                                            $from_user = 'Merchant : ' . $merchant_name;
                                            break;
                                        case 3:
                                            $status = 'Payment Invoice Cancel';
                                            $to_user = 'Admin : ' . $admin_name;
                                            $from_user = 'Merchant : ' . $merchant_name;
                                            break;
                                    }

                                @endphp
                                <tr>
                                    <td> {{ $loop->iteration }} </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($parcelMerchantPaymentDeltail->date_time)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($parcelMerchantPaymentDeltail->date_time)->format('H:i:s') }}
                                    </td>
                                    <td> {{ $status . ' (' . $parcelMerchantPaymentDeltail->parcel_merchant_delivery_payment->merchant_payment_invoice . ')' }}
                                    </td>
                                    <td> {{ $to_user }} </td>
                                    <td> {{ $from_user }} </td>
                                </tr>
                            @endforeach
                        @endif

                        <!-- For Branch Payments -->
                        @if (!empty($parcelBranchPaymentDeltails->count() > 0))

                            @foreach ($parcelBranchPaymentDeltails as $parcelBranchPaymentDetail)
                                @php
                                    $to_user = '';
                                    $from_user = '';
                                    $status = '';

                                    $branch_name = $parcelBranchPaymentDetail->parcel_delivery_payment->branch
                                        ? $parcelBranchPaymentDetail->parcel_delivery_payment->branch->name
                                        : 'Default';
                                    $branch_user = $parcelBranchPaymentDetail->parcel_delivery_payment->branch_user
                                        ? ' (' .
                                            $parcelBranchPaymentDetail->parcel_delivery_payment->branch_user->name .
                                            ')'
                                        : ' (Default)';
                                    $admin_name = $parcelBranchPaymentDetail->admin
                                        ? $parcelBranchPaymentDetail->admin->name
                                        : 'Default';

                                    switch ($parcelBranchPaymentDetail->status) {
                                        case 1:
                                            $status = 'Deposit Requested';
                                            $to_user = 'Branch : ' . $branch_name . $branch_user;
                                            $from_user = 'Admin : ' . $admin_name;
                                            break;
                                        case 2:
                                            $status = 'Deposit Accept';
                                            $to_user = 'Branch : ' . $branch_name . $branch_user;
                                            $from_user = 'Admin : ' . $admin_name;
                                            break;
                                        case 3:
                                            $status = 'Deposit Request Declined';
                                            $to_user = 'Branch : ' . $branch_name . $branch_user;
                                            $from_user = 'Admin : ' . $admin_name;
                                            break;
                                    }

                                @endphp
                                <tr>
                                    <td> {{ $loop->iteration + $mpayment }} </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($parcelBranchPaymentDetail->date_time)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($parcelBranchPaymentDetail->date_time)->format('H:i:s') }}
                                    </td>
                                    <td> {{ $status . ' (' . $parcelBranchPaymentDetail->parcel_delivery_payment->payment_invoice . ')' }}
                                    </td>
                                    <td> {{ $to_user }} </td>
                                    <td> {{ $from_user }} </td>
                                </tr>
                            @endforeach
                        @endif

                    </table>
                </fieldset>
            </div>
        @endif

        @foreach ($logsGroupedByDate as $key => $items)
            <div class="parcel-log">
                <h3><span class="log-date">{{ \Carbon\Carbon::parse($key)->format('jS F, Y') }}</span></h3>

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
                    <div class="log-item">
                        <span class="log-time">{{ \Carbon\Carbon::parse($item->time)->format('h:i A') }}</span>
                        {{ $status }}<br>
                        <span class="log-details">Note: {{ empty($sub_title) ? 'N/A' : $sub_title }} <br> By -
                            {{ $to_user }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach


        <div class="section6 mt-3">
            <h5 class="fs-1 font-weight-bold">Share Tracking Details</h5>
            <div class="d-flex flex-wrap justify-content-between w-100 " style="gap: 30px;">
                <div id="" class="d-flex flex-column  justify-content-center ww-80"
                    style=" border: 2px dotted #f87326; background-color: #eac0a8; padding: 10px;">
                    <p id="trackingText" class="p-0 m-0 font-weight-bold fs-1">
                        {{ route('frontend.orderTracking') . '?trackingBox=' . $parcel['tracking_id'] }}</p>
                </div>
                <button id="copyButton" class="text-white font-weight-bolder py-2 px-4 border-0"
                    style="background-color: #f87326; font-size: 18px; border-radius: 5px;">COPY</button>
            </div>
            <p class="w-lg-50 mt-3" style="color: #959290;">You can share the above public link with the recipient.
                They can view the
                tracking
                details from the link.
            </p>
        </div>
    </div>


</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .parcel-details-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        max-width: 900px;
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
        margin-bottom: 10px;
    }

    .ww-80 {
        width: 80%;
    }

    .section div {
        flex: 1;
        padding: 10px;
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

    .merchant-details h3 {
        font-size: 22px;
        margin-top: 0;
        color: #ff6600;
    }

    .parcel-details h3 {
        font-size: 22px;
        margin-top: 0;
        color: #ff6600;
    }

    .customer-details h3 {
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
        padding: 1px 10px;
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

    .pl-100 {
        padding-left: 40px;
    }

    p {
        margin-bottom: 0.3rem;
    }

    @media (max-width: 768px) {

        .ww-80 {
            width: 100%;
        }

        .header,
        .section {
            flex-direction: column;
        }

        .header-item,
        .section div {
            margin: 10px 0;
        }

        .pl-100 {
            padding-left: 20px;
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
