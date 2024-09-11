<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\Upazila;
use App\Models\WeightPackage;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;

class ParcelFilterController extends Controller
{


    public function filterParcelList($type)
    {


        $current_date       = date("Y-m-d");
        //        $current_date       = "2021-07-25";
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Parcel Filter List';
        $data['collapse']   = 'sidebar-collapse';
        $merchant_id = auth()->guard('merchant')->user()->id;
        if ($type == 'total_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->with('district', 'area')->get();
        } elseif ($type == 'delivered_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('delivery_type = ? and status in (?)', [1, 25])
                ->whereNull('suborder')
                ->with('district', 'area')->get();
        } elseif ($type == 'p_delivered_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('delivery_type = ? and status in (?,?)', [2, 22, 25])
                ->whereNull('suborder')
                ->with('district', 'area')->get();
        } elseif ($type == 'cancelled_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('status >= ? and delivery_type in (?)', [25, 4])
                ->with('district', 'area')->get();
        } elseif ($type == 'return_process') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereIn('status', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35])
                ->where('delivery_type', 4)
                ->with('district', 'area')->get();
        } elseif ($type == 'pending_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereNull('suborder')
                ->where(function ($query) {
                    $query->whereIn('status', [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]);
                    //$query->whereBetween('status', [10, 24]);
                    //->orWhere('delivery_type', 3);
                })
                ->orWhere(function ($query) {
                    $query->where('status', 25)->where('delivery_type', 3);
                })
                ->with('district', 'area')->get();
        } elseif ($type == 'pickup_pending') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereIn('status', [1, 2, 4])
                ->whereNull('suborder')

                // ->whereRaw('status = ?',  [1])
                ->with('district', 'area')->get();
        } elseif ($type == 'today_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('pickup_branch_date = ? ', [date("Y-m-d")])
                ->with('district', 'area')->get();
        } elseif ($type == 'today_total_delivery_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
                ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
                ->with('district', 'area')->get();
        } elseif ($type == 'today_total_partial_delivery_parcel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('status >= ? and delivery_type in (?)', [25, 2])
                ->whereRaw('parcel_date = ? ', [date("Y-m-d")])
                ->with('district', 'area')->get();
        } elseif ($type == 'today_delivery_cancel') {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->whereRaw('status >= ? and delivery_type in (?)', [25, 4])
                ->whereRaw('delivery_branch_date = ? ', [date("Y-m-d")])
                ->with('district', 'area')->get();
        } else {
            $data['parcels'] = Parcel::where('merchant_id', $merchant_id)
                ->with('district', 'area')->get();
        }


        $data['type']    = $type;

        return view('merchant.parcel.parcelFilterList', $data);
    }
}
