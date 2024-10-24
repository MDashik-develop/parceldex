<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Parcel;
use App\Models\District;
use App\Models\Merchant;
use App\Models\ParcelLog;
use Illuminate\Http\Request;
use App\Models\WeightPackage;
use App\Exports\BranchParcelExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ParcelDeliveryPayment;
use App\Exports\BranchDeliveryPayment;
use App\Models\MerchantServiceAreaCharge;
use Illuminate\Support\Facades\Validator;
use App\Exports\BranchDeliveryPaymentList;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\MerchantServiceAreaCodCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Notifications\MerchantParcelNotification;

class BranchDeliveryPaymentController extends Controller
{

    /** Branch Delivery Payment List */
    public function branchDeliveryPaymentList()
    {
        $data               = [];
        $data['main_menu']  = 'branch-payment';
        $data['child_menu'] = 'branchDeliveryPaymentList';
        $data['page_title'] = 'Branch Delivery Payment List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.deliveryPayment.branchDeliveryPaymentList', $data);
    }

    /** Branch Delivery Payment List */
    public function pushRequest(Request $request)
    {
        $parcels = Parcel::with('merchant')->where('is_push', 1)->where('status', 0)
            ->where(function ($query) use ($request) {
                $merchant_id = $request->input('merchant_id');
                $branch_id = $request->input('branch_id');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                    $query->where('merchant_id', $request->input('merchant_id'));
                }

                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->whereHas('merchant', function ($query) use ($branch_id) {
                        $query->where('branch_id', $branch_id);
                    });
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date !== '') {
                    $fromDateTime = Carbon::parse($request->input('from_date'));
                    $query->where('created_at', '>=', $fromDateTime);
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date !== '') {
                    $toDateTime = Carbon::parse($request->input('to_date'));
                    $query->where('created_at', '<=', $toDateTime);
                }
            })
            ->orderBy('id', 'desc')
            ->select()
            ->get();

        $data               = [];
        $data['main_menu']  = 'push-request';
        $data['child_menu'] = 'push-request';
        $data['page_title'] = 'Push Request';
        $data['collapse']   = 'sidebar-collapse';
        $data['merchants']   = Merchant::whereIn('id', $parcels->pluck('merchant_id')->toArray())->get();
        $data['branches']   = Branch::where('status', 1)->get();
        $data['parcels']   = $parcels;
        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        return view('admin.account.deliveryPayment.pushRequest', $data);
    }

    public function savePushRequest(Request $request)
    {
        try {
            foreach ($request->parcel_id as $key => $value) {
                $parcel = Parcel::find($value);

                if (isset($request->delete[$key]) && !empty($request->delete[$key])) {
                    $parcel->delete();
                    continue;
                }

                if (
                    isset($request->district_id[$key]) && !empty($request->district_id[$key]) &&
                    isset($request->area_id[$key]) && !empty($request->area_id[$key]) &&
                    isset($request->weight_package_id[$key]) && !empty($request->weight_package_id[$key])
                ) {

                    //Set District, Upazila, Area ID and Merchant Service Area Charge
                    $merchant_service_area_charge = 0;
                    $delivery_charge = 0;
                    $merchant_service_area_return_charge = 0;
                    $weight_package_charge = 0;
                    $cod_charge = 0;
                    $service_area_id = 0;
                    $cod_percent = 0;
                    $merchant_cod_percent = $parcel->merchant?->cod_charge ?? 0;
                    $district_id = $request->district_id[$key];
                    $weight_id = $request->weight_package_id[$key];
                    $collection_amount = $parcel->total_collect_amount;

                    $district = District::with('service_area:id,cod_charge,default_charge')->where('id', $district_id)->first();

                    if ($district) {

                        $service_area_id = $district->service_area_id;
                        //Service Area Default Charges
                        $delivery_charge = $district->service_area ? $district->service_area->default_charge : 0;


                        // Check Merchant COD Percent
                        if ($district->service_area->cod_charge != 0) {
                            $cod_percent = ($merchant_cod_percent != 0) ? $merchant_cod_percent : $district->service_area->cod_charge;
                        }

                        $code_charge_percent = $district->service_area->cod_charge;
                        if ($code_charge_percent != 0) {
                            $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                                'service_area_id' => $district->service_area_id,
                                'merchant_id'     => $parcel->merchant?->id,
                            ])->first();

                            if ($merchantServiceAreaCodCharge) {
                                $cod_percent = $merchantServiceAreaCodCharge->cod_charge;
                            }
                        }

                        $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                            'service_area_id' => $service_area_id,
                            'merchant_id' => $parcel->merchant?->id,
                        ])->first();

                        $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                            'service_area_id' => $service_area_id,
                            'merchant_id' => $parcel->merchant?->id,
                        ])->first();


                        if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                            $merchant_service_area_charge = $merchantServiceAreaCharge->charge;
                        }


                        //Set Default Return Charge 1/2 of Delivery Charge
                        $merchant_service_area_return_charge = $merchant_service_area_charge / 2;
                        if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                            //Set Return Charge Merchant Wise
                            $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                        }
                    }


                    // Weight Package Charge
                    if ($weight_id) {
                        $weightPackage = WeightPackage::with([
                            'service_area' => function ($query) use ($service_area_id) {
                                $query->where('service_area_id', '=', $service_area_id);
                            },
                        ])
                            ->where(['id' => $weight_id])
                            ->first();

                        $weight_package_charge = $weightPackage->rate;
                        if (!empty($weightPackage->service_area)) {
                            $weight_package_charge = $weightPackage->service_area->rate;
                        }
                    }

                    if (empty($weightPackage) || is_null($weight_id)) {
                        $weightPackage = WeightPackage::with([
                            'service_area' => function ($query) use ($service_area_id) {
                                $query->where('service_area_id', '=', $service_area_id);
                            },
                        ])
                            ->where(['id' => $weight_id])
                            ->first();

                        $weight_package_charge = $weightPackage->rate;
                        if (!empty($weightPackage->service_area)) {
                            $weight_package_charge = $weightPackage->service_area->rate;
                        }
                        $weight_id = $weightPackage->id;
                    }

                    /**
                     * Set Parcel Delivery Charge
                     * If Merchant service area is not 0 then check District Area default Delivery charge
                     */
                    $delivery_charge = $merchant_service_area_charge != 0 ? $merchant_service_area_charge : $delivery_charge;


                    $collection_amount = $collection_amount ?? 0;
                    if ($collection_amount != 0 && $cod_percent != 0) {
                        $cod_charge = ($collection_amount / 100) * $cod_percent;
                    }

                    $item_type_charge = $request->input('item_type_charge') ?? 0;
                    $service_type_charge = $request->input('service_type_charge') ?? 0;
                    $delivery_charge =  $delivery_charge + $item_type_charge + $service_type_charge;
                    $total_charge = $delivery_charge + $cod_charge + $weight_package_charge;

                    $data = [
                        'district_id' => $district_id,
                        'area_id' => $request->area_id[$key],
                        'weight_package_id' => $request->weight_package_id[$key],
                        'delivery_charge' => $delivery_charge,
                        'weight_package_charge' => $weight_package_charge,
                        'merchant_service_area_charge' => $merchant_service_area_charge,
                        'merchant_service_area_return_charge' => $merchant_service_area_return_charge,
                        'cod_percent' => $cod_percent,
                        'cod_charge' => $cod_charge,
                        'total_charge' => $total_charge,
                        'status' => 1,
                    ];

                    $parcel->update($data);

                    if ($parcel) {
                        $data = [
                            'parcel_id' => $parcel->id,
                            'merchant_id' => $parcel->merchant->id,
                            'pickup_branch_id' => $parcel->merchant->branch_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 1,
                        ];

                        ParcelLog::create($data);
                    }
                } else {
                    continue;
                }
            }

            return back()->with('success', 'Parcel Update Successfully');
        } catch (\Exception $exception) {
            return back()->with('error', 'Parcel Update Failed');
        }
    }

    public function getBranchDeliveryPaymentList(Request $request)
    {

        $model = ParcelDeliveryPayment::with([
            'branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
        ])
            ->where(function ($query) use ($request) {
                $branch_id = $request->input('branch_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->where('branch_id', $request->input('branch_id'));
                }

                if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
                    $query->where('status', $request->input('status'));
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('date_time', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('date_time', '<=', $request->input('to_date'));
                }
            })
            ->orderBy('id', 'desc')
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date_time', function ($data) {
                return date('d-m-Y', strtotime($data->date_time));
            })
            ->editColumn('total_payment_amount', function ($data) {
                return number_format($data->total_payment_amount, 2);
            })
            ->editColumn('total_payment_received_amount', function ($data) {
                return number_format($data->total_payment_received_amount, 2);
            })

            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1:
                        $status_name  = "Payment Request";
                        $class  = "success";
                        break;
                    case 2:
                        $status_name  = "Payment Accept";
                        $class  = "success";
                        break;
                    case 3:
                        $status_name  = "Payment Reject";
                        $class  = "danger";
                        break;
                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="View Branch Delivery Payment">
                <i class="fa fa-eye"></i></button>';
                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" parcel_delivery_payment_id="' . $data->id . '" title="Print Delivery Payment" >
                <i class="fa fa-print"></i> </button>';
                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success accept-branch-delivery-payment btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Accept Branch Delivery Payment">
                    <i class="fa fa-check"></i> </button>';
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm reject-branch-delivery-payment" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Reject Branch Delivery Payment">
                            <i class="far fa-window-close"></i> </button>';
                }

                $button .= '&nbsp; <a class="btn btn-info btn-sm" href=" ' . route('admin.account.excelBranchDeliveryPayment', $data->id) . '" target="_blank">
                <i class="fas fa-file-excel"></i></a>';

                return $button;
            })
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time'])
            ->make(true);
    }

    /** Branch Delivery Payment Receive List */
    public function branchDeliveryReceivePaymentList()
    {
        $data               = [];
        $data['main_menu']  = 'branch-payment';
        $data['child_menu'] = 'receivePaymentList';
        $data['page_title'] = 'Branch Delivery Receive Payment List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.deliveryPayment.receivePaymentList', $data);
    }

    public function getBranchDeliveryReceivePaymentList(Request $request)
    {

        $model = ParcelDeliveryPayment::with([
            'branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
        ])
            ->where(function ($query) use ($request) {
                $branch_id = $request->input('branch_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                $query->where('status', 2);

                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->where('branch_id', $request->input('branch_id'));
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('date_time', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('date_time', '<=', $request->input('to_date'));
                }
            })
            ->orderBy('id', 'desc')
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date_time', function ($data) {
                return date('d-m-Y', strtotime($data->date_time));
            })
            ->editColumn('total_payment_amount', function ($data) {
                return number_format($data->total_payment_amount, 2);
            })
            ->editColumn('total_payment_received_amount', function ($data) {
                return number_format($data->total_payment_received_amount, 2);
            })

            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1:
                        $status_name  = "Payment Request";
                        $class  = "success";
                        break;
                    case 2:
                        $status_name  = "Payment Accept";
                        $class  = "success";
                        break;
                    case 3:
                        $status_name  = "Payment Reject";
                        $class  = "danger";
                        break;
                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="View Branch Delivery Payment">
                <i class="fa fa-eye"></i></button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success accept-branch-delivery-payment btn-sm" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Accept Branch Delivery Payment">
                    <i class="fa fa-check"></i> </button>';
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm reject-branch-delivery-payment" data-toggle="modal" data-target="#viewModal" parcel_delivery_payment_id="' . $data->id . '" title="Reject Branch Delivery Payment">
                            <i class="far fa-window-close"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'total_payment_amount', 'total_payment_received_amount', 'date_time'])
            ->make(true);
    }


    public function viewBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.viewBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }

    public function excelBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');

        $fileName = 'branch_delivery_payment_list_' . now() . '.xlsx';

        return Excel::download(new BranchDeliveryPayment($parcelDeliveryPayment), $fileName);
    }

    public function printBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.printBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }

    public function acceptBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.acceptBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }

    public function confirmAcceptBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'total_payment_received_parcel' => 'required',
                'total_payment_received_amount' => 'required',
                'note'                          => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = ParcelDeliveryPayment::where([
                        'id' => $parcelDeliveryPayment->id,

                    ])
                        ->update([
                            'action_date_time'              => date('Y-m-d H:i:s'),
                            'total_payment_received_parcel' => $request->total_payment_received_parcel,
                            'total_payment_received_amount' => $request->total_payment_received_amount,
                            'note'                          => $request->note,
                            'status'                        => 2,
                            'admin_id'                      => $admin_id,
                        ]);

                    if ($check) {
                        $parcel_delivery_payment_detail_status = $request->parcel_delivery_payment_detail_status;
                        $parcel_delivery_payment_detail_id     = $request->parcel_delivery_payment_detail_id;
                        $parcel_id                             = $request->parcel_id;
                        $amount                                = $request->amount;
                        $detail_note                           = $request->detail_note;

                        $count = count($parcel_delivery_payment_detail_id);

                        for ($i = 0; $i < $count; $i++) {
                            ParcelDeliveryPaymentDetail::where('id', $parcel_delivery_payment_detail_id[$i])->update([
                                'note'              => $detail_note[$i],
                                'date_time'         => date('Y-m-d H:i:s'),
                                'admin_id'          => $admin_id,
                                'status'            => $parcel_delivery_payment_detail_status[$i],
                            ]);

                            Parcel::where('id', $parcel_id[$i])->update([
                                'payment_type'             => $parcel_delivery_payment_detail_status[$i],
                            ]);

                            // $parcel = Parcel::with('merchant')->where('id', $parcel_id[$i])->first();
                            // $message = "Dear ".$parcel->merchant->name.", Flier Express just delivered/partial delivered/Returned your product Reff-". $parcel->parcel_invoice." .  Please Collect the amount from accounts.";
                            // $this->send_sms($parcel->customer_contact_number, $message);


                            $parcel     = Parcel::with('merchant')->where('id', $parcel_id[$i])->first();

                            $delivery_type = "";
                            if ($parcel->delivery_type == 1 || $parcel->delivery_type == 2) {
                                $delivery_type = "Delivered";
                            }
                            if ($parcel->delivery_type == 4) {
                                $delivery_type = "Canceled";
                            }

                            //                            $message    = "Dear ".$parcel->merchant->name.", ";
                            //                            $message    .= "For  Parcel ID No ".$parcel->parcel_invoice."  is successfully ".$delivery_type.".";
                            //                            $message    .= "Please rate your experience with us in our google play store app link.";
                            //                            $this->send_sms($parcel->merchant->contact_number, $message);


                            $merchant_user = Merchant::find($parcel->merchant_id);
                            //$merchant_user->notify(new MerchantParcelNotification($parcel));

                            //$this->merchantDashboardCounterEvent($parcel->merchant_id);

                            //$this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();
                        //$this->adminDashboardCounterEvent();

                        $response = ['success' => 'Accept Delivery Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
                }
            }
        }
        return response()->json($response);
    }


    public function rejectBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $parcelDeliveryPayment->load('branch', 'branch_user');
        $parcelDeliveryPaymentDetails = ParcelDeliveryPaymentDetail::with('parcel')->where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->get();
        return view('admin.account.deliveryPayment.rejectBranchDeliveryPayment', compact('parcelDeliveryPayment', 'parcelDeliveryPaymentDetails'));
    }

    public function confirmRejectBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note'                          => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = ParcelDeliveryPayment::where([
                        'id' => $parcelDeliveryPayment->id,
                    ])
                        ->update([
                            'action_date_time'              => date('Y-m-d H:i:s'),
                            'note'                          => $request->note,
                            'total_payment_received_parcel' => 0,
                            'total_payment_received_amount' => 0,
                            'status'                        => 3,
                            'admin_id'                      => $admin_id,
                        ]);

                    if ($check) {

                        ParcelDeliveryPaymentDetail::where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->update([
                            'date_time'         => date('Y-m-d H:i:s'),
                            'status'            => 3,
                        ]);

                        $parcel_id                             = $request->parcel_id;

                        $count = count($parcel_id);

                        for ($i = 0; $i < $count; $i++) {
                            Parcel::where('id', $parcel_id[$i])->update([
                                'payment_type'             => 3,
                                'updated_admin_id'         => $admin_id,
                            ]);

                            $parcel = Parcel::where('id', $parcel_id[$i])->first();
                            $merchant_user = Merchant::find($parcel->merchant_id);
                            // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            //$this->merchantDashboardCounterEvent($parcel->merchant_id);

                            //$this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();

                        //$this->adminDashboardCounterEvent();

                        $response = ['success' => 'Reject Delivery Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
                    //                    $response = ['error' => $e->getMessage()];
                }
            }
        }
        return response()->json($response);
    }



    /** Delivery Payment statement */
    public function branchDeliveryPaymentStatement()
    {
        $data               = [];
        $data['main_menu']  = 'branch-payment';
        $data['child_menu'] = 'branchPaymentStatement';
        $data['page_title'] = 'Branch Delivery Payment Statement';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['parcel_payment_reports']   = [];
        $data['payment_total_amount']     = 0;
        $data['payment_total_pending_amount']   = 0;
        $data['payment_total_receive_amount']   = 0;

        $from_date  = Carbon::now()->subMonth()->format("Y-m-d");
        $to_date    = Carbon::now()->format("Y-m-d");

        $parcel_payment_data    = ParcelDeliveryPaymentDetail::whereBetween('created_at', [$from_date, $to_date])->get();

        //dd($parcel_payment_data);
        $data['date_array'] = array();
        $data['pinvoice_array'] = array();
        if (count($parcel_payment_data) > 0) {

            foreach ($parcel_payment_data as $payment_data) {
                $delivery_date = date("Y-m-d", strtotime($payment_data->created_at));
                $payment_invoice    = $payment_data->parcel_delivery_payment->payment_invoice;
                $data['date_array'][$delivery_date][]    = $payment_data->id;
                $data['pinvoice_array'][$payment_invoice][]    = $payment_data->parcel->parcel_invoice;
            }
        }

        $data['parcel_payment_data'] = $parcel_payment_data;


        return view('admin.account.deliveryPayment.deliveryParcelPaymentStatement', $data);
    }


    public function getBranchDeliveryPaymentStatement(Request $request)
    {

        $data               = [];
        $parcel_payment_data    = ParcelDeliveryPaymentDetail::with(['parcel', 'parcel_delivery_payment'])

            ->where(function ($query) use ($request) {

                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                $branch_id = $request->input('branch_id');
                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->whereHas('parcel_delivery_payment', function ($query)  use ($branch_id) {
                        $query->where('branch_id', $branch_id);
                    });
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('created_at', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('created_at', '<=', $request->input('to_date'));
                }
            })->get();

        //dd($parcel_payment_data);

        $data['date_array'] = array();
        $data['pinvoice_array'] = array();
        if (count($parcel_payment_data) > 0) {

            foreach ($parcel_payment_data as $payment_data) {
                $delivery_date = date("Y-m-d", strtotime($payment_data->created_at));
                $payment_invoice    = $payment_data->parcel_delivery_payment->payment_invoice;
                $data['date_array'][$delivery_date][]    = $payment_data->id;
                $data['pinvoice_array'][$payment_invoice][]    = $payment_data->parcel->parcel_invoice;
            }
        }

        $data['parcel_payment_data'] = $parcel_payment_data;


        return view('admin.account.deliveryPayment.filterBranchDeliveryPaymentStatement', $data);
    }
}
