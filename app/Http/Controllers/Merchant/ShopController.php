<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\District;
use App\Models\Merchant;
use App\Models\MerchantShop;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'shop';
        $data['child_menu'] = 'shop-list';
        $data['page_title'] = 'Shop List';
        $data['collapse'] = 'sidebar-collapse';
        return view('merchant.shop.index', $data);
    }


    public function getShops()
    {
        $model = Merchant::with(['district', 'upazila', 'area', 'branch'])
            ->where('parent_merchant_id', auth('merchant')->user()->id)
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('image', function ($data) {
                $image = "";

                if (!empty($data->image)) {
                    $image = '<img src="' . asset('uploads/merchant/' . $data->image) . '"
                            class="img-fluid img-thumbnail"
                            style="height: 55px !important; width: 100px !important;" alt="Merchant Image">';
                }

                return $image;
            })
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success";
                    $status = 0;
                    $status_name = "Active";
                } else {
                    $class = "danger";
                    $status = 1;
                    $status_name = "Inactive";
                }


                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px; pointer-events: none" > ' . $status_name . '</a>';

            })
            ->editColumn('cod_charge', function ($data) {
                $cod_charge = "0 %";

                if (!empty($data->cod_charge)) {
                    $cod_charge = $data->cod_charge . ' %';
                } elseif (is_null($data->cod_charge)) {
                    $cod_charge = "";
                }

                return $cod_charge;
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" merchant_id="' . $data->id . '" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                return $button;
            })

            ->editColumn('created_at', function ($data) {



                return $data->created_at->format("d-M-Y");
            })

            ->addColumn('branch_name', function ($data) {
                if ($data->branch_id) {
                    $text = '<span>' . $data->branch->name . '</span>';
                } else {
                    $text = '<span class="text-danger">' . $data->branch->name . '</span>';
                }


                return $text;
            })
            ->rawColumns(['image', 'status', 'action', 'image', 'created_at', 'branch_name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['main_menu'] = 'team';
        $data['child_menu'] = 'merchant';
        $data['page_title'] = 'Create Merchant';
        $data['districts'] = District::where('status', 1)->get();
        $data['serviceAreas'] = ServiceArea::where(['status' => 1, 'weight_type' => 1])->get();
        $data['branches'] = Branch::where('status', 1)->get();
        return view('merchant.shop.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:merchants',
            'image' => 'sometimes|image|max:3000',
            'branch_id' => 'required',
            // 'cod_charge'        => 'sometimes',
            'password' => 'sometimes',
            'address' => 'sometimes',
            'contact_number' => 'required',
            'district_id' => 'required',
            // 'upazila_id'        => 'required',
            'area_id' => 'sometimes',
            'business_address' => 'sometimes',
            'fb_url' => 'sometimes',
            'web_url' => 'sometimes',
            'bank_account_name' => 'sometimes',
            'bank_account_no' => 'sometimes',
            'bank_name' => 'sometimes',
            'bkash_number' => 'sometimes',
            'nagad_number' => 'sometimes',
            'rocket_name' => 'sometimes',
            'nid_no' => 'sometimes',
            'nid_card' => 'sometimes|image|max:3000',
            'trade_license' => 'sometimes|image|max:3000',
            'tin_certificate' => 'sometimes|image|max:3000',
        ], [
            'name.unique' => 'This Email Already Exist',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $image_name = null;
            $trade_license = null;
            $nid_card = null;
            $tin_certificate = null;

            if ($request->hasFile('image')) {
                $image_name = $this->uploadFile($request->file('image'), '/merchant/');
            }

            if ($request->hasFile('trade_license')) {
                $trade_license = $this->uploadFile($request->file('trade_license'), '/merchant/');
            }

            if ($request->hasFile('nid_card')) {
                $nid_card = $this->uploadFile($request->file('nid_card'), '/merchant/');
            }

            if ($request->hasFile('tin_certificate')) {
                $tin_certificate = $this->uploadFile($request->file('tin_certificate'), '/merchant/');
            }

            $password = $request->input('password') ?? 12345;
            $data = [
                'm_id' => $this->returnUniqueMerchantId(),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($password),
                'store_password' => $password,
                'company_name' => $request->input('company_name'),
                'address' => $request->input('address'),
                'contact_number' => $request->input('contact_number'),
                'branch_id' => $request->input('branch_id'),
                // 'cod_charge'        => $request->input('cod_charge'),
                'district_id' => $request->input('district_id'),
                // 'upazila_id'        => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'business_address' => $request->input('business_address'),
                'fb_url' => $request->input('fb_url'),
                'web_url' => $request->input('web_url'),
                'bank_account_name' => $request->input('bank_account_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'bank_name' => $request->input('bank_name'),
                'bkash_number' => $request->input('bkash_number'),
                'nagad_number' => $request->input('nagad_number'),
                'rocket_name' => $request->input('rocket_name'),
                'nid_no' => $request->input('nid_no'),
                'image' => $image_name,
                'trade_license' => $trade_license,
                'nid_card' => $nid_card,
                'tin_certificate' => $tin_certificate,
                'date' => date('Y-m-d'),
                'status' => 0,
                'created_admin_id' => 0,
                'parent_merchant_id' => auth('merchant')->user()->id,
            ];

            $merchant = Merchant::create($data);

            if ($merchant) {
                $charge = $request->input('charge');
                $return_charge = $request->input('return_charge');
                $cod_charge = $request->input('cod_charge');
                $service_area_id = $request->input('service_area_id');
                $count = count($service_area_id);

                $sync_charge_data = [];
                $sync_return_charge_data = [];
                $sync_cod_charge_data = [];

                for ($i = 0; $i < $count; $i++) {
                    if (!is_null($cod_charge[$i])) {
                        $sync_cod_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'cod_charge' => $cod_charge[$i],
                        ];
                    }

                    if (!is_null($charge[$i])) {
                        $sync_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'charge' => $charge[$i],
                        ];
                    }

                    if (!is_null($return_charge[$i])) {
                        $sync_return_charge_data[$service_area_id[$i]] = [
                            'merchant_id' => $merchant->id,
                            'return_charge' => $return_charge[$i],
                        ];
                    }
                }

                $merchant->service_area_charges()->sync($sync_charge_data);
                $merchant->service_area_return_charges()->sync($sync_return_charge_data);
                $merchant->service_area_cod_charges()->sync($sync_cod_charge_data);

                \DB::commit();

                // $admin_users = Admin::all();
                // foreach ($admin_users as $admin) {
                //     $admin->notify(new MerchantRegisterNotification($merchant));
                // }
                // $this->adminDashboardCounterEvent();

                $this->setMessage('Child Merchant Create Successfully', 'success');
                return redirect()->route('merchant.shop.index');
            } else {
                $this->setMessage('Merchant Create Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantShop $shop)
    {
        $shop->load('merchants');
        return view('merchant.shop.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantShop $shop)
    {
        $data = [];
        $data['main_menu'] = 'shop';
        $data['child_menu'] = 'shop-list';
        $data['page_title'] = 'Edit Shop';
        $data['shop'] = $shop;
        return view('merchant.shop.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MerchantShop $shop)
    {
        $merchant_id = auth('merchant')->user()->id;

        $validator = Validator::make($request->all(), [
            'shop_name' => 'required|unique:merchant_shops,shop_name,' . $shop->id . ',id,merchant_id,' . $merchant_id,
            'shop_address' => 'required',
        ], [
            'shop_name.unique' => 'This shop already exists',
            'shop_address.required' => 'Shop Address Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'shop_name' => $request->input('shop_name'),
            'shop_address' => $request->input('shop_address'),
            'status' => $request->input('status'),
        ];
        $check = MerchantShop::where('id', $shop->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Shop Update Successfully', 'success');
            return redirect()->route('merchant.shop.index');
        } else {
            $this->setMessage('Shop Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }



    public function updateStatus(Request $request)
    {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = MerchantShop::where('id', $request->shop_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Shop Status Update Successfully',
                        'status' => $request->status,
                    ];
                } else {
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }

            }

        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantShop $shop)
    {
        $check = $shop->delete() ? true : false;

        if ($check) {
            $this->setMessage('Shop Delete Successfully', 'success');
        } else {
            $this->setMessage('Shop Delete Failed', 'danger');
        }

        return redirect()->route('merchant.shop.index');
    }

    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check = MerchantShop::where('id', $request->shop_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Shop Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

}
