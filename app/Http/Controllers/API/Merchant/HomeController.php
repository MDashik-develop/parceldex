<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Parcel;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function dashboard(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        if ($request->start_date && $request->end_date) {
            Parcel::whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $data = [];

        $x = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '=', 25)
            ->whereIn('delivery_type', [1, 2, 4])
            ->where(function ($query) {
                $query->whereIn('payment_type', [1, 2, 3, 6])->orWhereNull('payment_type');
            })
            ->sum('customer_collect_amount');

        $y = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '=', 25)
            ->whereIn('delivery_type', [1, 2, 4])
            ->where(function ($query) {
                $query->whereIn('payment_type', [1, 2, 3, 6])->orWhereNull('payment_type');
            })
            ->sum('cancel_amount_collection');

        $total_customer_collect_amount = $x + $y;

        $total_charge_amount = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '=', 25)
            ->whereIn('delivery_type', [1, 2, 4])
            ->where(function ($query) {
                $query->whereIn('payment_type', [1, 2, 3, 6])->orWhereNull('payment_type');
            })
            ->sum('total_charge');

        $data['total_pending_payment'] =  number_format($total_customer_collect_amount - $total_charge_amount, 2, '.', '');


        // $data['total_pending_payment'] = $query->where('merchant_id', $merchant_id)
        //     ->count();

        $data['total_parcel'] = Parcel::where('merchant_id', $merchant_id)->whereNull('suborder')
            ->count();

        $data['total_cancel_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->where('status', 3)
            ->count();

        $data['total_cancel_parcel_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereIn('status', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36])
            ->where('delivery_type', 4)
            ->whereNull('suborder')
            ->sum('total_collect_amount');

        $data['total_waiting_pickup_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status < ?', [3, 11])
            ->count();

        $data['total_waiting_pickup_parcel_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereIn('status', [1, 3, 4, 5, 6, 7, 8, 9, 10])
            ->whereNull('suborder')
            ->sum('total_collect_amount');

        $data['total_waiting_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereIn('status', [16, 17, 18, 19, 20, 21, 22, 23, 24])
            ->whereIn('delivery_type', [1, 2, 3, 4, null])->count();
            
        //  ->whereRaw('(status >= ? and status <= ?) and (delivery_type is null or delivery_type = "" or delivery_type = ?)', [16, 24, 3])->count();

        // ->whereRaw('(status != ? and status >= ? and status <= ?) and (delivery_type is null or delivery_type = "" or delivery_type = ?)', [3, 16, 25, 3])

        // ->whereRaw('status >= ? and status <= ? and (delivery_type is null or delivery_type = "" or delivery_type = "3")', [ 4, 24])
        // ->count();

        /*$data['total_waiting_delivery_parcel']  = $query->where('merchant_id', $merchant_id)
                                                ->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "" or delivery_type in (?))', [3,11,24,3])
                                                ->count();*/
        /*
                $data['total_delivery_parcel']          = $query->where('merchant_id', $merchant_id)
                                                        ->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3,1,2,3,4])
                                                        ->count();*/

        $data['total_delivery_parcel'] = Parcel::where('merchant_id', $merchant_id)
            // ->whereRaw('status >= ? and delivery_type in (?,?)', [25, 1, 2])
            ->whereRaw('status >= ? and delivery_type in (?)', [25, 1])
            ->count();

        $data['total_delivery_parcel_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status != ? and status < ?', [16, 24])
            ->whereNull('suborder')
            ->sum('total_collect_amount');

        $data['total_delivery_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 5])
            ->count();

        $data['total_delivery_complete_parcel_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status = ? and delivery_type in (?)', [25, 1])
            ->whereNull('suborder')
            ->sum('total_collect_amount');

        $data['total_partial_delivery_complete'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?) ', [25, 2])
            ->count();

        $data['total_partial_delivery_complete_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status = ? and delivery_type in (?) ', [25, 2])
            ->sum('total_collect_amount');

        $data['total_pending_delivery'] = Parcel::where('merchant_id', $merchant_id)

            ->whereRaw('status > 11 and delivery_type in (?)', [3])
            ->count();

        $data['in_transit_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and status <= ?', [11, 15])
            ->count();

        $data['in_transit_parcel_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and status <= ?', [11, 15])
            ->whereNull('suborder')
            ->sum('total_collect_amount');

        // $data['total_waiting_delivery_parcel'] += $data['total_pending_delivery'];

        $data['total_return_parcel'] = Parcel::where('merchant_id', $merchant_id)->whereNull('suborder')
            ->whereRaw('status >= ? and delivery_type in (?)', [25, 4])
            ->count();

        $data['total_return_complete_parcel'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4])
            ->count();


        $data['total_pending_collect_amount'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 4])
            ->sum('merchant_paid_amount');

        //        $data['total_pending_collect_amount']    = $query->where('merchant_id', $merchant_id)
        //                                                    ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25,1,2,4])
        //                                                    ->toSql();
        //
        //        dd($merchant_id, $data['total_pending_collect_amount'] );




        // $data['total_collect_amount'] = $query->where('merchant_id', $merchant_id)
        //     ->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 5])
        //     ->sum('merchant_paid_amount');

        $plus1 = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=', 25)
            ->whereIn('payment_type', [5])
            ->whereIn('delivery_type', [1, 2, 4])
            // ->whereNull('suborder')
            ->sum('customer_collect_amount');

        $plus2 = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=', 25)
            ->whereIn('payment_type', [5])
            ->whereIn('delivery_type', [1, 2, 4])
            // ->whereNull('suborder')
            ->sum('cancel_amount_collection');


        $minus1 = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=', 25)
            ->whereIn('payment_type', [5])
            ->whereIn('delivery_type', [1, 2, 4])
            // ->whereNull('suborder')
            ->sum('total_charge');

        $data['total_collect_amount'] = round(($plus1 + $plus2) - $minus1, 2);

        $data['total_collect_amount_from_customer'] = Parcel::where('merchant_id', $merchant_id)
            ->whereRaw('status >= ? and delivery_type in (?,?) ', [25, 1, 2])
            ->sum('customer_collect_amount');

        $data['total_customer_collected_amount']  = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=', 25)
            ->whereRaw('delivery_type in (?,?)', [1, 2])
            ->sum('customer_collect_amount');

        $data['total_charge']      = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=', 25)
            ->whereRaw('delivery_type in (?,?,?)', [1, 2, 4])
            ->sum('total_charge');

        $data['total_customer_collected_amount'] -= $data['total_charge'];
        $data['total_customer_collected_amount'] = round($data['total_customer_collected_amount'], 2);


        // ================== new codes by Humayun ===============
        // $total_customer_collect_amount      = Parcel::where('merchant_id', $merchant_id)
        //     ->where('status', '>=', 25)
        //     ->whereRaw('delivery_type in (?,?) and payment_type in (?,?,?) and payment_request_status = ?', [1, 2, 2, 4, 6, 0])
        //     ->sum('customer_collect_amount');




        // $total_charge_amount                = Parcel::where('merchant_id', $merchant_id)
        //     ->where('status', '>=', 25)
        //     ->whereRaw('delivery_type in (?,?) and payment_type in (?,?,?) and payment_request_status = ?', [1, 2, 2, 4, 6, 0])
        //     ->sum('total_charge');

        // // Balance Amount
        // $data['total_pending_payment'] = number_format($total_customer_collect_amount - $total_charge_amount, 2, '.', '');
        // ================== new codes by Humayun End ===============



        $data['news'] = Notice::whereRaw('type = 2 and publish_for IN (0,2)')->orderBy('id', 'DESC')->first();




        return response()->json([
            'success' => 200,
            'message' => "Merchant Dashboard.",
            'dashboard_count' => $data,
        ], 200);
    }

    public function viewNews(Request $request)
    {
        $news_id = $request->input('news_id');
        $data['news'] = Notice::where('id', $news_id)->first();
        return response()->json([
            'success' => 200,
            'message' => "News view",
            'data' => $data,
        ], 200);
    }
}
