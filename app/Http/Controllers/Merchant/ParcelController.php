<?php

namespace App\Http\Controllers\Merchant;


use DataTables;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Rider;
use App\Models\Branch;
use App\Models\Parcel;
use App\Models\Upazila;
use App\Models\District;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\RiderRun;
use App\Models\ParcelLog;
use App\Models\ServiceType;
use App\Models\MerchantShop;
use Illuminate\Http\Request;
use App\Models\WeightPackage;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MerchantParcelExport;
use App\Imports\MerchantBulkParcelImport;
use App\Models\MerchantServiceAreaCharge;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantServiceAreaCodCharge;
use App\Models\MerchantServiceAreaReturnCharge;

class ParcelController extends Controller
{

    public function pushRequest(Request $request)
    {
        $parcels = Parcel::with('merchant')->where('is_push', 1)->where('status', 0)
            ->where(function ($query) use ($request) {
                // $merchant_id = $request->input('merchant_id');
                //  $branch_id = $request->input('branch_id');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');
                $merchant_id = auth()->guard('merchant')->user()->id;

                $query->where('merchant_id', $merchant_id);

                // if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                //     $query->where('merchant_id', $request->input('merchant_id'));
                // }

                // if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                //     $query->whereHas('merchant', function ($query) use ($branch_id) {
                //         $query->where('branch_id', $branch_id);
                //     });
                // }

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
        $data['merchants']   = Merchant::where('status', 1)->get();
        $data['branches']   = Branch::where('status', 1)->get();
        $data['parcels']   = $parcels;
        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        return view('merchant.parcel.pushRequest', $data);
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

    public function add()
    {
        if (auth()->guard('merchant')->user()->branch_id == 0 || !auth()->guard('merchant')->user()->branch_id) {
            $this->setMessage('Your branch Not Assigned. You are waiting for authorization!', 'danger');
            return redirect()->back();
        }
        $merchant_id = auth()->guard('merchant')->user()->id;
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'addParcel';
        $data['page_title'] = 'Add Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['districts'] = District::where('status', 1)->get();
        $data['merchant'] = Merchant::with('branch')->where('id', $merchant_id)->first();
        $data['merchantShops'] = MerchantShop::where('merchant_id', $merchant_id)->where('status', 1)->get();
        return view('merchant.parcel.addParcel', $data);
    }

    // For getting customer Info -->

    // public function customerInfo(Request $request){
    //     $phone = $request->phone;
    //     // dd($phone);
    //     $customer = Parcel::where('customer_contact_number',$phone)->select('customer_name','customer_address')->first();
    //     return response()->json($customer);
    // }
    // For getting customer Info -->



    // For getting customer Info -->

    public function customerInfo(Request $request)
    {
        $phone = $request->phone;
        $phone2 = $request->phone2;
        // dd($phone);
        // $customer = Parcel::where('customer_contact_number',$phone)->select('customer_name','customer_address')->first();
        // $customer = Parcel::where('customer_contact_number',$phone)->select('customer_name','customer_address','district_id','area_id')->first();
        // $customerParcel = Parcel::where('customer_contact_number',$phone)->count();
        // $totalDeliveryComplete = Parcel::whereRaw("customer_contact_number = '$phone' and status >= 25 and delivery_type in (1)")->select('id')->count();
        // $totalDeliveryPending = Parcel::whereRaw("customer_contact_number = '$phone' and status < 25 ")->select('id')->count();
        // $totalDeliveryCancel = Parcel::whereRaw("customer_contact_number = '$phone' and status >= 25 and delivery_type in (4)")->select('id')->count();

        if ($request->phone) {
            $customer = Parcel::whereNull('sub_order')->where('customer_contact_number', $phone)->select('customer_name', 'customer_address', 'district_id', 'area_id')->first();
            $customerParcel = Parcel::whereNull('sub_order')->where('customer_contact_number', $phone)->count();
            $totalDeliveryComplete = Parcel::whereNull('sub_order')->whereRaw("customer_contact_number = '$phone' and status >= 25 and delivery_type in (1)")->select('id')->count();
            $totalDeliveryPending = Parcel::whereNull('sub_order')->whereRaw("customer_contact_number = '$phone' and status < 25 ")->select('id')->count();
            $totalDeliveryCancel = Parcel::whereNull('sub_order')->whereRaw("customer_contact_number = '$phone' and status >= 25 and delivery_type in (4)")->select('id')->count();
        } elseif ($request->phone2) {
            $customer = Parcel::whereNull('sub_order')->where('customer_contact_number2', $phone2)->select('customer_name', 'customer_address', 'district_id', 'area_id')->first();
            $customerParcel = Parcel::whereNull('sub_order')->where('customer_contact_number2', $phone2)->count();
            $totalDeliveryComplete = Parcel::whereNull('sub_order')->whereRaw("customer_contact_number2 = '$phone2' and status >= 25 and delivery_type in (1)")->select('id')->count();
            $totalDeliveryPending = Parcel::whereNull('sub_order')->whereRaw("customer_contact_number2 = '$phone2' and status < 25 ")->select('id')->count();
            $totalDeliveryCancel = Parcel::whereNull('sub_order')->whereRaw("customer_contact_number2 = '$phone2' and status >= 25 and delivery_type in (4)")->select('id')->count();
        }

        if ($customerParcel > 0) {
            $percenrtComplete = round(($totalDeliveryComplete / $customerParcel) * 100);
        } else {
            $percenrtComplete = 0;
        }

        if ($customerParcel > 0) {
            $percenrtPending = round(($totalDeliveryPending / $customerParcel) * 100);
        } else {
            $percenrtPending = 0;
        }

        if ($customerParcel > 0) {
            $percenrtCancle = round(($totalDeliveryCancel / $customerParcel) * 100);
        } else {
            $percenrtCancle = 0;
        }

        return response()->json([
            'customer' => $customer,
            'customerParcel' => $customerParcel,
            'totalDeliveryComplete' => " Delivered:" . " " . $totalDeliveryComplete,
            'totalDeliveryPending' => " Pending: " . " " . $totalDeliveryPending,
            'totalDeliveryCancel' => " Canceled: " . " " . $totalDeliveryCancel,
            'percenrtComplete' => "(" . $percenrtComplete . "%)",
            'percenrtPending' => "(" . $percenrtPending . "%)",
            'percenrtCancel' => "(" . $percenrtCancle . "%)",

        ]);
    }
    // For getting customer Info -->

    public function store(Request $request)
    {
        //        dd($request->all());
        $validator = Validator::make($request->all(), [
            'merchant_order_id' => 'sometimes',
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            //          'product_details' => 'required',
            //         'product_value' => 'required|numeric|min:1',
            'product_value' => 'sometimes',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required|numeric|digits:11',
            'customer_contact_number2' => 'nullable|numeric|digits:11',
            'customer_address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'                   => 'required',
            'area_id' => 'required',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        \DB::beginTransaction();
        try {

            $merchant = auth()->guard('merchant')->user();

            $data = [
                'parcel_invoice' => $this->returnUniqueParcelInvoice(),
                'merchant_id' => $merchant->id,
                'date' => date('Y-m-d'),
                'merchant_order_id' => $request->input('merchant_order_id'),
                'shop_id' => $request->input('shop_id'),
                'pickup_address' => $request->input('pickup_address'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'customer_contact_number2' => $request->input('customer_contact_number2'),
                'exchange' => $request->input('exchange'),
                'product_details' => $request->input('product_details'),
                'product_value' => $request->input('product_value'),
                'district_id' => $request->input('district_id'),
                // 'upazila_id'                   => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'weight_package_id' => $request->input('weight_package_id'),
                'delivery_charge' => $request->input('delivery_charge'),
                'weight_package_charge' => $request->input('weight_package_charge'),
                'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
                'cod_percent' => $request->input('cod_percent'),
                'cod_charge' => $request->input('cod_charge'),
                'total_charge' => $request->input('total_charge'),
                'item_type_charge' => $request->input('item_type_charge'),
                'service_type_charge' => $request->input('service_type_charge'),
                'delivery_option_id' => $request->input('delivery_option_id'),
                'parcel_note' => $request->input('parcel_note'),
                'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'pickup_branch_id' => $merchant->branch_id,
                'parcel_date' => date('Y-m-d'),
                'status' => 1,
            ];

            $parcel = Parcel::create($data);

            if (!empty($parcel)) {

                $data = [
                    'parcel_id' => $parcel->id,
                    'merchant_id' => $merchant->id,
                    'pickup_branch_id' => $merchant->branch_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 1,
                ];
                ParcelLog::create($data);
                // \DB::commit();


                /*+++++++++++++++++++++++++++
                // PaperFly order Placement
                +++++++++++++++++++++++++++++*/
                $parameter_data = json_encode([
                    "merOrderRef" => $parcel->parcel_invoice,
                    "pickMerchantName" => $merchant->company_name,
                    "pickMerchantAddress" => $merchant->address,
                    // "pickMerchantThana"     => $merchant->upazila->name,
                    "pickMerchantDistrict" => $merchant->district->name,
                    "pickupMerchantPhone" => $merchant->contact_number,
                    "productSizeWeight" => "statndard",
                    "ProductBrief" => $parcel->product_details,
                    "packagePrice" => $parcel->total_collect_amount,
                    "max_weight" => "1",
                    "deliveryOption" => "regular",
                    "custname" => $parcel->customer_name,
                    "custaddress" => $parcel->customer_address,
                    "customerThana" => $parcel->upazila->name,
                    // "customerDistrict"      => $parcel->district->name,
                    "custPhone" => $parcel->customer_contact_number
                ]);

                // $order_placement = json_decode($this->callPaperFlyAPI($parameter_data), true);

                // if($order_placement && $order_placement['response_code'] == 200){
                //     $tracking_number = $order_placement['success']['tracking_number'];

                //     Parcel::where('id', $parcel->id)
                //     ->update([
                //         'tracking_number' => $tracking_number
                //     ]);
                // }
                /*+++++++++++++++++++++++++++
                // PaperFly order Placement
                +++++++++++++++++++++++++++++*/


                \DB::commit();

                //                $this->sweetAlertMessage('success', '', 'Parcel Create Successfully');
                $this->setMessage('Parcel Create Successfully', 'success');
                return redirect()->back();
                //                return redirect()->route('merchant.parcel.list');
            } else {
                $this->sweetAlertMessage('error', '', 'Parcel Create Failed');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function list()
    {

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('merchant.parcel.parcelList', $data);
    }

    public function returnList()
    {
        $data = [];
        $data['main_menu'] = 'return';
        $data['child_menu'] = 'returnList';
        $data['page_title'] = 'Return List';
        $data['collapse']   = 'sidebar-collapse';
        $data['riders']     = Rider::where([
            'status'    => 1
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['riderRun'] = RiderRun::with('rider_run_details')->where('run_type', 3)->get();

        return view('merchant.parcel.returnList', $data);
    }

    public function getReturnRiderRunList(Request $request)
    {
        $model = RiderRun::with([
            'rider:id,name,contact_number,address',
            'rider_run_details.parcel'
        ])
            // ->whereHas('rider_run_details.parcel', function ($query) {
            //     $query->where('merchant_id', auth()->guard('merchant')->user()->id);
            // })
            ->where('run_type', 3)
            ->orderByDesc('id')
            ->select();
        // $model = RiderRun::with([
        //     'rider' => function ($query) {
        //         $query->select('id', 'name', 'contact_number', 'address');
        //     },
        //     'rider_run_details.parcel'
        // ])
        //     ->whereHas('rider_run_details.parcel', function ($query) {
        //         $query->where('merchant_id', auth()->guard('merchant')->user()->id);
        //     })
        //     ->where('run_type', 3)
        //     ->orderBy('id', 'desc')
        //     ->select();

        //dd($model->get());
        if ($request->has('run_status') && ! is_null($request->get('run_status')) && $request->get('run_status') != 0) {
            $model->where('status', $request->get('run_status'));
        } elseif ($request->get('run_status') == '') {
            $model->whereIn('status', [1, 2]);
        } else {
            $model->whereIn('status', [1, 2, 3, 4]);
        }

        if ($request->has('rider_id') && ! is_null($request->get('rider_id')) && $request->get('rider_id') != 0) {
            $model->where('rider_id', $request->get('rider_id'));
        }
        if ($request->has('from_date') && ! is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('create_date_time', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && ! is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('create_date_time', '<=', $request->get('to_date'));
        }

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('create_date_time', function ($data) {
                return date('d-m-Y H:i:s', strtotime($data->create_date_time));
            })
            ->editColumn('start_date_time', function ($data) {
                return ($data->start_date_time) ? date('d-m-Y H:i:s', strtotime($data->start_date_time)) : "";
            })
            ->editColumn('cancel_date_time', function ($data) {
                return ($data->cancel_date_time) ? date('d-m-Y H:i:s', strtotime($data->cancel_date_time)) : "";
            })
            ->editColumn('complete_date_time', function ($data) {
                //    return auth()->guard('merchant')->user()->id;
                // return $data->rider_run_details->first()->parcel->merchant_id;
                return ($data->complete_date_time) ? date('d-m-Y H:i:s', strtotime($data->complete_date_time)) : "";
            })

            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1:
                        $status_name  = "Run Create";
                        $class  = "success";
                        break;
                    case 2:
                        $status_name  = "Run Start";
                        $class  = "success";
                        break;
                    case 3:
                        $status_name  = "Run Cancel";
                        $class  = "danger";
                        break;
                    case 4:
                        $status_name  = "Run Complete";
                        $class  = "success";
                        break;
                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                // $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                // <i class="fa fa-eye"></i> </button>';

                // $button .= '&nbsp; <a href="' . route('branch.parcel.printReturnRiderRun', $data->id) . '" class="btn btn-success btn-sm" title="Print Return Rider Run" target="_blank">
                //     <i class="fas fa-print"></i> </a>';

                // if ($data->status == 1) {
                //     $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Return Run Start">
                //     <i class="far fa-play-circle"></i> </button>';

                //     $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Return Run Cancel">
                //     <i class="far fa-window-close"></i> </button>';

                //     $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnRiderRun', $data->id) . '" class="btn btn-info btn-sm" title="Edit Return Run" >
                //         <i class="fas fa-edit"></i> </a>';
                // }
                // if ($data->status == 2) {
                //     $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" title="Reconciliation Return Run">
                //     <i class="fa fa-check"></i> </button> ';
                // }
                //return $button;
            })
            ->rawColumns(['action', 'status', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time'])
            ->make(true);
    }

    public function getParcelList(Request $request)
    {
        $merchant_id = auth()->guard('merchant')->user()->id;

        $model = Parcel::with([
            'service_type',
            'item_type',
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address,contact_number',
            'parcel_logs'
        ])
            // ->where('status', '!=', 0)
            ->whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {
                $parcel_status = $request->input('parcel_status');
                $parcel_invoice = $request->input('parcel_invoice');
                $merchant_order_id = $request->input('merchant_order_id');
                $customer_contact_number = $request->input('customer_contact_number');
                $customer_contact_number2 = $request->input('customer_contact_number2');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');

                if (($request->has('parcel_status') && !is_null($parcel_status))
                    || ($request->has('parcel_invoice') && !is_null($parcel_invoice))
                    || ($request->has('customer_contact_number') && !is_null($customer_contact_number))
                    || ($request->has('customer_contact_number2') && !is_null($customer_contact_number2))
                    || ($request->has('merchant_order_id') && !is_null($merchant_order_id))
                    || ($request->has('from_date') && !is_null($from_date))
                    || ($request->has('to_date') && !is_null($to_date))
                ) {
                    if ((!is_null($parcel_invoice))) {

                        $query->where('parcel_invoice', 'like', "%{$parcel_invoice}%");
                        $query->orWhere('merchant_order_id', 'like', "%{$parcel_invoice}%");
                        $query->orWhere('customer_contact_number', 'like', "%{$parcel_invoice}%");


                        /*if (!is_null($parcel_invoice) && !is_null($parcel_invoice)) {
                            $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                        } elseif (!is_null($merchant_order_id) && !is_null($merchant_order_id)) {
                            $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                        } elseif (!is_null($customer_contact_number) && !is_null($customer_contact_number)) {
                            $query->where('customer_contact_number', 'like', "%$customer_contact_number");
                        }*/
                    } else {
                        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                            if ($parcel_status == 1) {
                                $query->whereRaw('status >= 25 and delivery_type in (1,2)');
                            } elseif ($parcel_status == 2) {
                                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                                //    $query->whereRaw('status > 11 and delivery_type in (?)', [3]);
                                $query->whereRaw('status >= 10 and (status < 25 or (status = 25 and delivery_type = 3))');
                            } elseif ($parcel_status == 3) {
                                // $query->whereRaw('status = 3');
                                $query->whereRaw('status >= ? and delivery_type in (?)', [25, 4]);
                            } elseif ($parcel_status == 4) {
                                $query->whereRaw('status >= 25 and payment_type = 5 and (delivery_type = 1 or delivery_type = 2)');
                            } elseif ($parcel_status == 5) {
                                $query->whereRaw('status >= 25 and payment_type !=5 and delivery_type = 1 or delivery_type = 2');
                                // $query->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type = 1 or delivery_type = 2');
                            } elseif ($parcel_status == 6) {
                                // $query->whereRaw('status = 36 and delivery_type = 4');
                                $query->whereRaw('status = ? and delivery_type in (?)', [36, 4]);
                            } elseif ($parcel_status == 7) {
                                $query->whereRaw('status >= ? AND status <= ? AND status != ?', [1, 9, 3]);

                                // $query->whereRaw('status in (1) and delivery_type IS NULL or delivery_type = ""');
                            } elseif ($parcel_status == 8) {
                                $query->whereRaw('status >= 25 and delivery_type in(3)');
                            }
                        }
                        /* if ($request->has('from_date') && !is_null($from_date)) {
                            $query->whereDate('date', '>=', $from_date);
                        }
                        if ($request->has('to_date') && !is_null($to_date)) {
                            $query->whereDate('date', '<=', $to_date);
                        }*/


                        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
                            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                                if ($parcel_status == 1) {
                                    $query->whereDate('delivery_date', '>=', $request->get('from_date'));
                                } elseif ($parcel_status == 2) {
                                    $query->whereDate('pickup_branch_date', '>=', $request->get('from_date'));
                                } else {
                                    $query->whereDate('date', '>=', $request->get('from_date'));
                                }
                            } else {
                                $query->whereDate('date', '>=', $request->get('from_date'));
                            }
                        }

                        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
                            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                                if ($parcel_status == 1) {
                                    $query->whereDate('delivery_date', '<=', $request->get('to_date'));
                                } elseif ($parcel_status == 2) {
                                    $query->whereDate('pickup_branch_date', '<=', $request->get('to_date'));
                                } else {
                                    $query->whereDate('date', '<=', $request->get('to_date'));
                                }
                            } else {
                                $query->whereDate('date', '<=', $request->get('to_date'));
                            }
                        }
                    }
                }
                /* else {
                    $query->where('status', '!=', 3);
                }*/
            })
            ->orderBy('id', 'desc')
            ->select(
                'id',
                'created_at',
                'parcel_invoice',
                'tracking_number',
                'merchant_id',
                'date',
                'parcel_note',
                'merchant_order_id',
                'customer_name',
                'customer_address',
                'customer_contact_number',
                'customer_contact_number2',
                'customer_collect_amount',
                'total_collect_amount',
                'cod_charge',
                'exchange',
                'product_details',
                'weight_package_charge',
                'delivery_charge',
                'reschedule_parcel_date',
                'delivery_date',
                'pickup_branch_date',
                'total_charge',
                'district_id',
                'service_type_id',
                'item_type_id',
                'upazila_id',
                'area_id',
                'status',
                'delivery_type',
                'payment_type',
                'suborder',
                'cancel_amount_collection',
                'parcel_otp',
            );

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('parcel_invoice', function ($data) {
                $x = '';
                $suborderId = Parcel::where('parcel_invoice', $data->suborder)->first()?->id;

                if ($data->suborder) {
                    $x = '<br>Main Order:<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $suborderId . '"  title="Parcel View">
                ' . $data->suborder . ' </button>';

                    //$x = '<br></span> <p><strong>Main Order: </strong>' . $data->suborder . '</p>';
                }

                // $date_time =  $data->date . " " . date("h:i A", strtotime($data->created_at));
                $date_time =   $data->created_at->format('Y-m-d h:i A');

                return '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '"  title="Parcel View">
                ' . $data->parcel_invoice . ' </button><br></span> <p><strong></strong>' . $date_time . '</p>' . $x;
            })
            ->editColumn('parcel_status', function ($data) {
                $date_time = '---';
                if ($data->status >= 25) {
                    if ($data->delivery_type == 3) {
                        $date_time = date("Y-m-d", strtotime($data->reschedule_parcel_date));
                    }
                    if ($data->delivery_type == 4) {
                        $date_time = $data->created_at->format('Y-m-d');
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    }
                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    // $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                    $date_time = $data->created_at->format('Y-m-d');
                }
                $parcelStatus = returnParcelStatusNameForMerchant($data->status, $data->delivery_type, $data->payment_type, $data->parcel_invoice);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class="  text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong></strong>' . $date_time . '</p>';
            })
            ->editColumn('payment_status', function ($data) {
                $date  = $data?->merchantDeliveryPayment?->created_at->format('d-m-Y h:i A');
                $x = '';
                $payment_invoice = $data?->merchantDeliveryPayment?->payment_invoice;
                if ($date && $payment_invoice) {
                    $x = $date . '<br>' . $payment_invoice;
                }

                $parcelStatus = returnPaymentStatusForMerchant($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                $time        = isset($parcelStatus['time']) ?  $parcelStatus['time'] : '';
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span><br>' . $time;
            })
            ->editColumn('return_status', function ($data) {
                $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type, $data);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                $time        = isset($parcelStatus['time']) ?  $parcelStatus['time'] : '';
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span><br>' . $time;
            })
            // ->editColumn('payment_status', function ($data) {
            //     $return = "";
            //     $parcelStatus = returnPaymentStatusForMerchant($data->status, $data->delivery_type, $data->payment_type);
            //     $status_name = $parcelStatus['status_name'];
            //     $class = $parcelStatus['class'];
            //     $return .= '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <br>';


            //     $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
            //     $status_name = $parcelStatus['status_name'];
            //     $class = $parcelStatus['class'];
            //     $return .= '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            //     return $return;
            // })

            // ->editColumn('return_status', function ($data) {
            //     $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
            //     $status_name = $parcelStatus['status_name'];
            //     $class = $parcelStatus['class'];
            //     return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            // })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                <i class="fas fa-print"></i> </a>';

                $button .= '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '"  title="Parcel View">
                <i class="fa fa-eye"></i> </button>';

                if ($data->status != 3) {
                    if ($data->status == 1 || $data->status == 4) {
                        $button .= '&nbsp; <button class="btn btn-warning pickup-hold btn-sm" parcel_id="' . $data->id . '" title="Hold Parcel Processing">
                        <i class="far fa-pause-circle"></i></button>';
                    } elseif ($data->status == 2) {
                        $button .= '&nbsp; <button class="btn btn-success pickup-start btn-sm" parcel_id="' . $data->id . '" title="Start Parcel Processing" >
                            <i class="far fa-play-circle"></i>
                         </button>';
                    }

                    if ($data->status == 1) {
                        $button .= '&nbsp; <button class="btn btn-danger pickup-cancel btn-sm" parcel_id="' . $data->id . '" title="Parcel Cancel">
                                        <i class="far fa-window-close"></i>
                                    </button>';

                        $button .= '&nbsp;  <a class="btn btn-secondary btn-sm" href="' . route('merchant.parcel.edit', $data->id) . '"   title="Parcel Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                </a>';
                    }
                }
                return $button;
            })
            ->addColumn('parcel_info', function ($data) {
                $abc = is_null($data->parcel_otp) ? 'N/A' : $data->parcel_otp;

                $parcel_info = '<p><strong>Merchant Order ID: </strong>' . $data->merchant_order_id . '</p>';
                // $parcel_info .= '<p><strong>Parcel OTP: </strong>' . $data->parcel_code . '</p>';
                $parcel_info .= '<p><strong>OTP: </strong>' . $abc . '</p>';
                $parcel_info .= '<p><strong>Service Type: </strong>' . optional($data->service_type)->title . '</p>';
                $parcel_info .= '<p><strong>Item Type: </strong>' . optional($data->item_type)->title . '</p>';
                $parcel_info .= '<p><strong>Exchange: </strong>' . $data->exchange . '</p>';
                $parcel_info .= '<p><strong>Product Details: </strong>' . $data->product_details . '</p>';

                return $parcel_info;
            })

            ->addColumn('company_info', function ($data) {
                $company_info = '<p><strong>Name: </strong>' . $data->merchant->company_name . '</p>';
                $company_info .= '<p><strong>Phone: </strong>' . $data->merchant->contact_number . '</p>';
                //                $company_info .= '<span><strong>Address: </strong>'.$data->merchant->address.'</span>';
                return $company_info;
            })


            ->addColumn('customer_info', function ($data) {
                $district = "---";
                if ($data->district) {
                    $district = $data->district->name;
                }
                $area = "---";
                if ($data->area) {
                    $area = $data->area->name;
                }

                $customer_contact_number2 = "---";
                if ($data->customer_contact_number2) {
                    $customer_contact_number2 = $data->customer_contact_number2;
                }

                $customer_info = '<p><strong>Name: </strong>' . $data->customer_name . '</p>';
                $customer_info .= '<p><strong>Number: </strong>' . $data->customer_contact_number . '</p>';
                $customer_info .= '<p><strong>Alternative: </strong>' . $customer_contact_number2 . '</p>';
                $customer_info .= '<p><strong>District: </strong>' . $district . '</p>';
                $customer_info .= '<p><strong>Area: </strong>' . $area . '</p>';
                $customer_info .= '<span><strong>Address: </strong>' . $data->customer_address . '</span>';

                return $customer_info;
            })
            ->addColumn('amount', function ($data) {
                $amount = '<p><strong>Amount to be Collect: ৳ </strong>' . $data->total_collect_amount . '</p>';

                if ($data->status == 21 || $data->status == 22) {
                    $amount .= '<p><strong>Collected: ৳ </strong>' . $data->customer_collect_amount . '</p>';
                }

                if ($data->status == 25) {

                    if ($data->delivery_type == 1 || $data->delivery_type == 2) {
                        $amount .= '<p><strong>Collected: ৳ </strong>' . $data->customer_collect_amount . '</p>';
                    }

                    if ($data->delivery_type == 4) {
                        $amount .= '<p><strong>Collected: ৳ </strong>' . $data->cancel_amount_collection . '</p>';
                    }
                }

                if ($data->status == 24) {
                    $amount .= '<p><strong>Collected: ৳ </strong>' . $data->cancel_amount_collection . '</p>';
                }

                $amount .= '<p><strong>Delivery Charge:  ৳ </strong>' . $data->delivery_charge . '</p>';
                // $amount .= '<p><strong>Delivery Charge: </strong>'.$data->total_charge.'</p>';
                // $amount .= '<p><strong>COD Charge: </strong>'.$data->cod_charge.'</p>';
                return $amount;
            })
            ->addColumn('print', function ($data) {
                return '<input type="checkbox" class="print-check" id="" value="' . $data->id . '"/>';
            })
            ->addColumn('remarks', function ($data) {
                $logs_note = "";
                if ($data->parcel_logs) {
                    foreach ($data->parcel_logs as $parcel_log) {
                        if ("" != $logs_note) {
                            $logs_note .= ",<br>";
                        }
                        $logs_note .= $parcel_log->note;
                    }
                }
                $remarks = '<span><strong>Remarks: </strong>' . $data->parcel_note . '</span> <br>';
                $remarks .= '<span><strong>Notes: </strong>' . $logs_note . '</span>';
                return $remarks;
            })

            ->rawColumns([
                'parcel_invoice',
                'parcel_status',
                'payment_status',
                'return_status',
                'action',
                'image',
                'parcel_info',
                'company_info',
                'customer_info',
                'amount',
                'remarks',
                'print',
            ])
            ->make(true);
    }

    public function printParcelList(Request $request)
    {
        $merchant_id = auth()->guard('merchant')->user()->id;
        $filter = [];

        $parcel_status = $request->input('parcel_status');
        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');
        $customer_contact_number = $request->input('customer_contact_number');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        if ($parcel_status && $parcel_status != 0) {
            $filter['parcel_status'] = $parcel_status;
        }
        if ($parcel_invoice && $parcel_invoice != 0) {
            $filter['parcel_invoice'] = $parcel_invoice;
        }
        if ($merchant_order_id && $merchant_order_id != 0) {
            $filter['merchant_order_id'] = $merchant_order_id;
        }
        if ($customer_contact_number && $customer_contact_number != 0) {
            $filter['customer_contact_number'] = $customer_contact_number;
        }
        if ($from_date && $from_date != 0) {
            $filter['from_date'] = $from_date;
        }
        if ($to_date && $to_date != 0) {
            $filter['to_date'] = $to_date;
        }

        $model = Parcel::with([
            'service_type',
            'item_type',
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address',
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },
        ])
            ->whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {
                $parcel_status = $request->input('parcel_status');
                $parcel_invoice = $request->input('parcel_invoice');
                $merchant_order_id = $request->input('merchant_order_id');
                $customer_contact_number = $request->input('customer_contact_number');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');
                if (($request->has('parcel_status') && !is_null($parcel_status))
                    || ($request->has('parcel_invoice') && !is_null($parcel_invoice))
                    || ($request->has('customer_contact_number') && !is_null($customer_contact_number))
                    || ($request->has('merchant_order_id') && !is_null($merchant_order_id))
                    || ($request->has('from_date') && !is_null($from_date))
                    || ($request->has('to_date') && !is_null($to_date))
                ) {
                    if ((!is_null($parcel_invoice) && !is_null($parcel_invoice))
                        || (!is_null($merchant_order_id) && !is_null($merchant_order_id))
                        || (!is_null($customer_contact_number) && !is_null($customer_contact_number))
                    ) {
                        if (!is_null($parcel_invoice) && !is_null($parcel_invoice)) {
                            $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                        } elseif (!is_null($merchant_order_id) && !is_null($merchant_order_id)) {
                            $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                        } elseif (!is_null($customer_contact_number) && !is_null($customer_contact_number)) {
                            $query->where('customer_contact_number', 'like', "%$customer_contact_number");
                        }
                    } else {
                        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                            if ($parcel_status == 1) {
                                $query->whereRaw('status >= 25 and delivery_type in (1,2)');
                            } elseif ($parcel_status == 2) {
                                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                                //    $query->whereRaw('status > 11 and delivery_type in (?)', [3]);
                                $query->whereRaw('status >= 11 and (status < 25 or (status = 25 and delivery_type = 3))');
                            } elseif ($parcel_status == 3) {
                                // $query->whereRaw('status = 3');
                                $query->whereRaw('status >= ? and delivery_type in (?)', [25, 4]);
                            } elseif ($parcel_status == 4) {
                                $query->whereRaw('status >= 25 and payment_type = 5 and (delivery_type = 1 or delivery_type = 2)');
                            } elseif ($parcel_status == 5) {
                                $query->whereRaw('status >= 25 and payment_type !=5 and delivery_type = 1 or delivery_type = 2');
                                // $query->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type = 1 or delivery_type = 2');
                            } elseif ($parcel_status == 6) {
                                // $query->whereRaw('status = 36 and delivery_type = 4');
                                $query->whereRaw('status = ? and delivery_type in (?)', [36, 4]);
                            } elseif ($parcel_status == 7) {
                                $query->whereRaw('status in (1) and delivery_type IS NULL or delivery_type = ""');
                            } elseif ($parcel_status == 8) {
                                $query->whereRaw('status >= 25 and delivery_type in(3)');
                            }
                        }
                        if ($request->has('from_date') && !is_null($from_date)) {
                            $query->whereDate('date', '>=', $from_date);
                        }
                        if ($request->has('to_date') && !is_null($to_date)) {
                            $query->whereDate('date', '<=', $to_date);
                        }
                    }
                } else {
                    $query->where('status', '!=', 3);
                }
            })
            ->orderBy('id', 'desc')
            ->select(
                'id',
                'parcel_invoice',
                'tracking_number',
                'merchant_id',
                'date',
                'parcel_note',
                'merchant_order_id',
                'customer_name',
                'customer_address',
                'customer_contact_number',
                'customer_collect_amount',
                'total_collect_amount',
                'cod_charge',
                'weight_package_charge',
                'delivery_charge',
                'total_charge',
                'district_id',
                'service_type_id',
                'item_type_id',
                'upazila_id',
                'area_id',
                'status',
                'delivery_type',
                'payment_type'
            );
        $parcels = $model->get();
        return view('merchant.parcel.printParcelList', compact('parcels', 'filter'));
    }

    public function printParcelMultiple(Request $request)
    {
        //        dd($request->all());
        $parcel_ids = $request->input('parcel_ids');
        $parcels = Parcel::whereIn("id", $parcel_ids)->with('merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider')->get();
        //        dd($parcels);

        return view('merchant.parcel.printParcelMultiple', compact('parcels'));
    }

    public function parcelHold(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status' => 2,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', '=', $request->parcel_id)->update($data);

                if ($parcel) {

                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'merchant_id' => auth()->guard('merchant')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 2,
                    ];
                    ParcelLog::create($data);

                    // $this->merchantDashboardCounterEvent($data['merchant_id']);
                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Parcel Hold Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }

