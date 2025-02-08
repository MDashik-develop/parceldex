@extends('layouts.admin_layout.admin_layout')

@section('content')
    <br>
    <div class="content">
        <div class="container-fluid">

            <form action="" method="get" class="mb-3">
                <div class="d-flex gap-5">
                    <input type="text" class="form-control mr-3" placeholder="Order ID" name="order_id"
                        value="{{ request()->order_id }}">
                    <button class="btn btn-success">Searrch</button>
                </div>
            </form>

            <form action="{{route('admin.parcel-reset.update')}}" method="post">
                @csrf
                @method('put')
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td>SL</td>
                            <td>Order Id</td>
                            <td>Curent Delivery Status</td>
                            <td>Amount To Be Collect</td>
                            <td>Collected Amount</td>
                            <td>Payment Status</td>
                            <td>Change Delivery Status</td>
                            <td>Amount To Be Collect</td>
                            <td>Collected Amount</td>
                            <td>Payment Status</td>
                            <td>Remove</td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($parcels ?? [] as $k => $p)
                            <tr id="parcel_row-{{ $p->parcel_invoice }}">
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $p->parcel_invoice }}</td>
                                <td>{{ returnParcelStatusForAdmin($p->status, $p->delivery_type, $p->payment_type, $p)['status_name'] }}
                                </td>
                                <td>{{ $p->total_collect_amount }}</td>
                                <td>{{ $p->cancel_amount_collection + $p->customer_collect_amount }}</td>
                                <td>

                                    @if ($p->payment_type == 1)
                                        <span class="badge badge-success">Delivery Branch Send Accounts Paid Request</span>
                                    @elseif ($p->payment_type == 2)
                                        <span class="badge badge-warning">Accounts Paid Request Accept</span>
                                    @elseif ($p->payment_type == 3)
                                        <span class="badge badge-danger">Accounts Paid Request Reject</span>
                                    @elseif ($p->payment_type == 4)
                                        <span class="badge badge-primary">Accounts Send Merchant Paid Request</span>
                                    @elseif ($p->payment_type == 5)
                                        <span class="badge badge-info">Merchant Paid Request Accept</span>
                                    @elseif ($p->payment_type == 6)
                                        <span class="badge badge-dark">Merchant Paid Request Reject</span>
                                    @else
                                        <span class="badge badge-secondary">Unknown</span>
                                    @endif

                                </td>
                                <td>
                                    <select name="parcels[{{ $p->parcel_invoice }}][status]" id=""
                                        class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="0">In Review API</option>
                                        <option value="1">Pick Requested Reschedule</option>
                                        <option value="2">Pick Request Hold</option>
                                        <option value="3">Deleted</option>
                                        <option value="4">Pick Request Reschedule</option>
                                        <option value="5">Pick Run Create</option>
                                        <option value="6">Pick Run Start</option>
                                        <option value="7">Pick Run Cancel</option>
                                        <option value="8">Pick Rider Accept</option>
                                        <option value="9">Pickup Declined</option>
                                        <option value="10">Picked Up by Rider</option>
                                        <option value="11">Picked Up</option>
                                        <option value="12">Transfer Run Create</option>
                                        <option value="13">Transfer Run Cancel</option>
                                        <option value="14">At Kitchen</option>
                                        <option value="15">Transfer Declined</option>
                                        <option value="16">Rider Assign Run Create</option>
                                        <option value="17">Rider Assign Run Start</option>
                                        <option value="18">Rider Assign Run Cancel</option>
                                        <option value="19">Rider Accept for Delivery</option>
                                        <option value="20">Rider Declined</option>
                                        <option value="21">Rider Requested for Delivery</option>
                                        <option value="22">Rider Requested for Partial Delivery</option>
                                        <option value="23">Rider Requested for Reschedule</option>
                                        <option value="24">Rider Requested for Cancel</option>

                                        <option value="25">Delivered</option>
                                        <option value="25">Partial Delivered</option>
                                        <option value="25">Recheduled</option>
                                        <option value="25">Cancelled</option>

                                        <option value="26">Return Transfer Run Create</option>
                                        <option value="26">Exchange Transfer Run Create</option>
                                        <option value="26">Partial & Exchange Transfer Run Create</option>
                                        <option value="26">Partial Return Transfer Run Create</option>

                                        <option value="27">Return Transfer Run Cancel</option>
                                        <option value="27">Exchange Transfer Run Cancel</option>
                                        <option value="27">Partial & Exchange Transfer Run Cancel</option>
                                        <option value="27">Partial Transfer Run Cancel</option>

                                        <option value="28">Return Transfer Received</option>
                                        <option value="28">Exchange Transfer Received</option>
                                        <option value="28">Partial & Exchange Transfer Received</option>
                                        <option value="28">Partial Return Transfer Received</option>

                                        <option value="29">Return Transfer Declined</option>
                                        <option value="29">Exchange Return Transfer Declined</option>
                                        <option value="29">Partial & Exchange Return Transfer Declined</option>
                                        <option value="29">Partial Return Transfer Declined</option>

                                        <option value="30">Return Assign Run Create</option>
                                        <option value="30">Exchange Assign Run Create</option>
                                        <option value="30">Partial & Exchange Assign Run Create</option>
                                        <option value="30">Partial Assign Run Create</option>

                                        <option value="31">Return Assign Run Start</option>
                                        <option value="31">Exchange Assign Run Start</option>
                                        <option value="31">Partial & Exchange Assign Run Start</option>
                                        <option value="31">Partial Assign Run Start</option>

                                        <option value="32">Return Assign Declined</option>
                                        <option value="32">Exchange Assign Declined</option>
                                        <option value="32">Partial & Exchange Assign Declined</option>
                                        <option value="32">Partial Assign Declined</option>

                                        <option value="33">Return Assign Accept</option>
                                        <option value="33">Exchange Assign Accept</option>
                                        <option value="33">Partial & Exchange Assign Accept</option>
                                        <option value="33">Partial Assign Accept</option>

                                        <option value="34">Return Assign Declined</option>
                                        <option value="34">Exchange Assign Declined</option>
                                        <option value="34">Partial & Exchange Assign Declined</option>
                                        <option value="34">Partial Assign Declined</option>

                                        <option value="35">Return Confirmed</option>
                                        <option value="35">Exchange Return Confirmed</option>
                                        <option value="35">Partial & Exchange Return Confirmed</option>
                                        <option value="35">Partial Return Confirmed</option>

                                        <option value="36">Returned</option>
                                        <option value="36">Exchange Returned</option>
                                        <option value="36">Partial Returned</option>
                                        <option value="36">Partial & Exchange Returned</option>

                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="parcels[{{ $p->parcel_invoice }}][amount_be_collect]"
                                        id="" class="form-control" placeholder="Amount To Be Collect">
                                </td>
                                <td>
                                    <input type="text" name="parcels[{{ $p->parcel_invoice }}][collected_amount]"
                                        id="" class="form-control" placeholder="Collected Amount">
                                </td>
                                <td>
                                    <select name="parcels[{{ $p->parcel_invoice }}][payment_type]" id=""
                                        class="form-control">
                                        <option value="1" @if ($p->payment_type == 1) selected @endif>Delivery
                                            Branch Send Accounts Paid Request</option>
                                        <option value="2" @if ($p->payment_type == 2) selected @endif>Accounts
                                            Paid Request Accept</option>
                                        <option value="3" @if ($p->payment_type == 3) selected @endif>Accounts
                                            Paid Request Reject</option>
                                        <option value="4" @if ($p->payment_type == 4) selected @endif>Accounts
                                            Send Merchant Paid Request</option>
                                        <option value="5" @if ($p->payment_type == 5) selected @endif>Merchant
                                            Paid Request Accept</option>
                                        <option value="6" @if ($p->payment_type == 6) selected @endif>Merchant
                                            Paid Request Reject</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" onclick="remove({{ $p->parcel_invoice }})">Remove</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>

                <button class="btn btn-success mt-3">Comfirm</button>
            </form>

        </div>
    </div>
@endsection

@push('script_js')
    <script>
        function remove(k) {
            document.getElementById('parcel_row-' + k).remove();
        }
    </script>
@endpush
