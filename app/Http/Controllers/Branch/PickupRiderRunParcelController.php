<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Imports\BranchMerchantBulkParcelImport;
use App\Imports\MerchantBulkParcelImport;
use App\Models\Area;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\MerchantServiceAreaCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\MerchantServiceAreaCodCharge;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\WeightPackage;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

// use App\Imports\BranchBulkParcelImport;

class PickupRiderRunParcelController extends Controller
{

    public function pickupRiderRunList()
    {
        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupRiderRunList';
        $data['page_title'] = 'Pickup Rider List';
        $data['collapse'] = 'sidebar-collapse';
        $data['riders'] = Rider::where([
            'status' => 1,
            'branch_id' => auth()->guard('branch')->user()->branch->id,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        return view('branch.parcel.pickupParcel.pickupRiderRunList', $data);
    }

    public function getPickupRiderRunList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $model = RiderRun::with([
            'rider' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
        ])
            ->whereRaw('branch_id = ? and run_type = 1', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();

        if ($request->has('run_status') && !is_null($request->get('run_status')) && $request->get('run_status') != 0) {
            $model->where('status', $request->get('run_status'));
        } elseif ($request->get('run_status') == '') {
            $model->whereIn('status', [1, 2]);
        } else {
            $model->whereIn('status', [1, 2, 3, 4]);
        }

        if ($request->has('rider_id') && !is_null($request->get('rider_id')) && $request->get('rider_id') != 0) {
            $model->where('rider_id', $request->get('rider_id'));
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('create_date_time', '>=', $request->get('from_date'));
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
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
                return ($data->complete_date_time) ? date('d-m-Y H:i:s', strtotime($data->complete_date_time)) : "";
            })
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                    case 1:
                        $status_name = "Run Create";
                        $class = "success";
                        break;
                    case 2:
                        $status_name = "Run Start";
                        $class = "success";
                        break;
                    case 3:
                        $status_name = "Run Cancel";
                        $class = "danger";
                        break;
                    case 4:
                        $status_name = "Run Complete";
                        $class = "success";
                        break;
                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }

                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.printPickupRiderRun', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Rider Run" target="_blank">
                <i class="fas fa-print"></i> </a>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Pickup Run Start">
                    <i class="far fa-play-circle"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Pickup Run Cancel">
                    <i class="far fa-window-close"></i> </button>';

                    $button .= '&nbsp; <a href="' . route('branch.parcel.editPickupRiderRun', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Run" >
                        <i class="fas fa-edit"></i> </a>';
                }

                if ($data->status == 2) {
                    $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                    <i class="fa fa-check"></i> </button> ';
                }

                $button .= '&nbsp; <a href="' . route('branch.parcel.printAllPickupRiderRunParcel', $data->id) . '" class="btn btn-info btn-sm"  title="All Parcel Print" target="_blank">
                <i class="fas fa-print"></i> </a>';

                return $button;
            })
            ->rawColumns(['action', 'status', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time'])
            ->make(true);
    }

    public function printPickupRiderRunList(Request $request)
    {
        //        return $request->all();
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $model = RiderRun::with([
            'rider' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
        ])
            ->whereRaw('branch_id = ? and run_type = 1', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $filter = [];

        if ($request->has('run_status') && !is_null($request->get('run_status')) && $request->get('run_status') != 0) {
            $model->where('status', $request->get('run_status'));
            $filter['run_status'] = $request->get('run_status');
        } elseif ($request->get('run_status') == '') {
            $model->whereIn('status', [1, 2]);
        } else {
            $model->whereIn('status', [1, 2, 3, 4]);
        }

        if ($request->has('rider_id') && !is_null($request->get('rider_id')) && $request->get('rider_id') != 0) {
            $model->where('rider_id', $request->get('rider_id'));
            $filter['rider_id'] = $request->get('rider_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('create_date_time', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('create_date_time', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $riderRuns = $model->get();
        return view('branch.parcel.pickupParcel.printPickupRiderRunList', compact('riderRuns', 'filter'));
    }

    public function printPickupRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.pickupParcel.printPickupRiderRun', compact('riderRun'));
    }

    public function printAllPickupRiderRunParcel(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        // dd($riderRun);
        return view('branch.parcel.pickupParcel.printAllPickupRiderRunParcel', compact('riderRun'));
    }


    //Apu ----!! Branch Bulk Parcel Import New System !!----

    public function merchantBulkParcelImport()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupRiderRunList';
        $data['page_title'] = 'Parcel Import List';
        $data['collapse'] = 'sidebar-collapse';
        //        $data['riders'] = Rider::with(['rider_runs' => function ($query) {
        //            $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc')->limit(1);
        //        },
        //        ])
        //            ->where([
        //                'status' => 1,
        //                'branch_id' => $branch_id,
        //            ])
        //            ->select('id', 'name', 'contact_number', 'address')
        //            ->get();
        $data['riders'] = Rider::where(['status' => 1, 'branch_id' => $branch_id,])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();
        //        dd($data['riders']);
        return view('branch.parcel.pickupParcel.merchantBulkParcelImport', $data);
    }

    public function merchantBulkParcelImportStore(Request $request)
    {
        $file = $request->file('file')->store('import');
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        $rider_id = $request->input('rider_id');
        $date = $request->input('date');
        $note = $request->input('note');
        DB::beginTransaction();
        try {
            $import = new BranchMerchantBulkParcelImport($rider_id, $date, $note);
            // dd($import);
            $import->import($file);
            if ($import->failures()->isNotEmpty()) {
                return back()->withFailures($import->failures());
            }
            \DB::commit();
            $this->setMessage('Check And Confirm Import', 'success');
            return redirect()->route('branch.parcel.merchantBulkParcelImport.check');
        } catch (\Exception $e) {
            \DB::rollback();
            // dd($e->getMessage());
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function merchantBulkParcelImportCheck()
    {
        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupRiderRunList';
        $data['page_title'] = 'Parcel Import List';
        $data['collapse'] = 'sidebar-collapse';
        $import_parcels = \session()->has('import_parcel') ? \session()->get('import_parcel') : [];
        $data['areas'] = Area::all();
        $data['weight_packages'] = WeightPackage::all();
        $data['import_parcels'] = $import_parcels;

        if (count($import_parcels) > 0) {
            return view('branch.parcel.pickupParcel.merchantBulkParcelImportCheck', $data);
        }

        return redirect()->route('branch.parcel.merchantBulkParcelImport');
    }

    public function merchantBulkParcelImportEntry(Request $request)
    {
        $parcel_count = count($request->parcel);
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        $import_parcels = \session()->has('import_parcel') ? \session()->get('import_parcel') : [];
        $rider_id = $import_parcels['rider_id'];
        $date = $import_parcels['date'];
        $note = $import_parcels['note'];
        \DB::beginTransaction();
        try {
            $rows = $request->input('parcel');

            if (count($rows)) {
                $currentDate = date("ymd");
                $data = [
                    'run_invoice' => $this->returnUniqueRiderRunInvoice(),
                    'rider_id' => $rider_id,
                    'branch_id' => $branch_id,
                    'branch_user_id' => $branch_user_id,
                    'create_date_time' => $date . ' ' . date('H:i:s'),
                    'total_run_parcel' => 0,
                    'note' => $note,
                    'run_type' => 1,
                    'status' => 2,
                ];
                $riderRun = RiderRun::create($data);
                $rider_run_id = $riderRun->id;
                foreach ($rows as $row) {

                    $merchant_order_id = isset($row['merchant_order_id']) ? $row['merchant_order_id'] : null;
                    $rider_id = isset($row['rider_id']) ? $row['rider_id'] : null;
                    $rider_run_id = $rider_run_id;
                    $customer_name = isset($row['customer_name']) ? $row['customer_name'] : null;
                    $customer_contact_number = isset($row['customer_contact_number']) ? $row['customer_contact_number'] : null;
                    $customer_contact_number2 = isset($row['customer_contact_number2']) ? $row['customer_contact_number2'] : null;
                    $exchange = isset($row['exchange']) ? $row['exchange'] : 'no';
                    $customer_address = isset($row['customer_address']) ? $row['customer_address'] : null;
                    $area_id = isset($row['area_id']) ? $row['area_id'] : null;
                    $product_details = isset($row['product_details']) ? $row['product_details'] : null;
                    $weight_package_id = isset($row['weight_package_id']) ? $row['weight_package_id'] : null;
                    $remark = isset($row['parcel_note']) ? $row['parcel_note'] : null;
                    $collection_amount = isset($row['total_collect_amount']) ? $row['total_collect_amount'] : null;
                    $exchange = isset($row['exchange']) ? $row['exchange'] : 'no';

                    $m_id = isset($row['m_id']) ? $row['m_id'] : null;
                    $merchant = Merchant::where('m_id', $m_id)->first();

                    if ($merchant) {

                        if (
                            $merchant->id != null && $customer_name != null
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
                            $cod_percent = $merchant->cod_charge ?? 0;
                            $district_id = 0;
                            $upazila_id = 0;
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

                            // Set Merchant Insert Parcel Calculation
                            $delivery_charge = $merchant_service_area_charge;
                            $cod_charge = 0;
                            $collection_amount = $collection_amount ?? 0;

                            if ($collection_amount != 0 && $cod_percent != 0) {
                                $cod_charge = ($collection_amount / 100) * $cod_percent;
                            }

                            $total_charge = $delivery_charge + $cod_charge + $weight_package_charge;

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
                                'customer_contact_number2' => $customer_contact_number2,
                                'exchange' => $exchange,
                                'product_details' => $product_details,
                                'district_id' => $district_id,
                                'upazila_id' => $upazila_id,
                                'area_id' => $area_id,
                                'weight_package_id' => $weightPackage->id,
                                'service_type_id' => 1,
                                'service_type_charge' => 0,

                                'delivery_charge' => $delivery_charge,
                                'weight_package_charge' => $weight_package_charge,
                                'merchant_service_area_charge' => $merchant_service_area_charge,
                                'merchant_service_area_return_charge' => $merchant_service_area_return_charge,
                                'total_collect_amount' => $collection_amount ?? 0,
                                'cod_percent' => $cod_percent,
                                'cod_charge' => $cod_charge,
                                'total_charge' => ceil($total_charge),
                                'parcel_note' => $remark,
                                'delivery_option_id' => 1,
                                'pickup_branch_id' => $branch_id,
                                'pickup_branch_date' => date('Y-m-d'),
                                'pickup_branch_user_id' => $branch_user_id,
                                'parcel_date' => date('Y-m-d'),
                                'status' => 10,
                                'pickup_rider_date' => date('Y-m-d'),
                                'pickup_rider_id' => $rider_id,
                                'exchange' => $exchange,
                            ];

                            $parcel = Parcel::create($data);

                            // Insert Parcel Log
                            $parcel_log = [
                                'parcel_id' => $parcel->id,
                                'pickup_rider_id' => $rider_id,
                                'pickup_branch_id' => $branch_id,
                                'pickup_branch_user_id' => $branch_user_id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 10,
                                'delivery_type' => $parcel->delivery_type,
                            ];
                            ParcelLog::create($parcel_log);

                            // Insert Rider Details
                            $riderRunDetail = RiderRunDetail::create([
                                'rider_run_id' => $rider_run_id,
                                'parcel_id' => $parcel->id,
                                'complete_date_time' => date('Y-m-d H:i:s'),
                                'status' => 7,
                            ]);
                        }
                    }

                    $riderRun = RiderRun::where('id', $rider_run_id)->update([
                        'total_run_parcel' => $parcel_count,
                        'total_run_complete_parcel' => $parcel_count,
                    ]);
                }
            }

            \DB::commit();
            \session()->forget('import_parcel');
            $this->setMessage('Branch Bulk Parcel Insert Successfully', 'success');
            return redirect()->route('branch.parcel.pickupRiderRunList');
        } catch (\Exception $e) {
            \DB::rollback();
            //             dd($e->getMessage());
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function merchantBulkParcelImportReset()
    {
        \session()->forget('import_parcel');
        $this->setMessage('Import Parcel reset successful!', 'success');
        return redirect()->route('branch.parcel.merchantBulkParcelImport');
    }

    //Apu ----!! Branch Bulk Parcel Import New System  End!!----


    public function startPickupRiderRun(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                \DB::beginTransaction();
                try {
                    $check = RiderRun::where('id', $request->rider_run_id)->update([
                        'start_date_time' => date('Y-m-d H:i:s'),
                        'status' => 2,
                    ]);

                    if ($check) {
                        $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                        foreach ($riderRunDetails as $riderRunDetail) {
                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            $parcel->update([
                                'status' => 6,
                                'parcel_date' => date('Y-m-d'),
                                'pickup_rider_date' => date('Y-m-d'),
                            ]);

                            ParcelLog::create([
                                'parcel_id' => $riderRunDetail->parcel_id,
                                'pickup_branch_id' => auth()->guard('branch')->user()->branch->id,
                                'pickup_branch_user_id' => auth()->guard('branch')->user()->id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 6,
                                'delivery_type' => $parcel->delivery_type,
                            ]);

                            RiderRunDetail::where('id', $riderRunDetail->id)->update([
                                'status' => 2,
                            ]);

                            \DB::commit();

                            /** parcel notification */
                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();

                            // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                            // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);
                        }

                        // $this->adminDashboardCounterEvent();

                        $response = ['success' => 'Pickup Rider Run Start Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }

    public function cancelPickupRiderRun(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                \DB::beginTransaction();
                try {
                    $check = RiderRun::where('id', $request->rider_run_id)->update([
                        'cancel_date_time' => date('Y-m-d H:i:s'),
                        'status' => 3,
                    ]);

                    if ($check) {
                        $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                        foreach ($riderRunDetails as $riderRunDetail) {
                            Parcel::where('id', $riderRunDetail->parcel_id)->update([
                                'status' => 7,
                                'parcel_date' => date('Y-m-d'),
                            ]);
                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            ParcelLog::create([
                                'parcel_id' => $riderRunDetail->parcel_id,
                                'pickup_branch_id' => auth()->guard('branch')->user()->branch->id,
                                'pickup_branch_user_id' => auth()->guard('branch')->user()->id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 7,
                                'delivery_type' => $parcel->delivery_type,
                            ]);
                            RiderRunDetail::where('id', $riderRunDetail->id)->update([
                                'status' => 3,
                            ]);

                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                            // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);
                        }

                        \DB::commit();
                        // $this->adminDashboardCounterEvent();

                        $response = ['success' => 'Pickup Rider Run Cancel Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);
    }

    public function pickupRiderRunGenerate()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupRiderRunGenerate';
        $data['page_title'] = 'Pickup Rider List';
        $data['collapse'] = 'sidebar-collapse';

        $data['riders'] = Rider::with([
            'rider_runs' => function ($query) {
                $query->select('id', 'status', 'rider_id');
                $query->orderBy('id', 'desc');
            },
        ])
            ->where([
                'status' => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        //        $data['merchants']     = Merchant::where([

        //                    'status'    => 1,

        //                    'branch_id' => $branch_id,

        //                ])

        //                ->select('id', 'name', 'company_name','contact_number', 'address')
        //                ->get();

        $data['merchants'] = Parcel::join('merchants as m', 'm.id', '=', 'parcels.merchant_id')->where([
            'pickup_branch_id' => $branch_id,
        ])
            ->whereIn('parcels.status', [1, 4, 7, 9])
            ->select('parcels.merchant_id', 'm.company_name')
            ->distinct()
            ->orderBy('m.company_name', 'ASC')
            ->get();

        $data['parcels'] = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
        ])
            ->whereRaw("pickup_branch_id = ? AND status in (1, 4, 7, 9)", [$branch_id])
            ->select(
                'id',
                'parcel_invoice',
                'merchant_order_id',
                'pickup_address',
                'customer_name',
                'customer_contact_number',
                'total_collect_amount',
                'merchant_id'
            )
            ->orderBy('id', 'DESC')
            ->get();

        return view('branch.parcel.pickupParcel.pickupRiderRunGenerate', $data);
    }

    public function returnPickupRiderRunParcel(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');
        $merchant_id = $request->input('merchant_id');

        if (!empty($parcel_invoice) || !empty($merchant_order_id) || !empty($merchant_id)) {

            $data['parcels'] = Parcel::with([
                'merchant' => function ($query) {
                    $query->select('id', 'name', 'company_name', 'contact_number', 'address');
                },
            ])
                ->where(function ($query) use ($branch_id, $parcel_invoice, $merchant_order_id, $merchant_id) {

                    $query->whereRaw("pickup_branch_id = ? AND status in (1, 4, 7, 9)", [$branch_id]);

                    if (!empty($parcel_invoice)) {
                        $query->where('parcel_invoice', 'LIKE', "%{$parcel_invoice}%");
                    } elseif (!empty($merchant_order_id)) {
                        // $query->where('merchant_order_id', 'LIKE', "%{$merchant_order_id}%");
                        $query->where(function ($q) use ($merchant_order_id) {
                            $q->where('merchant_order_id', 'LIKE', "%{$merchant_order_id}%")
                                ->orWhere('customer_contact_number', 'LIKE', "%{$merchant_order_id}%");
                        });
                    } elseif (!empty($merchant_id)) {
                        $query->where([
                            'merchant_id' => $merchant_id,
                        ]);
                    }
                })
                ->select(
                    'id',
                    'parcel_invoice',
                    'merchant_order_id',
                    'pickup_address',
                    'customer_name',
                    'customer_contact_number',
                    'total_collect_amount',
                    'merchant_id'
                )
                ->get();
            // dd($data['parcels']);

        } else {
            $data['parcels'] = Parcel::with([
                'merchant' => function ($query) {
                    $query->select('id', 'name', 'company_name', 'contact_number', 'address');
                },
            ])
                ->where(function ($query) use ($branch_id, $parcel_invoice, $merchant_order_id, $merchant_id) {
                    $query->whereIn('status', [1, 4, 7, 9]);

                    $query->where([
                        'pickup_branch_id' => $branch_id,
                    ]);
                })
                ->select(
                    'id',
                    'parcel_invoice',
                    'merchant_order_id',
                    'pickup_address',
                    'customer_name',
                    'customer_contact_number',
                    'total_collect_amount',
                    'merchant_id'
                )
                ->get();
        }

        return view('branch.parcel.pickupParcel.pickupRiderRunParcel', $data);
    }

    public function pickupRiderRunParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $parcel_invoice = $request->input('parcel_invoice');
        $parcels = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
        ])
            ->where(function ($query) use ($request) {
                $query->whereIn('id', $request->parcel_invoices)
                    ->orWhereIn('parcel_invoice', $request->parcel_invoices);
            })
            ->where([
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [1, 4, 7, 9])
            ->get();

        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;

                if (count($cart) > 0) {

                    foreach ($cart as $item) {

                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id' => $cart_id,
                        'name' => $parcel->merchant->name,
                        'price' => 1,
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'parcel_invoice' => $parcel->parcel_invoice,
                            'customer_name' => $parcel->customer_name,
                            'customer_address' => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name' => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option' => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => $getTotal,
            'error' => $error,
        ];
        return view('branch.parcel.pickupParcel.pickupRiderRunParcelCart', $data);
    }

    public function pickupRiderRunEditParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $parcel_invoice = $request->input('parcel_invoice');
        $parcels = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->where([
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [1, 4, 5, 7, 9])
            ->get();

        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;

                if (count($cart) > 0) {

                    foreach ($cart as $item) {

                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id' => $cart_id,
                        'name' => $parcel->merchant->name,
                        'price' => 1,
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'parcel_invoice' => $parcel->parcel_invoice,
                            'customer_name' => $parcel->customer_name,
                            'customer_address' => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name' => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option' => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => $getTotal,
            'error' => $error,
        ];
        return view('branch.parcel.pickupParcel.pickupRiderRunParcelCart', $data);
    }

    public function pickupRiderRunParcelDeleteCart(Request $request)
    {

        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($branch_id)->getTotal(),
            'error' => "",
        ];
        return view('branch.parcel.pickupParcel.pickupRiderRunParcelCart', $data);
    }

    public function confirmPickupRiderRunGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {
            $data = [
                'run_invoice' => $this->returnUniqueRiderRunInvoice(),
                'rider_id' => $request->input('rider_id'),
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_run_parcel' => $request->input('total_run_parcel'),
                'note' => $request->input('note'),
                'run_type' => 1,
            ];
            $riderRun = RiderRun::create($data);

            if ($riderRun) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');
                $merchant_numbers = [];

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    RiderRunDetail::create([
                        'rider_run_id' => $riderRun->id,
                        'parcel_id' => $parcel_id,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'status' => 5,
                        'parcel_date' => $request->input('date'),
                        'pickup_rider_id' => $request->input('rider_id'),
                        'pickup_branch_id' => $branch_id,
                        'pickup_branch_user_id' => $branch_user_id,
                    ]);
                    $parcel = Parcel::where('id', $parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id' => $parcel_id,
                        'pickup_rider_id' => $request->input('rider_id'),
                        'pickup_branch_id' => $branch_id,
                        'pickup_branch_user_id' => $branch_user_id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 5,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    /** parcel notification */
                    $parcel = Parcel::where('id', $parcel_id)->first();
                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    $merchant_numbers[] = $merchant_user->contact_number;

                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);
                }

                \DB::commit();

                // send sms to merchant
                $merchant_numbers = array_unique($merchant_numbers);
                $rider = Rider::where('id', $request->input('rider_id'))->first();

                foreach ($merchant_numbers ?? [] as $number) {
                    $this->send_sms($number, "Pick Rider Assigned\nRider: $rider->name\nMobile: $rider->contact_number\nPlease make sure your parcel is ready.\n-Parceldex Courier");
                }

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Pickup Rider Run Insert Successfully', 'success');
                return redirect()->route('branch.parcel.pickupRiderRunList');
            } else {
                $this->setMessage('Pickup Rider Run Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();

            //            $this->setMessage('Database Error', 'danger');
            //            return redirect()->back()->withInput();

            return $e->getMessage();
        }
    }

    public function editPickupRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');

        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->clear();

        foreach ($riderRun->rider_run_details as $rider_run_detail) {
            $cart_id = $rider_run_detail->parcel->id;

            \Cart::session($branch_id)->add([
                'id' => $cart_id,
                'name' => $rider_run_detail->parcel->merchant->name,
                'price' => 1,
                'quantity' => 1,
                'target' => 'subtotal',
                'attributes' => [
                    'parcel_invoice' => $rider_run_detail->parcel->parcel_invoice,
                    'customer_name' => $rider_run_detail->parcel->customer_name,
                    'customer_address' => $rider_run_detail->parcel->customer_address,
                    'customer_contact_number' => $rider_run_detail->parcel->customer_contact_number,
                    'merchant_name' => $rider_run_detail->parcel->merchant->name,
                    'merchant_contact_number' => $rider_run_detail->parcel->merchant->contact_number,
                    'option' => [],
                ],
                'associatedModel' => $rider_run_detail->parcel,
            ]);
        }

        $data = [];
        $cart = \Cart::session($branch_id)->getContent();
        $data['cart'] = $cart->sortBy('id');
        $data['totalItem'] = \Cart::session($branch_id)->getTotalQuantity();
        $data['getTotal'] = \Cart::session($branch_id)->getTotal();

        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupRiderRunGenerate';
        $data['page_title'] = 'Pickup Rider List';
        $data['collapse'] = 'sidebar-collapse';
        $data['riderRun'] = $riderRun;

        $data['riders'] = Rider::with([
            'rider_runs' => function ($query) {
                $query->select('id', 'status', 'rider_id')
                    ->orderBy('id', 'desc');
            },
        ])
            ->where([
                'status' => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        //        $data['merchants']     = Merchant::where([

        //                    'status'    => 1,

        //                    'branch_id' => $branch_id,

        //                ])

        //                ->select('id', 'name', 'company_name','contact_number', 'address')
        //                ->get();

        $data['merchants'] = Parcel::where([
            'pickup_branch_id' => $branch_id,
        ])
            ->whereIn('status', [1, 4, 7, 9])
            ->select('merchant_id')
            ->distinct()
            ->orderBy('merchant_id', 'ASC')->get();

        $data['parcels'] = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number');
            },
        ])
            ->where([
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [1, 7, 9])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
            ->get();

        return view('branch.parcel.pickupParcel.pickupRiderRunGenerateEdit', $data);
    }

    public function confirmPickupRiderRunGenerateEdit(Request $request, RiderRun $riderRun)
    {

        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {
            $data = [
                'rider_id' => $request->input('rider_id'),
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_run_parcel' => $request->input('total_run_parcel'),
                'note' => $request->input('note'),
                'run_type' => 1,
                'status' => 1,
            ];
            $check = RiderRun::where('id', $riderRun->id)->update($data) ? true : false;

            if ($check) {
                $riderRunDetails = RiderRunDetail::where('rider_run_id', $riderRun->id)->get();

                foreach ($riderRunDetails as $riderRunDetail) {
                    Parcel::where('id', $riderRunDetail->parcel_id)->update([
                        'status' => 1,
                    ]);
                }

                RiderRunDetail::where('rider_run_id', $riderRun->id)->delete();

                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {

                    $parcel_id = $item->id;
                    RiderRunDetail::create([
                        'rider_run_id' => $riderRun->id,
                        'parcel_id' => $parcel_id,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'status' => 5,
                        'parcel_date' => $request->input('date'),
                        'pickup_rider_id' => $request->input('rider_id'),
                        'pickup_branch_id' => $branch_id,
                        'pickup_branch_user_id' => $branch_user_id,
                    ]);
                    $parcel = Parcel::where('id', $parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id' => $parcel_id,
                        'pickup_rider_id' => $request->input('rider_id'),
                        'pickup_branch_id' => $branch_id,
                        'pickup_branch_user_id' => $branch_user_id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 5,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    /** parcel notification */
                    $parcel = Parcel::where('id', $parcel_id)->first();
                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();

                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Rider Run Update Successfully', 'success');
                return redirect()->route('branch.parcel.pickupRiderRunList');
            } else {
                $this->setMessage('Rider Run Update Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function viewPickupRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.pickupParcel.viewPickupRiderRun', compact('riderRun'));
    }

    public function pickupRiderRunReconciliation(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.pickupParcel.pickupRiderRunReconciliation', compact('riderRun'));
    }

    public function confirmPickupRiderRunReconciliation(Request $request, RiderRun $riderRun)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'run_note' => 'sometimes',
                'total_run_complete_parcel' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $check = RiderRun::where([
                        'id' => $riderRun->id,
                    ])
                        ->update([
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'total_run_complete_parcel' => $request->total_run_complete_parcel,
                            'note' => $request->run_note,
                            'status' => 4,
                        ]);

                    if ($check) {
                        $rider_run_details_id = $request->rider_run_details_id;
                        $rider_run_status = $request->rider_run_status;
                        $complete_note = $request->complete_note;
                        $parcel_id = $request->parcel_id;
                        $count = count($rider_run_details_id);

                        for ($i = 0; $i < $count; $i++) {
                            RiderRunDetail::where('id', $rider_run_details_id[$i])->update([
                                'complete_note' => $complete_note[$i],
                                'complete_date_time' => date('Y-m-d H:i:s'),
                                'status' => $rider_run_status[$i],
                            ]);
                            $status = 9;

                            if ($rider_run_status[$i] == 7) {
                                $status = 11;
                            }

                            if ($rider_run_status[$i] == 6) {
                                $status = 4;
                            }

                            Parcel::where('id', $parcel_id[$i])->update([
                                'status' => $status,
                                'parcel_date' => date('Y-m-d'),
                                'pickup_branch_date' => date('Y-m-d'),
                            ]);
                            $parcel = Parcel::where('id', $parcel_id[$i])->first();
                            ParcelLog::create([
                                'parcel_id' => $parcel_id[$i],
                                'pickup_branch_id' => auth()->guard('branch')->user()->branch->id,
                                'pickup_branch_user_id' => auth()->guard('branch')->user()->id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => $status,
                                'delivery_type' => $parcel->delivery_type,
                            ]);

                            $parcel = Parcel::with("merchant:id,name,company_name,contact_number")->where('id', $parcel_id[$i])->first();
                            if ($rider_run_status[$i] == 7) {
                                $message = "Dear " . $parcel->merchant->name . ". ";
                                $message .= "Your  Parcel ID No {$parcel->parcel_invoice} is successfully Picked up.";

                                // $this->send_sms($parcel->merchant->contact_number, $message);
                                // if ($rider_run_status[$i] == 7) {
                                //     $c_message = "Dear " . $parcel->customer_name . ", we received a parcel from " . $parcel->merchant->company_name . " and will deliver soon. Track here: " . route('frontend.orderTracking') . "?trackingBox=" . $parcel->tracking_id. "   \n- Foring";
                                //     // $this->send_sms($parcel->customer_contact_number, $c_message);
                                // }

                                $message = "Dear " . ucwords($parcel->customer_name) . ", Track your parcel from " . ucwords($parcel->merchant->company_name) . ". " . route('frontend.orderTracking') . "?trackingBox=" . $parcel->tracking_id . "   \n- Parceldex Ltd";

                                //                             Parcel from SAREEZ BY SAKYLA  (Company_Name) has picked up.
                                // COD: 1,00,000 Tk. (total_collect_amount)
                                // Track: https://parceldex.com/orderTracking?trackingBox=231220YD80083
                                // Parceldex Courier

                                $message = "Parcel from " . ucwords($parcel->merchant->company_name) . " has picked up. \nCOD- " . number_format($parcel->total_collect_amount) . " Tk.\nTrack- " . route('frontend.orderTracking') . "?trackingBox=" . $parcel->tracking_id . "\nParceldex Courier";

                                $this->send_sms($parcel->customer_contact_number, $message);
                            }

                            // $parcel = Parcel::where('id', $parcel_id)->first();

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                            // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);
                        }

                        \DB::commit();
                        // $this->adminDashboardCounterEvent();

                        $response = ['success' => 'Pickup Rider Run Reconciliation Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => $e->getMessage()];
                }
            }
        }

        return response()->json($response);
    }
}