    public function parcelStart(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status' => 1,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);

                if ($parcel) {
                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'merchant_id' => auth()->guard('merchant')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 1,
                    ];
                    ParcelLog::create($data);

                    // $this->merchantDashboardCounterEvent($data['merchant_id']);
                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Parcel Start Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }

    public function parcelCancel(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status' => 3,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);

                if ($parcel) {
                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'merchant_id' => auth()->guard('merchant')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 3,
                    ];
                    ParcelLog::create($data);

                    // $this->merchantDashboardCounterEvent($data['merchant_id']);
                    // // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Parcel Cancel Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }

    public function viewParcel(Request $request, Parcel $parcel)
    {
        $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
            ->where('parcel_id', $parcel->id)->orderBy('id', 'desc')->get();

        $parcel->load('district', 'upazila', 'area', 'merchant', 'merchant_shops', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('merchant.parcel.viewParcel', compact('parcel', 'parcelLogs'));
    }

    public function edit(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        $merchant_id = auth()->guard('merchant')->user()->id;
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'addParcel';
        $data['page_title'] = 'Edit Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        // $data['upazilas'] = Upazila::where([
        //             ['district_id', '=', $parcel->district->id],
        //             ['status', '=', 1],
        //         ])->get();

        $data['areas'] = Area::where([
            ['district_id', '=', $parcel->district_id],
            ['status', '=', 1],
        ])->get();
        $service_area_id = $parcel->district->service_area_id;

        $data['weightPackages'] = WeightPackage::with([
            'service_area' => function ($query) use ($service_area_id) {
                $query->where('service_area_id', '=', $service_area_id);
            },
        ])
            ->where([
                ['status', '=', 1],
                ['weight_type', '=', 1],
            ])->get();
        $data['serviceTypes'] = ServiceType::where('service_area_id', $service_area_id)->get();
        $data['itemTypes'] = ItemType::where('service_area_id', $service_area_id)->get();
        $data['merchant'] = Merchant::with('branch')->where('id', $merchant_id)->first();
        $data['merchantShops'] = MerchantShop::where('merchant_id', $merchant_id)->where('status', 1)->get();

        return view('merchant.parcel.editParcel', $data);
    }


    public function update(Request $request, Parcel $parcel)
    {
        //        dd($request->all());
        $validator = Validator::make($request->all(), [
            'merchant_order_id' => 'sometimes',
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            //            'product_details' => 'required',
            'product_value' => 'required|numeric|min:1',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required|numeric|digits:11',
            'customer_address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'                   => 'required',
            'area_id' => 'sometimes',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        \DB::beginTransaction();
        // try {

        $merchant = auth()->guard('merchant')->user();

        $data = [
            'merchant_order_id' => $request->input('merchant_order_id'),
            'shop_id' => $request->input('shop_id'),
            'pickup_address' => $request->input('pickup_address'),
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details' => $request->input('product_details'),
            'district_id' => $request->input('district_id'),
            // 'upazila_id'                   => $request->input('upazila_id'),
            'upazila_id' => 0,
            'area_id' => $request->input('area_id') ?? 0,
            'weight_package_id' => $request->input('weight_package_id'),
            'delivery_charge' => $request->input('delivery_charge'),
            'weight_package_charge' => $request->input('weight_package_charge'),
            'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
            'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
            'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
            'cod_percent' => $request->input('cod_percent'),
            'cod_charge' => $request->input('cod_charge'),
            'total_charge' => $request->input('total_charge'),
            'delivery_option_id' => $request->input('delivery_option_id'),
            'product_value' => $request->input('product_value'),
            'parcel_note' => $request->input('parcel_note'),
            'pickup_branch_id' => $merchant->branch_id,
            'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
            'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
            'item_type_charge' => $request->input('item_type_charge'),
            'service_type_charge' => $request->input('service_type_charge'),
            'status' => 1,
        ];

        $x = 'Update: ';
        $hasUpdated = false;

        $parcelOld = Parcel::find($parcel->id);
        $check = Parcel::where('id', $parcel->id)->update($data);

        $oldProduct_value = floatval($parcelOld->product_value);
        $newProduct_value = floatval($request->input('product_value'));

        $oldTotal_collect_amount = floatval($parcelOld->total_collect_amount);
        $newTotal_collect_amount = floatval($request->input('total_collect_amount'));


        if ($check) {
            if ($oldProduct_value != $newProduct_value) {
                $hasUpdated = true;
                $x .= 'Product value has been changed to ' . $oldProduct_value . ' to ' . $newProduct_value;
            }

            if ($oldTotal_collect_amount != $newTotal_collect_amount) {
                if ($hasUpdated) {
                    $x .= ' & amount to be collect ' . $oldTotal_collect_amount . ' to ' . $newTotal_collect_amount;
                } else {
                    $x .= 'Total collect amount has been changed to ' . $oldTotal_collect_amount . ' to ' . $newTotal_collect_amount;
                }
                $hasUpdated = true;
            }

            if ($hasUpdated) {
                createActivityLog($x, $parcelOld, $merchant->name);
            }

            // $data = [
            //     'parcel_id' => $parcel->id,
            //     'merchant_id' => $merchant->id,
            //     'pickup_branch_id' => $merchant->branch_id,
            //     'date' => date('Y-m-d'),
            //     'time' => date('H:i:s'),
            //     'status' => 1,
            // ];
            // ParcelLog::create($data);

            \DB::commit();

            $this->setMessage('Parcel Update Successfully', 'success');
            return redirect()->route('merchant.parcel.list');
        } else {
            $this->setMessage('Parcel Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
        // } catch (\Exception $e) {
        //     \DB::rollback();
        //     $this->setMessage('Database Error Found', 'danger');
        //     return redirect()->back()->withInput();
        // }
    }


    //     public function merchantBulkParcelImport()
    //     {
    //         $data = [];
    //         $data['main_menu'] = 'parcel';
    //         $data['child_menu'] = 'list';
    //         $data['page_title'] = 'Merchant Bulk Parcel Upload';
    //         $data['collapse'] = 'sidebar-collapse';
    //         return view('merchant.parcel.merchantBulkParcelImport', $data);
    //     }


    //     public function merchantBulkParcelImportStore(Request $request)
    //     {
    // //        dd($request->all());
    //         $file = $request->file('file')->store('import');

    //         $merchant_id = auth()->guard('merchant')->user()->id;
    //         $rider_id = $request->input('rider_id');
    //         $branch_id = auth()->guard('merchant')->user()->branch->id;

    //         \DB::beginTransaction();
    //         try {

    //             $import = new MerchantBulkParcelImport();
    //             $import->import($file);
    //             if ($import->failures()->isNotEmpty()) {
    //                 return back()->withFailures($import->failures());
    //             }

    //             \DB::commit();

    //             // $this->merchantDashboardCounterEvent($merchant_id);
    //             // $this->adminDashboardCounterEvent();
    //             $this->setMessage('Merchant Bulk Parcel Insert Successfully', 'success');
    //             return redirect()->route('merchant.parcel.list');
    //         } catch (\Exception $e) {
    //             \DB::rollback();
    // //            dd($e->getMessage());
    //             $this->setMessage($e->getMessage(), 'danger');
    //             return redirect()->back()->withInput();
    //         }
    //     }

    public function merchantBulkParcelImport()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'list';
        $data['page_title'] = 'Merchant Bulk Parcel Upload';
        $data['collapse'] = 'sidebar-collapse';
        return view('merchant.parcel.merchantBulkParcelImport', $data);
    }


    public function merchantBulkParcelImportStore(Request $request)
    {
        $file = $request->file('file')->store('import');
        $merchant_id = auth()->guard('merchant')->user()->id;
        $rider_id = $request->input('rider_id');
        $branch_id = auth()->guard('merchant')->user()->branch->id;

        \DB::beginTransaction();
        try {

            $import = new MerchantBulkParcelImport();
            // dd($import);

            $import->import($file);
            if ($import->failures()->isNotEmpty()) {
                return back()->withFailures($import->failures());
            }
            \DB::commit();

            // $this->merchantDashboardCounterEvent($merchant_id);
            // $this->adminDashboardCounterEvent();


            $this->setMessage('Check And Confirm Import', 'success');
            return redirect()->route('merchant.parcel.merchantBulkParcelImport.check');
            /*$this->setMessage('Merchant Bulk Parcel Insert Successfully', 'success');
            return redirect()->route('merchant.parcel.list');*/
        } catch (\Exception $e) {
            \DB::rollback();
            // dd($e->getMessage());
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function merchantBulkParcelImportEntry(Request $request)
    {

        $merchant_id = auth()->guard('merchant')->user()->id;
        $branch_id = auth()->guard('merchant')->user()->branch->id;

        \DB::beginTransaction();
        try {
            $rows = $request->input('parcel');
            //  dd($rows);
            if (count($rows)) {

                $currentDate = date("ymd");

                foreach ($rows as $row) {
                    $merchant_order_id = isset($row['merchant_order_id']) ? $row['merchant_order_id'] : null;
                    $customer_name = isset($row['customer_name']) ? $row['customer_name'] : null;
                    $customer_contact_number = isset($row['customer_contact_number']) ? $row['customer_contact_number'] : null;
                    $customer_address = isset($row['customer_address']) ? $row['customer_address'] : null;
                    $area_id = isset($row['area_id']) ? $row['area_id'] : null;
                    $product_details = isset($row['product_details']) ? $row['product_details'] : null;
                    $weight_package_id = isset($row['weight_package_id']) ? $row['weight_package_id'] : null;

                    // $service_type_id = isset($row['service_type_id']) ? $row['service_type_id'] : null;
                    // $item_type_id = isset($row['item_type_id']) ? $row['item_type_id'] : null;

                    $remark = isset($row['parcel_note']) ? $row['parcel_note'] : null;
                    $collection_amount = isset($row['total_collect_amount']) ? $row['total_collect_amount'] : null;
                    //                    dd($collection_amount);
                    $merchant = Merchant::where('id', $merchant_id)->first();

                    if ($merchant) {
                        if (
                            $merchant_id != null && $customer_name != null
                            && $customer_contact_number != null
                            && $customer_address != null && $area_id != null
                        ) {

                            $lastParcel = Parcel::orderBy('id', 'desc')->first();
                            $random_string = strtoupper(Controller::generateRandomString(3));
                            if (!empty($lastParcel)) {
                                $get_serial = substr($lastParcel->parcel_invoice, 9, 30);
                                $get_serial = strtoupper(base_convert(base_convert($get_serial, 36, 10) + 1, 10, 36));
                                $parcel_invoice = $currentDate . $random_string . str_pad($get_serial, 4, '0', STR_PAD_LEFT);
                            } else {
                                $parcel_invoice = $currentDate . $random_string . "001";
                            }

                            // Set District, Upazila, Area ID and Merchant Service Area Charge
                            $merchant_service_area_charge = 0;
                            $merchant_service_area_return_charge = 0;
                            $weight_package_charge = 0;
                            $item_type_charge = 0;
                            $cod_percent = $merchant->cod_charge;
                            $district_id = 0;
                            $upazila_id = 0;
                            //                            $area_id = 0;
                            $service_area_id = 0;

                            $area = Area::with('district')->where('id', $area_id)->first();

                            if ($area) {
                                $district_id = $area->district_id;
                                $upazila_id = $area->upazila_id;
                                $area_id = $area->id;
                                $service_area_id = $area->district->service_area_id;

                                $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                                    'service_area_id' => $service_area_id,
                                    'merchant_id' => $merchant->id,
                                ])->first();
                                // dd($merchantServiceAreaCodCharge);
                                if ($merchantServiceAreaCodCharge) {
                                    $cod_percent = $merchantServiceAreaCodCharge->cod_charge;
                                } else {
                                    $cod_percent = $area->district->service_area->cod_charge;
                                }
                                $merchant_service_area_charge = $area->district->service_area->default_charge;

                                $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                    'service_area_id' => $service_area_id,
                                    'merchant_id' => $merchant->id,
                                ])->first();

                                $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                                    'service_area_id' => $service_area_id,
                                    'merchant_id' => $merchant->id,
                                ])->first();

                                if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                                    $merchant_service_area_charge = $merchantServiceAreaCharge->charge;
                                }

                                if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                                    $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                                }
                            } else {
                                $merchant_service_area_charge = 60;
                            }
                            // Weight Package Charge
                            if ($weight_package_id) {
                                $weightPackage = WeightPackage::with([
                                    'service_area' => function ($query) use ($service_area_id) {
                                        $query->where('service_area_id', '=', $service_area_id);
                                    },
                                ])->where(['id' => $weight_package_id])->first();
                                if (!$weightPackage) {
                                    $weightPackage = WeightPackage::where('id', $weight_package_id)->first();
                                }
                                $weight_package_charge = $weightPackage->rate;
                                if (!empty($weightPackage->service_area)) {
                                    $weight_package_charge = $weightPackage->service_area->rate;
                                }
                            }
                            if (empty($weightPackage) || !$weight_package_id) {
                                $weightPackage = WeightPackage::with([
                                    'service_area' => function ($query) use ($service_area_id) {
                                        $query->where('service_area_id', '=', $service_area_id);
                                    },
                                ])->where(['status' => 1])->first();

                                $weight_package_charge = $weightPackage->rate;
                                if (!empty($weightPackage->service_area)) {
                                    $weight_package_charge = $weightPackage->service_area->rate;
                                }
                            }

                            // // Item Type Package Charge
                            // if ($item_type_id) {
                            //     $itemType = ItemType::with([
                            //         // 'service_area' => function ($query) use ($service_area_id) {
                            //         //     $query->where('service_area_id', '=', $service_area_id);
                            //         // },
                            //     ])->where(['id' => $item_type_id])->first();
                            //     if (!$itemType) {
                            //         $itemType = ItemType::where('id', $item_type_id)->first();

                            //         dd($itemType);
                            //     }
                            //     $item_type_charge = $itemType->rate;
                            //     if (!empty($itemType->service_area)) {
                            //         $item_type_charge = $itemType->service_area->rate;
                            //     }
                            // }
                            // if (empty($itemType) || !$item_type_id) {
                            //     $itemType = ItemType::with([
                            //         'service_area' => function ($query) use ($service_area_id) {
                            //             $query->where('service_area_id', '=', $service_area_id);
                            //         },
                            //     ])->where(['status' => 1])->first();

                            //     $item_type_charge = $itemType->rate;
                            //     if (!empty($itemType->service_area)) {
                            //         $item_type_charge = $itemType->service_area->rate;
                            //     }
                            // }



                            //         // Service Type Package Charge
                            //         if ($service_type_id) {
                            //             $serviceType = ServiceType::with([
                            //                 'service_area' => function ($query) use ($service_area_id) {
                            //                     $query->where('service_area_id', '=', $service_area_id);
                            //                 },
                            //             ])->where(['id' => $service_type_id])->first();
                            //             if (!$serviceType) {
                            //                 $serviceType = ServiceType::where('id', $service_type_id)->first();

                            //                 dd($serviceType);
                            //             }
                            //             $service_type_charge = $serviceType->rate;
                            //             if (!empty($serviceType->service_area)) {
                            //                 $service_type_charge = $serviceType->service_area->rate;
                            //             }
                            //         }
                            //         if (empty($serviceType) || !$service_type_id) {
                            //             $serviceType = ServiceType::with([
                            //                 'service_area' => function ($query) use ($service_area_id) {
                            //                     $query->where('service_area_id', '=', $service_area_id);
                            //                 },
                            //             ])->where(['status' => 1])->first();

                            //             $service_type_charge = $serviceType->rate;
                            //             if (!empty($serviceType->service_area)) {
                            //                 $service_type_charge = $serviceType->service_area->rate;
                            //             }
                            //         }




                            // Set Merchant Insert Parcel Calculation
                            $delivery_charge = $merchant_service_area_charge;
                            $cod_charge = 0;
                            $collection_amount = $collection_amount ?? 0;
                            if ($collection_amount != 0 && $cod_percent != 0) {
                                $cod_charge = ($collection_amount / 100) * $cod_percent;
                            }

                            $total_charge = $delivery_charge + $cod_charge + $weight_package_charge;
                            // $total_charge    = $delivery_charge + $cod_charge + $weight_package_charge;

                            // Insert Parcel
                            $data = [
                                'parcel_invoice' => $this->returnUniqueParcelInvoice(),
                                'merchant_id' => $merchant->id,
                                'pickup_address' => $merchant->address,

                                'date' => date('Y-m-d'),
                                'merchant_order_id' => $merchant_order_id,
                                'customer_name' => $customer_name,
                                'customer_address' => $customer_address,
                                'customer_contact_number' => $customer_contact_number,
                                'product_details' => $product_details,
                                'district_id' => $district_id,
                                'upazila_id' => $upazila_id,
                                'area_id' => $area_id,
                                'weight_package_id' => $weightPackage->id,

                                // 'item_type_id' => $itemType->id,
                                'service_type_id' => 1,
                                'service_type_charge' => 0,

                                'delivery_charge' => $delivery_charge,
                                'weight_package_charge' => $weight_package_charge,
                                'merchant_service_area_charge' => $merchant_service_area_charge,
                                'merchant_service_area_return_charge' => $merchant_service_area_return_charge,
                                'total_collect_amount' => $collection_amount ?? 0,
                                'cod_percent' => $cod_percent,
                                'cod_charge' => $cod_charge,
                                'total_charge' => $total_charge,
                                'parcel_note' => $remark,
                                'delivery_option_id' => 1,
                                'pickup_branch_id' => $merchant->branch_id,
                                'pickup_rider_date' => date('Y-m-d'),
                                'parcel_date' => date('Y-m-d'),
                                'status' => 1,
                            ];
                            //                                                        dd($data);

                            $parcel = Parcel::create($data);
                            //                            dump($parcel);

                            // Insert Parcel Log
                            $parcel_log = [
                                'parcel_id' => $parcel->id,
                                'merchant_id' => $merchant->id,
                                'pickup_branch_id' => $merchant->branch_id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 1,
                            ];
                            ParcelLog::create($parcel_log);
                        }
                    }
                }
            }
            //            die();
            \DB::commit();
            \session()->forget('import_parcel');
            $this->setMessage('Merchant Bulk Parcel Insert Successfully', 'success');
            return redirect()->route('merchant.parcel.list');
        } catch (\Exception $e) {
            \DB::rollback();
            //             dd($e->getMessage());
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function excelAllParcelList(Request $request)
    {
        $fileName = 'parcel_' . time() . '.xlsx';
        return Excel::download(new MerchantParcelExport($request), $fileName);
    }

    public function merchantBulkParcelImportCheck()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'list';
        $data['page_title'] = 'Merchant Bulk Parcel Upload';
        $data['collapse'] = 'sidebar-collapse';
        $import_parcels = \session()->has('import_parcel') ? \session()->get('import_parcel') : [];

        $data['areas'] = Area::all();
        $data['weight_packages'] = WeightPackage::all();
        // $data['item_types'] = ItemType::all();
        // $data['service_types'] = ServiceType::all();
        $data['import_parcels'] = $import_parcels;
        //dd($data['import_parcels']);
        if (count($import_parcels) > 0) {
            return view('merchant.parcel.merchantBulkParcelImportCheck', $data);
        }
        return redirect()->route('merchant.parcel.merchantBulkParcelImport');
    }

    public function merchantBulkParcelImportReset()
    {
        \session()->forget('import_parcel');
        $this->setMessage('Import Parcel reset successful!', 'success');
        return redirect()->route('merchant.parcel.merchantBulkParcelImport');
    }
}
