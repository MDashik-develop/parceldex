<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParcelResetController extends Controller
{

    public function single()
    {
        $data = [];
        $data['main_menu'] = 'parcel-reset';
        $data['child_menu'] = 'single';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse'] = 'sidebar-collapse';

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
