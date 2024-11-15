<div class="modal-header bg-default">
    <h4 class="modal-title">Received Branch Transfer Received </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@php
    $branch_id = auth()->guard('branch')->user()->branch->id . '-' . auth()->guard('branch')->user()->branch->id;
    \Cart::session($branch_id)->clear();
@endphp
<div class="modal-body">
    <form role="form"
        action="{{ route('branch.parcel.confirmReceivedBranchTransferReceived', $deliveryBranchTransfer->id) }}"
        id="confirmAssignDeliveryBranch" method="POST" enctype="multipart/form-data"
        onsubmit="return createForm(this)">
        <input type="hidden" name="total_transfer_received_parcel" id="total_transfer_received_parcel"
            value="{{ $deliveryBranchTransfer->total_transfer_parcel }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Transfer Information</legend>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-style">
                                    <tr>
                                        <th style="width: 40%"> Consignment </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->run_invoice }} </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Create Date </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            {{ \Carbon\Carbon::parse($deliveryBranchTransfer->create_date_time)->format('d/m/Y H:i:s') }}
                                        </td>
                                    </tr>

                                    @if ($deliveryBranchTransfer->cancel_date_time)
                                        <tr>
                                            <th style="width: 40%">Cancel Date </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%">
                                                {{ \Carbon\Carbon::parse($deliveryBranchTransfer->cancel_date_time)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($deliveryBranchTransfer->reject_date_time)
                                        <tr>
                                            <th style="width: 40%">Complete Date </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%">
                                                {{ \Carbon\Carbon::parse($deliveryBranchTransfer->reject_date_time)->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th style="width: 40%">Total Transfer </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%"> {{ $deliveryBranchTransfer->total_transfer_parcel }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Transfer Received </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%" id="view_total_transfer_received_parcel">
                                            0 </td>

                                        {{-- <td style="width: 50%" id="view_total_transfer_received_parcel">
                                                {{ $deliveryBranchTransfer->total_transfer_parcel }} </td> --}}
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Total Transfer Reject </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%" id="reject_total_transfer_received_parcel">
                                            0 </td>

                                        {{-- <td style="width: 50%" id="view_total_transfer_received_parcel">
                                                {{ $deliveryBranchTransfer->total_transfer_parcel }} </td> --}}
                                    </tr>
                                    <tr>
                                        <th style="width: 40%">Status </th>
                                        <td style="width: 10%"> : </td>
                                        <td style="width: 50%">
                                            @switch($deliveryBranchTransfer->status)
                                                @case(1)
                                                    <div class="badge badge-success">Transfer Create </div>
                                                @break

                                                @case(2)
                                                    <div class="badge badge-success">Transfer Cancel </div>
                                                @break

                                                @case(3)
                                                    <div class="badge badge-danger ">Transfer Received </div>
                                                @break

                                                @case(4)
                                                    <div class="badge badge-success">Transfer Reject </div>
                                                @break

                                                @default
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Received Date</th>
                                        <th colspan="2">
                                            <input type="date" name="received_date" id="received_date"
                                                class="form-control"
                                                value="{{ \Carbon\Carbon::parse()->now()->format('Y-m-d') }}" required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Note </th>
                                        <th colspan="2">
                                            <textarea name="transfer_note" id="transfer_note" class="form-control" placeholder="Transfer Transfer Note">{{ $deliveryBranchTransfer->note }}</textarea>
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <fieldset>
                                    <legend>From Branch Information </legend>
                                    <table class="table table-style">
                                        <tr>
                                            <th style="width: 40%"> Name </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $deliveryBranchTransfer->from_branch->name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Contact Number </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%">
                                                {{ $deliveryBranchTransfer->from_branch->contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $deliveryBranchTransfer->from_branch->address }}
                                            </td>
                                        </tr>

                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        @if ($deliveryBranchTransfer->delivery_branch_transfer_details->count() > 0)
                            <div class="col-md-12 row mb-3" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <fieldset id="div_delivery_branch_transfer_parcel" style="display: none">
                                        <legend>Delivery Branch Transfer Parcel </legend>
                                        <div class="row">
                                            <div class="col-sm-12" id="show_delivery_branch_transfer_parcel">

                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="col-md-12 row mb-3" style="margin-top: 20px;">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input type="text" name="parcel_invoice" id="parcel_invoice"
                                            class="form-control" placeholder="Enter Parcel Invoice Barcode"
                                            style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                    padding: 3px 0px 3px 3px;
                                    margin: 5px 1px 3px 0px;
                                    border: 1px solid rgb(62, 196, 118);">
                                    </div>
                                </div>
                            </div>
                            <fieldset>
                                <legend>Transfer Parcel</legend>
                                <table class="table table-style table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center"> SL </th>
                                            <th width="10%" class="text-center">Order ID </th>
                                            <th width="10%" class="text-center">Merchant Order</th>
                                            <th width="10%" class="text-center">Merchant Name</th>
                                            <th width="15%" class="text-center">Customer Name</th>
                                            <th width="15%" class="text-center">ChangeStatus</th>
                                            <th width="15%" class="text-center">Note </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deliveryBranchTransfer->delivery_branch_transfer_details as $delivery_branch_transfer_detail)
                                            <tr class="parclTR"
                                                data-parcel_id="{{ $delivery_branch_transfer_detail->parcel->id }}"
                                                data-parcel_invoice="{{ $delivery_branch_transfer_detail->parcel->parcel_invoice }}">
                                                <td class="text-center"> {{ $loop->iteration }} </td>
                                                <td class="text-center">
                                                    {{ $delivery_branch_transfer_detail->parcel->parcel_invoice }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $delivery_branch_transfer_detail->parcel->merchant_order_id }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $delivery_branch_transfer_detail->parcel->merchant->company_name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $delivery_branch_transfer_detail->parcel->customer_name }} <br>
                                                    {{ $delivery_branch_transfer_detail->parcel->customer_contact_number }}
                                                </td>
                                                <td class="text-center">
                                                    <select name="delivery_branch_transfer_status[]"
                                                        class="form-control select2 delivery_branch_transfer_status delivery_branch_transfer_status"
                                                        style="width: 100%"
                                                        onchange="return delivery_branch_transfer_status()">
                                                        <option value="3">Transfer Received</option>
                                                        <option value="4" selected>Transfer Reject</option>
                                                    </select>
                                                    <input type="hidden" name="delivery_branch_transfer_details_id[]"
                                                        class="delivery_branch_transfer_details_id"
                                                        value="{{ $delivery_branch_transfer_detail->id }}">
                                                    <input type="hidden" name="parcel_id[]" class="parcel_id"
                                                        value="{{ $delivery_branch_transfer_detail->parcel_id }}">
                                                </td>
                                                <td class="text-center">
                                                    <textarea name="received_note[]" required class="form-control received_note" placeholder="Received Note"> {{ $delivery_branch_transfer_detail->note }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </fieldset>
                        @endif
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">Confirm </button>
            <button type="reset" class="btn btn-primary">Reset</button>
        </div>
    </form>

</div>
<div class="modal-footer">
    <button type="button" id="viewModalCloseBtn" class="btn btn-danger float-right"
        data-dismiss="modal">Close</button>
</div>

<style>

</style>

<script>
    function delivery_branch_transfer_status() {
        var complete = 0;
        var reject = 0;
        var s = $('.delivery_branch_transfer_status option:selected').map(function() {
            if (this.value == 3) {
                complete++;
            } else {
                reject++;
            }
        }).get();
        $("#view_total_transfer_received_parcel").html(complete);
        $("#reject_total_transfer_received_parcel").html(reject);
        $("#total_transfer_received_parcel").val(complete);
    }

    $("#confirmAssignDeliveryBranch").on("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault(); // Prevent form submission on Enter
            console.log("Enter key prevented for form submission.");
        }
    });

    $("#parcel_invoice").on("trigger change", function(e) {
        var invoice_id = $(this).val();
        var parcel_invoices = [invoice_id];

        if (invoice_id != "") {

            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    parcel_invoices: parcel_invoices,
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ route('branch.parcel.deliveryBranchTransferParcelAddCart2') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $("#show_delivery_branch_transfer_parcel").html(response);
                    $("#div_delivery_branch_transfer_parcel").show();
                    $("#parcel_invoice").val("");

                    $('.parclTR').each(function() {
                        if ($(this).data('parcel_invoice') == invoice_id) {
                            $(this).hide(); // Hide the row if the data attribute matches

                            // Find the select element in the current row
                            let select = $(this).find(
                                'select.delivery_branch_transfer_status');

                            // Set option with value="3" as selected
                            select.val("3"); // Update the value of the <select>

                            // If using select2 or similar, refresh the dropdown UI
                            if (select.hasClass('select2')) {
                                select.trigger(
                                    'change'
                                ); // Trigger change event for the select2 plugin
                            }

                            delivery_branch_transfer_status();
                        }
                    });

                    // $('.parclTR').each(function() {
                    //     if ($(this).data('parcel_invoice') == invoice_id) {
                    //         $(this).hide(); // Hide the row if the data attribute matches

                    //          // Find the select element in the current row
                    //         let select = $(this).find('select.delivery_branch_transfer_status');

                    //         // Set option with value="3" as selected
                    //         select.find('option[value="3"]').prop('selected', true);

                    //         // Remove selected attribute from option with value="4"
                    //         select.find('option[value="4"]').prop('selected', false);

                    //         console.log("Selection updated for invoice: " + invoice_id);
                    //     }
                    // });

                    return false;
                }
            });
        }


    });

    function delete_parcel(itemId) {
        $.ajax({
            cache: false,
            type: 'POST',
            data: {
                itemId: itemId,
                _token: "{{ csrf_token() }}",
            },
            url: "{{ route('branch.parcel.deliveryBranchTransferParcelDeleteCart2') }}",
            error: function(xhr) {
                alert("An error occurred: " + xhr.status + " " + xhr.statusText);
            },
            success: function(response) {
                $('#show_delivery_branch_transfer_parcel').html(response);
                $('.parclTR').each(function() {
                    if ($(this).data('parcel_id') == itemId) {
                        $(this).show(); // Hide the row if the data attribute matches
                    }
                });
            }
        });
    }

    function createForm(object) {
        event.preventDefault();

        let received_date = $('#received_date').val();
        let transfer_note = $('#transfer_note').val();
        var delivery_branch_transfer_id = $("#delivery_branch_transfer_id").val();
        var total_transfer_received_parcel = $("#total_transfer_received_parcel").val();

        var delivery_branch_transfer_status = $('.delivery_branch_transfer_status  option:selected').map(function() {
            return this.value;
        }).get();

        var received_note = $('.received_note').map(function() {
            return this.value;
        }).get();

        let hasError = false;
        delivery_branch_transfer_status.forEach((element, index) => {
            if (element == 4 && received_note[index].trim() == '') {
                hasError = true;
                toastr.error('Note is required for transfer reject status');
            }
        });

        if (hasError) {
            return;
        }

        var delivery_branch_transfer_details_id = $('.delivery_branch_transfer_details_id').map(function() {
            return this.value;
        }).get();

        var parcel_id = $('.parcel_id').map(function() {
            return this.value;
        }).get();

        $.ajax({
            cache: false,
            type: "PATCH",
            dataType: "JSON",
            data: {
                received_date: received_date,
                transfer_note: transfer_note,
                delivery_branch_transfer_id: delivery_branch_transfer_id,
                total_transfer_received_parcel: total_transfer_received_parcel,
                delivery_branch_transfer_status: delivery_branch_transfer_status,
                delivery_branch_transfer_details_id: delivery_branch_transfer_details_id,
                received_note: received_note,
                parcel_id: parcel_id,
                _token: "{{ csrf_token() }}"
            },
            error: function(xhr) {
                alert("An error occurred: " + xhr.status + " " + xhr.statusText);
            },
            url: object.action,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();

                    setTimeout(function() {
                        $('#viewModal').modal('hide');
                        $('#viewModalCloseBtn').click();
                    }, 1000);
                } else {
                    var getError = response.error;
                    var message = "";
                    if (getError.received_date) {
                        message += getError.received_date[0];
                    }
                    if (getError.transfer_note) {
                        message += getError.transfer_note[0];
                    }

                    if (getError.total_transfer_received_parcel) {
                        message += getError.total_transfer_received_parcel[0];
                    }
                    if (getError.delivery_branch_transfer_details_id) {
                        message += getError.delivery_branch_transfer_details_id[0];
                    }
                    if (getError.delivery_branch_transfer_status) {
                        message += getError.delivery_branch_transfer_status[0];
                    }
                    if (getError.received_note) {
                        message += getError.received_note[0];
                    }
                    message += getError;

                    toastr.error(message);
                }
            }
        })

    }
</script>
