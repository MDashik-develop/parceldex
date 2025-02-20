<div class="modal-header bg-default">
    <h4 class="modal-title">Received Return Branch Transfer Reject </h4>
    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form role="form" action="{{ route('branch.parcel.confirmReceivedReturnBranchTransferReject', $returnBranchTransfer->id) }}" id="confirmReceivedReturnBranchTransferReject" method="POST" enctype="multipart/form-data" onsubmit="return createForm(this)">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset>
                        <legend>Return Transfer Information</legend>
                        <div class="row">
                            <div class="col-md-6">
                            <table class="table table-style">
                                <tr>
                                    <th style="width: 40%"> Consignment </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $returnBranchTransfer->run_invoice }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Create Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($returnBranchTransfer->create_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>

                                @if($returnBranchTransfer->cancel_date_time)
                                <tr>
                                    <th style="width: 40%">Cancel Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($returnBranchTransfer->cancel_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>
                                @endif

                                @if($returnBranchTransfer->reject_date_time)
                                <tr>
                                    <th style="width: 40%">Complete Date </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ \Carbon\Carbon::parse($returnBranchTransfer->reject_date_time)->format('d/m/Y H:i:s') }} </td>
                                </tr>
                                @endif

                                <tr>
                                    <th style="width: 40%">Total Transfer </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%"> {{ $returnBranchTransfer->total_transfer_parcel }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Total Transfer Received </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%" id="view_total_transfer_received_parcel"> {{ $returnBranchTransfer->total_transfer_received_parcel }} </td>
                                </tr>
                                <tr>
                                    <th style="width: 40%">Status </th>
                                    <td style="width: 10%"> : </td>
                                    <td style="width: 50%">
                                    @switch($returnBranchTransfer->status)
                                        @case(1) <div class="badge badge-success">Transfer Create </div> @break
                                        @case(2) <div class="badge badge-success">Transfer Cancel </div> @break
                                        @case(3) <div class="badge badge-danger " >Transfer Received </div>  @break
                                        @case(4)<div class="badge badge-success">Transfer Reject </div> @break
                                        @default
                                    @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th >Reject Date</th>
                                    <th colspan="2">
                                        <input type="date" name="reject_date" id="reject_date" class="form-control" value="{{ \Carbon\Carbon::parse()->now()->format('Y-m-d') }}" required>
                                    </th>
                                </tr>
                                <tr>
                                    <th >Note </th>
                                    <th colspan="2">
                                        <textarea name="transfer_note" class="form-control" placeholder="Transfer Transfer Note">{{ $returnBranchTransfer->note }}</textarea>
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
                                            <td style="width: 50%"> {{ $returnBranchTransfer->from_branch->name }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Contact Number </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $returnBranchTransfer->from_branch->contact_number }} </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 40%"> Address </th>
                                            <td style="width: 10%"> : </td>
                                            <td style="width: 50%"> {{ $returnBranchTransfer->from_branch->address }} </td>
                                        </tr>

                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        @if($returnBranchTransfer->return_branch_transfer_details->count() > 0)
                        <fieldset>
                            <legend>Transfer  Parcel</legend>
                            <table class="table table-style table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center"> SL </th>
                                        <th width="15%" class="text-center">Order ID </th>
                                        <th width="15%" class="text-center">Merchant Name</th>
                                        <th width="20%" class="text-center">Merchant Contact Number</th>
                                        <th width="15%" class="text-center">Customer Name</th>
                                        <th width="20%" class="text-center">Customer Contact Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returnBranchTransfer->return_branch_transfer_details as $return_branch_transfer_detail)
                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration }} </td>
                                        <td class="text-center"> {{ $return_branch_transfer_detail->parcel->parcel_invoice }} </td>
                                        <td class="text-center"> {{ $return_branch_transfer_detail->parcel->merchant->name }} </td>
                                        <td class="text-center"> {{ $return_branch_transfer_detail->parcel->merchant->contact_number }} </td>
                                        <td class="text-center"> {{ $return_branch_transfer_detail->parcel->customer_name }} </td>
                                        <td class="text-center"> {{ $return_branch_transfer_detail->parcel->customer_contact_number }} </td>
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
    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
</div>

<style>

</style>

<script>
    function createForm(object){
        event.preventDefault();

        let reject_date      = $('#reject_date').val();
        let transfer_note    = $('#transfer_note').val();

        $.ajax({
            cache     : false,
            type      : "PATCH",
            dataType  : "JSON",
            data      : {
                reject_date    : reject_date,
                transfer_note  : transfer_note,
                _token         : "{{ csrf_token() }}"
            },
            error     : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url       : object.action,
            success   : function(response){
                if(response.success){
                    toastr.success(response.success);

                    $('#yajraDatatable').DataTable().ajax.reload();
                    setTimeout(function(){$('#viewModal').modal('hide')},1000);
                }
                else{
                    var getError = response.error;
                    var message = "";
                    if(getError.reject_date){
                        message += getError.reject_date[0];
                    }
                    if(getError.transfer_note){
                        message += getError.transfer_note[0];
                    }
                    message += getError;
                    toastr.error(message);
                }
            }
        })
    }
</script>
