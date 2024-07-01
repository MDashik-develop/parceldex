<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\BookingParcel;
use App\Models\BookingParcelPayment;
use App\Models\Notice;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Merchant;
use App\Models\Branch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\BranchPasswordRestMail;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\BranchUser;
use App\Models\ServiceArea;
use App\Models\ParcelLog;

class HomeController extends Controller{

    public function home() {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $data                           = [];
        $data['main_menu']              = 'home';
        $data['child_menu']             = 'home';
        $data['page_title']             = 'Home';

        $data['news']   = Notice::whereRaw('type = 2 and publish_for IN (0,1)')->orderBy('id', 'DESC')->first();

        $counter_data   = parent::returnDashboardCounterForBranch($branch_id);
        $data['counter_data'] = $counter_data;

        return view('branch.home', $data);
    }

    public function orderTracking() {
        $data               = [];
        $data['main_menu']  = 'orderTracking';
        $data['child_menu'] = 'orderTracking';
        $data['page_title'] = 'Order Tracking';
        return view('branch.orderTracking', $data);
    }

    public function returnOrderTrackingResult(Request $request) {
        $parcel_invoice     = $request->input('parcel_invoice');
        $merchant_order_id  = $request->input('merchant_order_id');

        if((!is_null($parcel_invoice) && $parcel_invoice != '') || (!is_null($merchant_order_id) && $merchant_order_id != '')){
            $parcel = Parcel::with('district', 'upazila', 'area', 'merchant',
                    'weight_package', 'pickup_branch', 'pickup_rider',
                    'delivery_branch', 'delivery_rider')
                    ->where('pickup_branch_id', auth()->guard('branch')->user()->branch_id )
                    ->where(function($query) use ($parcel_invoice, $merchant_order_id){
                        if(!is_null($parcel_invoice)){
                            $query->where('parcel_invoice','like', "%$parcel_invoice");
                        }
                        elseif(!is_null($merchant_order_id)){
                            $query->where('merchant_order_id','like', "%$merchant_order_id");
                        }
                    })
                    ->first();
            if($parcel){
                $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch',
                    'delivery_rider', 'admin', 'merchant')
                    ->where('parcel_id', $parcel->id)->get();

                return view('branch.orderTrackingResult', compact('parcel', 'parcelLogs'));
            }
        //dd($parcel);
        }
        //dd($request->all());
    }



    public function coverageArea() {
        $data               = [];
        $data['main_menu']  = 'coverageArea';
        $data['child_menu'] = 'coverageArea';
        $data['page_title'] = 'Coverage Area';
        return view('branch.coverageArea', $data);
    }

    public function getCoverageAreas(Request $request) {
        $model = Area::with('upazila')->where('status', 1)->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->rawColumns([])
            ->make(true);
    }

    public function serviceCharge() {
        $data               = [];
        $data['main_menu']  = 'serviceCharge';
        $data['child_menu'] = 'serviceCharge';
        $data['page_title'] = 'Service Charge ';
        return view('branch.serviceCharge', $data);
    }

    public function getServiceCharges(Request $request) {
        $model = WeightPackage::where('status', 1)->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('rate', function ($data) {
                return number_format($data->rate, 2);
            })
            ->editColumn('weight_type', function ($data) {

                if ($data->weight_type == 1) {
                    $weight_type = "KG";
                } else {
                    $weight_type = "CFT";
                }

                return $weight_type;
            })
            ->rawColumns(['weight_type', 'rate'])
            ->make(true);
    }


    public function profile() {
        $data               = [];
        $data['main_menu']  = 'profile';
        $data['child_menu'] = 'profile';
        $data['page_title'] = 'Profile';
        $data['branchUser']     = BranchUser::with(['branch'])->where('id', auth()->guard('branch')->user()->id)->first();
        return view('branch.profile', $data);
    }

    public function merchantListByBranch() {
        $data               = [];
        $data['main_menu']  = 'merchantList';
        $data['child_menu'] = 'merchantList';
        $data['page_title'] = 'Merchant List';
        $data['branchUser']     = BranchUser::with(['branch'])->where('id', auth()->guard('branch')->user()->id)->first();
        $data['serviceAreas'] = ServiceArea::where(['status' => 1, 'weight_type' => 1])->get();

        return view('branch.merchantList', $data);
    }
    public function printMerchantListByBranch() {
        $data               = [];
        $data['main_menu']  = 'merchantList';
        $data['child_menu'] = 'merchantList';
        $data['page_title'] = 'Merchant List';
        $data['branchUser']     = BranchUser::with(['branch'])->where('id', auth()->guard('branch')->user()->id)->first();
        $data['serviceAreas'] = ServiceArea::where(['status' => 1, 'weight_type' => 1])->get();

        return view('branch.printMerchantList', $data);
    }

    public function riderListByBranch() {
        $data               = [];
        $data['main_menu']  = 'riderList';
        $data['child_menu'] = 'riderList';
        $data['page_title'] = 'Rider List';
        $data['branchUser']     = BranchUser::with(['branch'])->where('id', auth()->guard('branch')->user()->id)->first();
        return view('branch.riderList', $data);
    }

    public function printRiderListByBranch() {
        $data               = [];
        $data['main_menu']  = 'riderList';
        $data['child_menu'] = 'riderList';
        $data['page_title'] = 'Rider List';
        $data['branchUser']     = BranchUser::with(['branch'])->where('id', auth()->guard('branch')->user()->id)->first();
        return view('branch.printRiderList', $data);
    }

}
