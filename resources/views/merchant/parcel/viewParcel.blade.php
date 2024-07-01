<div class="modal-header bg-default">
    <h4 class="modal-title">Parcel Information View </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="card-body">
        <div class="row">
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
                                        <i class="fas fa-print" onclick="copyInvoice()" style="margin-left: 10px;"></i>
                        
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
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_date)->format('d/m/Y') }} </td>
                                </tr>
                                {{-- <tr>
                                    <th style="width: 40%">Product Details  </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->product_details }} </td>
                                </tr> --}}
                                @if($parcel->service_type)
                                 <tr>
                                    <th style="width: 40%">Service Type </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ optional($parcel->service_type)->title}} </td>
                                </tr>
                                @endif
                                @if($parcel->item_type)
                                 <tr>
                                    <th style="width: 40%">Item Type </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ optional($parcel->item_type)->title}} </td>
                                </tr>
                                @endif
                                <tr>
                                    <th style="width: 40%">Product Value  </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->product_value }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Product details  </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $parcel->product_details }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Remark  </th>
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

                                    {{--@if($parcel->cod_charge != 0 && $parcel->total_collect_amount)--}}
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
                                    {{--@endif--}}

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
                @if(!empty($parcel->pickup_branch))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Pickup Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Pickup Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_branch_date)->format('d/m/Y') }} </td>
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

                @if(!empty($parcel->pickup_rider))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Pickup Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Pickup Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->pickup_rider_date)->format('d/m/Y') }} </td>
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

                @if(!empty($parcel->delivery_branch))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Delivery Branch Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_branch_date)->format('d/m/Y') }} </td>
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

                @if(!empty($parcel->delivery_rider))
                <div class="col-md-6">
                    <fieldset>
                        <legend>Delivery Rider Information</legend>
                        <table class="table table-style">
                            <tr>
                                <th style="width: 40%"> Delivery Date </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ \Carbon\Carbon::parse($parcel->delivery_rider_date)->format('d/m/Y') }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Name</th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->name ?? "" }} </td>
                            </tr>
                            <tr>
                                <th style="width: 40%"> Contact Number </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->contact_number ?? "" }} </td>
                            </tr>
<!--                            <tr>
                                <th style="width: 40%"> Address </th>
                                <td style="width: 10%"> : </td>
                                <td style="width: 50%"> {{ $parcel->delivery_rider->address ?? "" }} </td>
                            </tr>-->
                        </table>
                    </fieldset>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>
    .table-style td, .table-style th {
        padding: .1rem !important;
    }
</style>

<script>
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

<script>

</script>
