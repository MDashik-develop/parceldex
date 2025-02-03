<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanceController extends Controller
{

    public function details()
    {
        $data = [];
        $data['main_menu'] = 'request';
        $data['child_menu'] = 'parcelPaymentRequest';
        $data['page_title'] = 'Parcel Payment Request ';
        $data['collapse'] = 'sidebar-collapse';
        $data['collapse'] = 'sidebar-collapse';

        return view('merchant.balance.details', $data);
    }
}
