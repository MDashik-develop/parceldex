<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use Illuminate\Http\Request;

class ParcelResetController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $data['main_menu'] = 'parcel-reset';
        $data['child_menu'] = 'single';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse'] = 'sidebar-collapse';

        $parcel_ids = explode(',', str_replace(' ', '', $request->input('order_id')));

        $parcels = Parcel::whereIn('parcel_invoice', $parcel_ids)->get();

        $data['parcels'] = $parcels;

        return view('admin.parcel-reset.index', $data);
    }

    public function update(Request $request)
    {

        // dd($request->all());

        foreach ($request->parcels as $parcel_invoice => $data) {
            $parcel = Parcel::where('parcel_invoice', $parcel_invoice)->first();

            if ($parcel) {

                $update_data = [];

                if ($data['status']) {
                    $update_data['status'] = $data['status'];
                }

                if ($data['payment_type']) {
                    $update_data['payment_type'] = $data['payment_type'];
                }

                if ($data['total_collect_amount']) {
                    $update_data['total_collect_amount'] = $data['total_collect_amount'];
                }

                if ($data['customer_collect_amount']) {
                    $update_data['customer_collect_amount'] = $data['customer_collect_amount'];
                }

                $parcel->update($update_data);
            }
        }

        return redirect()->back()->with('success', 'Parcel status updated successfully');
    }

}
