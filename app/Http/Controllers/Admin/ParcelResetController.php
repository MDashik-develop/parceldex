<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use Illuminate\Http\Request;

class ParcelResetController extends Controller
{

    public function single(Request $request)
    {
        $data = [];
        $data['main_menu'] = 'parcel-reset';
        $data['child_menu'] = 'single';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse'] = 'sidebar-collapse';

        $parcel_ids = explode(',', str_replace(' ', '', $request->input('order_id')));

        $parcels = Parcel::whereIn('parcel_invoice', $parcel_ids)->get();

        $data['parcels'] = $parcels;

        // dd($parcel_ids);

        return view('admin.parcel-reset.single', $data);
    }

    public function bulk()
    {
        $data = [];
        $data['main_menu'] = 'parcel-reset';
        $data['child_menu'] = 'bulk';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse'] = 'sidebar-collapse';

        return view('admin.parcel-reset.bulk', $data);
    }

}
