<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminParcelExport;
use App\Http\Controllers\Controller;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Rider;
use App\Models\Branch;
use App\Models\Area;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Upazila;
use App\Models\WeightPackage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;

class ParcelController extends Controller
{
    public function printParcelMultiple(Request $request)
    {
        //        dd($request->all());
        $parcel_ids = $request->input('parcel_ids');
        $parcels = Parcel::whereIn("id", $parcel_ids)->with('merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider')->get();
        //        dd($parcels);

        return view('merchant.parcel.printParcelMultiple', compact('parcels'));
    }

    public function list()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('admin.parcel.parcelList', $data);
    }

    public function getParcelList(Request $request)
    {
        $model = Parcel::with(['district', 'upazila', 'area'])
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                $parcelStatus = returnParcelStatusNameForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];

                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" >
                <i class="fa fa-eye"></i> </button>';

                if (auth()->guard('admin')->user()->type == 1) {
                    $button .= '&nbsp; <a href="' . route('admin.parcel.editParcel', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                }

                if (auth()->guard('admin')->user()->type == 1 && $data->status < 11) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '" >
                <i class="fa fa-trash"></i> </button>';
                }

                return $button;
            })
            ->editColumn('service_type', function ($data) {
                return optional($data->service_type)->title;
            })
            ->editColumn('item_type', function ($data) {
                return optional($data->item_type)->title;
            })
            ->rawColumns(['status', 'action', 'service_type', 'item_type', 'image'])
            ->make(true);
    }


    public function allParcelList()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'allParcelList';
        $data['page_title'] = 'All Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        $data['merchants'] = Merchant::with('branch')
            ->orderBy('company_name', 'ASC')
            ->get();
        $data['branches'] = Branch::orderBy('name', 'ASC')
            ->get();

        return view('admin.parcel.allParcelList', $data);
    }

    public function getAllParcelList(Request $request)
    {
        //        dd($request->input('parcel_status'));
        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address',
            'parcel_logs'
            /*'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },*/
        ])
            ->where('status', '!=', 0)
            ->whereRaw('pickup_branch_id IS NOT NULL')
            ->select();

        $parcel_status = $request->parcel_status;

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
        }
        if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {
            $model->whereRaw('delivery_branch_id = ' . $request->input('delivery_branch_id'));
        }
        if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {

            $parcel_invoice = $request->get('parcel_invoice');
            $model->where('parcel_invoice', 'like', "{$parcel_invoice}");
            $model->orWhere('merchant_order_id', 'like', "{$parcel_invoice}");
            $model->orWhere('customer_contact_number', 'like', "%{$parcel_invoice}%");
        }

        /*if ($request->has('merchant_order_id') && !is_null($request->get('merchant_order_id')) && $request->get('merchant_order_id') != 0) {
            $model->where('merchant_order_id', $request->get('merchant_order_id'));
        }

        if ($request->has('customer_contact_number') && !is_null($request->get('customer_contact_number')) && $request->get('customer_contact_number') != 0) {
            $model->where('customer_contact_number','like', '%'.$request->get('customer_contact_number').'%' );
        }*/

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                if ($parcel_status == 1) {
                    $model->whereDate('delivery_date', '>=', $request->get('from_date'));
                } elseif ($parcel_status == 9) {
                    $model->whereDate('pickup_branch_date', '>=', $request->get('from_date'));
                } else {
                    $model->whereDate('date', '>=', $request->get('from_date'));
                }
            } else {
                $model->whereDate('date', '>=', $request->get('from_date'));
            }
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                if ($parcel_status == 1) {
                    $model->whereDate('delivery_date', '<=', $request->get('to_date'));
                } elseif ($parcel_status == 9) {
                    $model->whereDate('pickup_branch_date', '<=', $request->get('to_date'));
                } else {
                    $model->whereDate('date', '<=', $request->get('to_date'));
                }
            } else {
                $model->whereDate('date', '<=', $request->get('to_date'));
            }
        }
        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
            if ($parcel_status == 1) {
                // $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                //                $model->whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)');
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                // $model->whereRaw('status > 11 and delivery_type in (?)', [3]);
                //                $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');
                $model->whereRaw('status >= 11 and status <= 25 and (delivery_type IS NULL OR (status in (23,25) and delivery_type = 3))');
                //  $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');

            } elseif ($parcel_status == 3) {
                // $query->whereRaw('status = 3');
                $model->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4]);
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                // $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            } elseif ($parcel_status == 12) {
                $model->whereRaw('status >= 25 and delivery_type in(3)');
            }
        }

        //        dd($model->toSql());
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
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2 || $data->delivery_type == 4) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    }
                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    // $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                    $date_time = $data->date;
                }
                $parcelStatus = returnParcelStatusForAdmin($data->status, $data->delivery_type, $data->payment_type, $data);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class="  text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong></strong>' . $date_time . '</p>';
            })
            ->editColumn('payment_status', function ($data) {
                // $return = "";
                // $parcelStatus = returnPaymentStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                // $status_name = $parcelStatus['status_name'];
                // $class = $parcelStatus['class'];
                // $return .= '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <br>';


                // $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                // $status_name = $parcelStatus['status_name'];
                // $class = $parcelStatus['class'];
                // $return .= '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
                $date  = $data?->merchantDeliveryPayment?->created_at->format('d-m-Y h:i A');
                $x = '';
                $payment_invoice = $data?->merchantDeliveryPayment?->payment_invoice;
                if ($date && $payment_invoice) {
                    $x = $date . '<br>' . $payment_invoice;
                }


                $parcelStatus = returnPaymentStatusForAdmin($data->status, $data->delivery_type, $data->payment_type, $data);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                $time = $parcelStatus['time'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span><br>' . $time;
            })

            ->editColumn('return_status', function ($data) {
                $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type, $data);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                $time        = $parcelStatus['time'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span><br>' . $time;
            })
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

                    // if (auth()->guard('admin')->user()->type == 1) {
                    $button .= '&nbsp; <a href="' . route('admin.parcel.editParcel', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                    // }
                    // if ($data->status < 10) {
                    //     $button .= '&nbsp; <button class="btn btn-danger pickup-cancel btn-sm" parcel_id="' . $data->id . '" title="Parcel Cancel">
                    //                     <i class="far fa-window-close"></i>
                    //                 </button>';

                    //     $button .= '&nbsp;  <a class="btn btn-secondary btn-sm" href="' . route('admin.parcel.editParcel', $data->id) . '"   title="Parcel Edit">
                    //                     <i class="fas fa-pencil-alt"></i>
                    //             </a>';
                    // }
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

                // $amount = '<p><strong>Amount to be Collect: ৳ </strong>' . $data->total_collect_amount . '</p>';
                // $amount .= '<p><strong>Collected: ৳ </strong>' . $data->customer_collect_amount . '</p>';
                // $amount .= '<p><strong>Delivery Charge:  ৳ </strong>' . $data->delivery_charge . '</p>';
                // // $amount .= '<p><strong>Delivery Charge: </strong>'.$data->total_charge.'</p>';
                // // $amount .= '<p><strong>COD Charge: </strong>'.$data->cod_charge.'</p>';
                // return $amount;
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

    public function printAllParcelList(Request $request)
    {

        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address',
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },
        ])
            // ->WhereBetween('date', [$request->from_date, $request->to_date])
            // ->whereRaw('pickup_branch_id IS NOT NULL')
            ->orderBy('id', 'desc')
            ->select();

        $filter = [];

        $parcel_status = $request->parcel_status;
        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
            if ($parcel_status == 1) {
                // $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                // $model->whereRaw('status > 11 and delivery_type in (?)', [3]);
                $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');
            } elseif ($parcel_status == 3) {
                // $query->whereRaw('status = 3');
                $model->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4]);
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                // $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            } elseif ($parcel_status == 12) {
                $model->whereRaw('status >= 25 and delivery_type in(3)');
            }
            $filter['parcel_status'] = $parcel_status;
        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
            $filter['merchant_id'] = $request->get('merchant_id');
        }
        if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {
            $model->where('delivery_branch_id', $request->get('delivery_branch_id'));
            $filter['delivery_branch_id'] = $request->get('delivery_branch_id');
        }

        if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {
            $model->where('parcel_invoice', $request->get('parcel_invoice'));
            $filter['parcel_invoice'] = $request->get('parcel_invoice');
        }

        if ($request->has('merchant_order_id') && !is_null($request->get('merchant_order_id')) && $request->get('merchant_order_id') != 0) {
            $model->where('merchant_order_id', $request->get('merchant_order_id'));
            $filter['merchant_order_id'] = $request->get('merchant_order_id');
        }

        if ($request->has('customer_contact_number') && !is_null($request->get('customer_contact_number')) && $request->get('customer_contact_number') != 0) {
            $model->where('customer_contact_number', $request->get('customer_contact_number'));
            $filter['customer_contact_number'] = $request->get('customer_contact_number');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }







        $parcels = $model->get();

        return view('admin.parcel.printParcelList', compact('parcels', 'filter'));
    }

    public function excelAllParcelList(Request $request)
    {
        $fileName = 'parcel_' . time() . '.xlsx';
        return Excel::download(new AdminParcelExport($request), $fileName);
    }


    public function viewParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_branch_user', 'pickup_rider', 'delivery_branch', 'delivery_branch_user', 'delivery_rider', 'admin', 'merchant')
            ->where('parcel_id', $parcel->id)->orderBy('id', 'DESC')->get();

        $parcelBranchPaymentDeltails = ParcelDeliveryPaymentDetail::where('parcel_id', $parcel->id)
            ->orderBy('id', 'DESC')
            ->with('parcel_delivery_payment')
            ->get();
        $parcelMerchantPaymentDeltails = ParcelMerchantDeliveryPaymentDetail::where('parcel_id', $parcel->id)
            ->orderBy('id', 'DESC')
            ->with('parcel_merchant_delivery_payment')
            ->get();

        return view('admin.parcel.viewParcel', compact('parcel', 'parcelLogs', 'parcelBranchPaymentDeltails', 'parcelMerchantPaymentDeltails'));
    }

    public function editParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
            ->where('parcel_id', $parcel->id)->get();

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Update Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['parcelLogs'] = $parcelLogs;
        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        if (!empty($parcel->pickup_branch_id)) {
            $data['pickupRiders'] = Rider::where([
                ['status', '=', 1],
                ['branch_id', '=', $parcel->pickup_branch_id],
            ])->get();
        } else {
            $data['pickupRiders'] = [];
        }

        if (!empty($parcel->delivery_branch_id)) {
            $data['deliveryRiders'] = Rider::where([
                ['status', '=', 1],
                ['branch_id', '=', $parcel->delivery_branch_id],
            ])->get();
        } else {
            $data['deliveryRiders'] = [];
        }


        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        // $data['upazilas']   = Upazila::where([
        //     ['district_id', '=', $parcel->district->id],
        //     ['status', '=', 1],
        // ])->get();

        $data['areas'] = Area::where([
            ['district_id', '=', $parcel->district_id],
            ['status', '=', 1],
        ])->get();

        $service_area_id = $parcel->district->service_area_id;

        $data['weightPackages'] = WeightPackage::with([
            'service_area' => function ($query) use ($service_area_id) {
                $query->where('service_area_id', '=', $service_area_id);
            }
        ])
            ->where([
                ['status', '=', 1],
                ['weight_type', '=', 1]
            ])->get();
        $data['serviceTypes'] = ServiceType::where('service_area_id', $service_area_id)->get();
        $data['itemTypes'] = ItemType::where('service_area_id', $service_area_id)->get();
        return view('admin.parcel.editParcel', $data);
    }


    public function confirmEditParcel(Request $request, Parcel $parcel)
    {

        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'weight_package_charge' => 'required',
            'delivery_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            //            'delivery_option_id' => 'required',
            //            'product_details' => 'required',
            'product_value' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'              => 'required',
            'area_id' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details' => $request->input('product_details'),
            'product_value' => $request->input('product_value'),
            'district_id' => $request->input('district_id'),
            // 'upazila_id'              => $request->input('upazila_id'),
            'upazila_id' => 0,
            'area_id' => $request->input('area_id') ?? 0,
            'weight_package_id' => $request->input('weight_package_id'),
            'weight_package_charge' => $request->input('weight_package_charge'),
            'delivery_charge' => $request->input('delivery_charge'),
            'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
            'cod_percent' => $request->input('cod_percent'),
            'cod_charge' => $request->input('cod_charge'),
            'total_charge' => $request->input('total_charge'),
            //            'delivery_option_id' => $request->input('delivery_option_id'),
            'parcel_note' => $request->input('parcel_note'),
            'merchant_order_id' => $request->input('merchant_order_id'),
            'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
            'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
            'item_type_charge' => $request->input('item_type_charge'),
            'service_type_charge' => $request->input('service_type_charge'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];
        //        dd($data);

        $parcelOld = Parcel::find($parcel->id);

        $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

        $x = 'Update: ';
        $hasUpdated = false;

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
                    $x .= ' & total collect amount ' . $oldTotal_collect_amount . ' to ' . $newTotal_collect_amount;
                } else {
                    $x .= 'Total collect amount has been changed to ' . $oldTotal_collect_amount . ' to ' . $newTotal_collect_amount;
                }
                $hasUpdated = true;
            }

            if ($hasUpdated) {
                createActivityLog($x, $parcelOld, auth()->guard('admin')->user()->name);
            }

            // $data = [
            //     'parcel_id' => $parcel->id,
            //     'admin_id' => auth()->guard('admin')->user()->id,
            //     'date' => date('Y-m-d'),
            //     'time' => date('H:i:s'),
            //     'status' => $parcel->status,
            //     'delivery_type' => $parcel->delivery_type,
            // ];
            // ParcelLog::create($data);

            $this->setMessage('Parcel Update Successfully', 'success');
            return redirect()->back();
        } else {
            $this->setMessage('Parcel Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function printParcel(Request $request, Parcel $parcel)
    {
        // echo '<img src="data:image/png; base64,' . \DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />'; exit;
        // echo \DNS1D::getBarcodeHTML($parcel->parcel_invoice, 'C39', 1.5, 33); exit;

        $parcel->load('merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        $data = [];
        $data['parcel'] = $parcel;
        return view('admin.parcel.printParcel', $data);
    }


    //    public function destroy(Request $request, Parcel $parcel) {
    //        $check = $parcel->delete() ? true : false;
    //
    //        if ($check) {
    //            $this->setMessage('Parcel Delete Successfully', 'success');
    //        } else {
    //            $this->setMessage('Parcel Delete Failed', 'danger');
    //        }
    //
    //        return redirect()->route('admin.parcel.parcelList');
    //    }

    public function delete(Request $request)
    {
        // dd($request->all());
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                if ($parcel->parcel_logs) {
                    $parcel->parcel_logs()->delete();
                }
                $check = Parcel::where('id', $request->parcel_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Parcel Delete Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function orderTracking($parcel_invoice = '')
    {

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'orderTracking';
        $data['parcel_invoice'] = urldecode($parcel_invoice);
        $data['page_title'] = 'Order Tracking';
        return view('admin.parcel.orderTracking', $data);
    }


    public function returnOrderTrackingResult(Request $request)
    {
        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if ((!is_null($parcel_invoice) && $parcel_invoice != '') || (!is_null($merchant_order_id) && $merchant_order_id != '')) {
            $parcel = Parcel::with(
                'district',
                'upazila',
                'area',
                'merchant',
                'weight_package',
                'pickup_branch',
                'pickup_rider',
                'delivery_branch',
                'delivery_rider'
            )
                ->where(function ($query) use ($parcel_invoice, $merchant_order_id) {
                    if (!is_null($parcel_invoice)) {
                        $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                    } elseif (!is_null($merchant_order_id)) {
                        $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                    }
                })
                ->first();
            if ($parcel) {
                $parcelLogs = ParcelLog::with(
                    'pickup_branch',
                    'pickup_rider',
                    'delivery_branch',
                    'delivery_rider',
                    'admin',
                    'merchant'
                )
                    ->where('parcel_id', $parcel->id)
                    ->orderBy('id', 'desc')
                    ->get();
                // dd($parcelLogs);
                return view('admin.parcel.orderTrackingResult', compact('parcel', 'parcelLogs'));
            }
        }
    }
}
