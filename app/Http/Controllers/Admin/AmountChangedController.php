<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AmountChangedController extends Controller
{

    public function index()
    {
        $data = [];
        $data['main_menu'] = 'amountCharge';
        $data['child_menu'] = 'amountCharge';
        $data['page_title'] = 'amount-charge';


        return view('admin.amount_changed.index', $data);
    }

}
