<?php

use Carbon\Carbon;
use App\Models\Parcel;
use App\Models\ParcelLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;


if (!function_exists('send_bl_sms')) {
    function send_bl_sms($phone, $message)
    {
        $msisdn = trim($phone);
        $n = strlen($msisdn);
        if ($n == 11) {
            $msisdn = '88' . $msisdn;
        }


        return Http::post("https://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=NOMASK&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");
        return true;


        // Too stop SMS
        //   return true;


    }
}


if (!function_exists('send_signup_sms')) {
    function send_signup_sms($phone, $message)
    {
        $msisdn = trim($phone);
        $n = strlen($msisdn);
        if ($n == 11) {
            $msisdn = '88' . $msisdn;
        }


        return Http::post("http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=Parceldex&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");
        //  return Http::post("https://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=NOMASK&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");



        //   return Http::post("http://bulksms.zaman-it.com/api/sendsms?api_key=01708063104.E1EqubtDkEcyCPHvaj&type=text&phone=$msisdn&senderid=8809604903051&message=$message");
        // Too stop SMS
        //   return true;


    }
}


if (!function_exists('pathao_access_token')) {
    function pathao_access_token()
    {
        $data = [
            'client_id' => 'olejRwPejN',
            'client_secret' => 'ZZfu3mllrBe3RautzMnwjFHhIUMkSDo7Or4bFZ73',
            'username' => 'parceldex@gmail.com',
            'password' => 'parceldex2023',
            'grant_type' => 'password',
        ];
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post("https://api-hermes.pathao.com/aladdin/api/v1/issue-token", $data);
        $response = json_decode($res, true);
        return $response['access_token'];
    }
}

if (!function_exists('get_pathao_cities')) {
    function get_pathao_cities($access_token = null)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/countries/1/city-list");
        $response = json_decode($res, true);
        return $response['data']['data'];
    }
}
if (!function_exists('get_pathao_zones')) {
    function get_pathao_zones($city_id, $access_token = null)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/cities/$city_id/zone-list");
        $response = json_decode($res, true);
        return $response['data']['data'];
    }
}
if (!function_exists('get_pathao_areas')) {
    function get_pathao_areas($zone_id, $access_token = null)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/zones/$zone_id/area-list");
        $response = json_decode($res, true);
        return $response['data']['data'];
    }
}

if (!function_exists('create_pathao_order')) {
    function create_pathao_order($access_token, $city_id, $zone_id, $area_id, $parcel)
    {
        $data = [
            "store_id" => 126179,
            "merchant_order_id" => $parcel->parcel_invoice,
            "sender_name" => "ParcelDex",
            "sender_phone" => '01601057407',
            "recipient_name" => $parcel->customer_name,
            "recipient_phone" => $parcel->customer_contact_number,
            "recipient_address" => $parcel->customer_address,

            "recipient_city" => $city_id,
            "recipient_zone" => $zone_id,
            "recipient_area" => $area_id,

            "delivery_type" => 48,
            "item_type" => 2,
            "special_instruction" => $parcel->parcel_note,
            //            "special_instruction" => "Api testing",
            "item_quantity" => 1,
            "item_weight" => (float)$parcel->weight_package->name,
            "amount_to_collect" => $parcel->total_collect_amount,
            "item_description" => $parcel->product_details
        ];
        // dd($data);
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->post("https://api-hermes.pathao.com/aladdin/api/v1/orders", $data);
        return json_decode($res, true);
    }
}

function last_query_start()
{
    \DB::enableQueryLog();
}

function last_query_end()
{
    $query = \DB::getQueryLog();
    dd(end($query));
}

function debugger_data($data)
{
    echo "<pre>";
    print_r(json_decode($data));
    exit;
}

function setTimeZone()
{
    date_default_timezone_set('Asia/Dhaka');
}


function notification_data() {}

function file_url($file, $path)
{
    return asset('uploads/' . $path . '/' . $file);
}



function merchantParcelNotification($merchant_id)
{
    Notification::where('send_to', $merchant_id)->where('status', 'unread')->union(
        Notification::where('send_to', $merchant_id)
            ->where('status', 'read')
            ->orderBy('id', 'desc')
            ->limit(15)
            ->getQuery()
    )->get();
}


function error_processor($validator)
{
    $err_keeper = [];
    foreach ($validator->errors()->getMessages() as $index => $error) {
        array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
    }
    return $err_keeper;
}




function returnParcelStatusNameForAdmin($status, $delivery_type, $payment_type)
{
    $status_name    = "";
    $class          = "";

    $returnableParcel   = ($delivery_type == 2 || $delivery_type == 4);
    $deliveredParcel    = ($delivery_type == 1 || $delivery_type == 2);

    if ($status == 1) {
        $status_name  = "Pickup Request";
        $class        = "success";
    } elseif ($status == 2) {
        $status_name  = "Parcel Hold";
        $class        = "warning";
    } elseif ($status == 3) {
        // $status_name  = "Parcel Cancel";
        $status_name  = "Deleted";
        $class        = "danger";
    } elseif ($status == 4) {
        $status_name  = "Re-schedule Pickup";
        $class        = "warning";
    } elseif ($status == 5) {
        $status_name  = "Assign for Pickup";
        $class        = "success";
    } elseif ($status == 6) {
        $status_name  = "Rider Assign For Pick";
        $class        = "success";
    } elseif ($status == 7) {
        $status_name  = "Pickup Run Cancel";
        $class        = "warning";
    } elseif ($status == 8) {
        $status_name  = "On the way to Pickup";
        $class        = "success";
    } elseif ($status == 9) {
        $status_name  = "Pickup Rider Reject";
        $class        = "warning";
    } elseif ($status == 10) {
        $status_name  = "Pickup Rider Complete Task";
        $class        = "success";
    } elseif ($status == 11) {
        $status_name  = "Picked Up";
        $class        = "success";
    } elseif ($status == 12) {
        $status_name  = "Branch Transfer";
        $class        = "success";
    } elseif ($status == 13) {
        $status_name  = "Branch Transfer Cancel";
        $class        = "warning";
    } elseif ($status == 14) {
        $status_name  = "Branch Transfer Complete";
        $class        = "success";
    } elseif ($status == 15) {
        $status_name  = "Delivery Branch Reject";
        $class        = "warning";
    } elseif ($status == 16) {
        $status_name  = "Delivery Run Create";
        $class        = "success";
    } elseif ($status == 17) {
        $status_name  = "Delivery Run Start";
        $class        = "success";
    } elseif ($status == 18) {
        $status_name  = "Delivery Run Cancel";
        $class        = "warning";
    } elseif ($status == 19) {
        $status_name  = "Delivery Rider Accept";
        $class        = "success";
    } elseif ($status == 20) {
        $status_name  = "Delivery Rider Reject";
        $class        = "warning";
    } elseif ($status == 21) {
        $status_name  = "Delivery Rider Complete Delivery";
        $class        = "success";
    } elseif ($status == 22) {
        $status_name  = "Partial Delivered";
        $class        = "success";
    } elseif ($status == 23) {
        $status_name  = "Rescheduled";
        $class        = "success";
    } elseif ($status == 24) {
        $status_name  = "Delivery Rider Return";
        $class        = "warning";
    } elseif ($status == 25 && $delivery_type == 1) {
        $status_name  = "Delivery Complete";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 2) {
        $status_name  = "Partial Delivery";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 3) {
        $status_name  = "Reschedule Delivery";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 4) {
        $status_name  = "Delivery Cancel";
        $class        = "success";
    }

    /** For Partial Delivery Return */
    // elseif ($status == 26 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Transfer";
    //     $class        = "success";
    // } elseif ($status == 27 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Transfer Cancel";
    //     $class        = "success";
    // } elseif ($status == 28 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Transfer Complete";
    //     $class        = "success";
    // } elseif ($status == 29 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Transfer Reject";
    //     $class        = "success";
    // } elseif ($status == 30 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Run Create";
    //     $class        = "success";
    // } elseif ($status == 31 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Run start";
    //     $class        = "success";
    // } elseif ($status == 32 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Run Cancel";
    //     $class        = "success";
    // } elseif ($status == 33 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Rider Accept";
    //     $class        = "success";
    // } elseif ($status == 34 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Rider Reject";
    //     $class        = "success";
    // } elseif ($status == 35 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Run Complete";
    //     $class        = "success";
    // } elseif ($status == 36 && $delivery_type == 2) {
    //     $status_name  = "Delivery Exchange Return Complete";
    //     $class        = "success";
    // }

    /** For Return Parcel */
    elseif ($status == 26 && $delivery_type == 4) {
        $status_name  = "Return Transfer";
        $class        = "success";
    } elseif ($status == 27 && $delivery_type == 4) {
        $status_name  = "Return Transfer Cancel";
        $class        = "success";
    } elseif ($status == 28 && $delivery_type == 4) {
        $status_name  = "Return Transfer Complete";
        $class        = "success";
    } elseif ($status == 29 && $delivery_type == 4) {
        $status_name  = "Return Transfer Reject";
        $class        = "success";
    } elseif ($status == 30 && $delivery_type == 4) {
        $status_name  = "Return Run Create";
        $class        = "success";
    } elseif ($status == 31 && $delivery_type == 4) {
        $status_name  = "Return Run start";
        $class        = "success";
    } elseif ($status == 32 && $delivery_type == 4) {
        $status_name  = "Return Run Cancel";
        $class        = "success";
    } elseif ($status == 33 && $delivery_type == 4) {
        $status_name  = "Return Run Rider Accept";
        $class        = "success";
    } elseif ($status == 34 && $delivery_type == 4) {
        $status_name  = "Return Run Rider Reject";
        $class        = "success";
    } elseif ($status == 35 && $delivery_type == 4) {
        $status_name  = "Return Run Complete";
        $class        = "success";
    } elseif ($status == 36 && $delivery_type == 4) {
        $status_name  = "Return Complete";
        $class        = "success";
    }

    /** For Payment Status */
    if ($delivery_type == 1 && $status == 25 && $payment_type == 1) {
        $status_name  = "Branch Payment Request";
        $class        = "primary";
    } elseif ($delivery_type == 1 && $status == 25 && $payment_type == 2) {
        $status_name  = "Accounts Accept Payment";
        $class        = "success";
    } elseif ($delivery_type == 1 && $status == 25 && $payment_type == 3) {
        $status_name  = "Accounts Reject Payment";
        $class        = "warning";
    } elseif ($delivery_type == 1 && $status == 25 && $payment_type == 4) {
        $status_name  = "Accounts Payment Request";
        $class        = "primary";
    } elseif ($delivery_type == 1 && $status == 25 && $payment_type == 5) {
        $status_name  = "Paid ";
        // $status_name  = "Accounts Payment Done";
        $class        = "success";
    } elseif ($delivery_type == 1 && $status == 25 && $payment_type == 6) {
        $status_name  = "Merchant Payment Reject";
        $class        = "warning";
    }


    /** For Partial Payment Status */
    // if ($delivery_type == 2 && $status == 25 && $payment_type == 1) {
    //     $status_name  = "Branch Delivery Exchange Payment Request";
    //     $class        = "primary";
    // } elseif ($delivery_type == 2 && $status == 25 && $payment_type == 2) {
    //     $status_name  = "Accounts Delivery Exchange Accept Payment";
    //     $class        = "success";
    // } elseif ($delivery_type == 2 && $status == 25 && $payment_type == 3) {
    //     $status_name  = "Accounts Delivery Exchange Reject Payment";
    //     $class        = "warning";
    // } elseif ($delivery_type == 2 && $status == 25 && $payment_type == 4) {
    //     $status_name  = "Accounts Delivery Exchange Payment Request";
    //     $class        = "primary";
    // } elseif ($delivery_type == 2 && $status == 25 && $payment_type == 5) {
    //     $status_name  = "Accounts Delivery Exchange Payment Done";
    //     $class        = "success";
    // } elseif ($delivery_type == 2 && $status == 25 && $payment_type == 6) {
    //     $status_name  = "Merchant Delivery Exchange Payment Reject";
    //     $class        = "warning";
    // }

    return [
        'status_name'   => $status_name,
        'class'         => $class
    ];
}


// color class comment------
//  primary -blue, warning -yellow  , info - akasi , danger - red , success - green , secondary - gray

function returnParcelStatusForAdmin($status, $delivery_type, $payment_type = null, $parcel = null)
{
    $status_name    = "";
    $class          = "";

    if ($status == 1) {
        $status_name  = "Pick Requested";
        $class        = "success";
    } elseif ($status == 2) {
        $status_name  = "Pick Request Hold";
        $class        = "warning";
    } elseif ($status == 3) {
        // $status_name  = "Parcel Cancel";
        $status_name  = "Deleted";
        $class        = "danger";
    } elseif ($status == 4) {
        $status_name  = "Pick Request Reschedule";
        $class        = "warning";
    } elseif ($status == 5) {
        $status_name  = "Pick Run Create";
        $class        = "success";
    } elseif ($status == 6) {
        $status_name  = "Pick Run Start";
        $class        = "success";
    } elseif ($status == 7) {
        $status_name  = "Pick Run Cancel";
        $class        = "warning";
    } elseif ($status == 8) {
        $status_name  = "Pick Rider Accept";
        $class        = "success";
    } elseif ($status == 9) {
        $status_name  = "Pick Rider Declined";
        $class        = "warning";
    } elseif ($status == 10) {
        $status_name  = "Picked Up by Rider";
        $class        = "success";
    } elseif ($status == 11) {
        $status_name  = "Picked Up";
        $class        = "success";
    } elseif ($status == 12) {
        $status_name  = "Transfer Run Create";
        $class        = "secondary";
    } elseif ($status == 13) {
        $status_name  = "Transfer Run Cancel";
        $class        = "warning";
    } elseif ($status == 14) {
        $status_name  = "At Kitchen";
        $class        = "info";
    } elseif ($status == 15) {
        $status_name  = "Transfer Declined";
        $class        = "warning";
    } elseif ($status == 16) {
        $status_name  = "Rider Assign Run Create";
        $class        = "success";
    } elseif ($status == 17) {
        $status_name  = "Rider Assign Run Start";
        $class        = "success";
    } elseif ($status == 18) {
        $status_name  = "Rider Assign Run Cancel";
        $class        = "warning";
    } elseif ($status == 19) {
        $status_name  = "Rider Accept for Delivery";
        $class        = "success";
    } elseif ($status == 20) {
        $status_name  = "Rider Declined";
        $class        = "warning";
    } elseif ($status == 21) {
        $status_name  = "Rider Requested for Delivery";
        $class        = "success";
    } elseif ($status == 22) {
        $status_name  = "Rider Requested for Partial Delivery";
        $class        = "success";
    } elseif ($status == 23) {
        $status_name  = "Rider Requested for Reschedule";
        $class        = "success";
    } elseif ($status == 24) {
        $status_name  = "Rider Requested for Cancel";
        $class        = "warning";
    } elseif ($status >= 25 && $delivery_type == 1) {
        $status_name  = "Delivered";
        $class        = "success";
    } elseif ($status >= 25 && $delivery_type == 2) {
        $status_name  = "Partial Delivery";
        $class        = "success";
    } elseif ($status >= 25 && $delivery_type == 3) {
        $status_name  = "Reschedule Delivery";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 4) {
        $status_name  = "Cancelled";
        $class        = "danger";

        $x = $parcel;

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Cancelled";
        }
    } elseif ($status == 26 && $delivery_type == 4) {
        $status_name  = "Return Transfer Run Create";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Transfer Run Create";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Transfer Run Create";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Transfer Run Create";
        }
    } elseif ($status == 27 && $delivery_type == 4) {
        $status_name  = "Return Transfer Run Cancel";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Transfer Run Cancel";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Transfer Run Cancel";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Transfer Run Cancel";
        }
    } elseif ($status == 28 && $delivery_type == 4) {
        $status_name  = "Return Transfer Received";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Transfer Received";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Transfer Received";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Transfer Received";
        }
    } elseif ($status == 29 && $delivery_type == 4) {
        $status_name  = "Return Transfer Declined";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return Transfer Declined";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return Transfer Declined";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Transfer Declined";
        }
    } elseif ($status == 30 && $delivery_type == 4) {
        $status_name  = "Return Assign Run Create";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Run Create";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Run Create";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Run Create";
        }
    } elseif ($status == 31 && $delivery_type == 4) {
        $status_name  = "Return Assign Run Start";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Run Start";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Run Start";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Run Start";
        }
    } elseif ($status == 32 && $delivery_type == 4) {
        $status_name  = "Return Assign Declined";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Declined";
        }
    } elseif ($status == 33 && $delivery_type == 4) {
        $status_name  = "Return Assign Accept";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Accept";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Accept";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Accept";
        }
    } elseif ($status == 34 && $delivery_type == 4) {
        $status_name  = "Return Assign Declined";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Declined";
        }
    } elseif ($status == 35 && $delivery_type == 4) {
        $status_name  = "Return Confirmed";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return Confirmed";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return Confirmed";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Confirmed";
        }
    } elseif ($status == 36 && $delivery_type == 4) {
        $status_name  = "Returned";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Returned";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Returned";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Returned";
        }
    }

    if ($status == 0 && isset($parcel) && $parcel?->is_push) {
        $status_name  = "In Review API";
        $class        = "success";
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class
    ];
}

function returnDeliveryStatusForAdmin($status, $delivery_type, $payment_type)
{
    $status_name    = "";
    $class          = "";

    if ($delivery_type) {
        if ($status >= 25 && $delivery_type == 1) {
            $status_name  = "Delivered";
            $class        = "success";
        } elseif ($status >= 25 && $delivery_type == 2) {
            $status_name  = "Partial Delivery";
            $class        = "success";
        } elseif ($delivery_type == 3) {
            $status_name  = "Reschedule";
            $class        = "warning";
        } elseif ($status >= 25 && $delivery_type == 4) {
            $status_name  = "Returned";
            $class        = "danger";
        }
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class
    ];
}

function returnPaymentStatusForAdmin($status, $delivery_type, $payment_type, $parcel)
{
    $status_name    = "";
    $class          = "";
    $time           = "";

    $query = ParcelLog::where('parcel_id', $parcel->id)->where('status', '>=', 25)->whereIn('delivery_type', [1, 2, 4]);

    if ($status >= 25 && ($delivery_type == 1 || $delivery_type == 2 || $delivery_type == 4) && $payment_type) {
        if ($payment_type == 1) {
            $status_name  = "Branch Payment Request";
            $class        = "primary";
            $x            = $query->whereHas('parcel', function ($query) {
                $query->where('payment_type', 1);
            })->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($payment_type == 2) {
            $status_name  = "Accounts Accept Payment";
            $class        = "success";
            $x            = $query->whereHas('parcel', function ($query) {
                $query->where('payment_type', 2);
            })->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($payment_type == 3) {
            $status_name  = "Accounts Reject Payment";
            $class        = "warning";
            $x            = $query->whereHas('parcel', function ($query) {
                $query->where('payment_type', 3);
            })->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($payment_type == 4) {
            $status_name  = "Accounts Payment Request";
            $class        = "primary";
            $x            = $query->whereHas('parcel', function ($query) {
                $query->where('payment_type', 4);
            })->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($payment_type == 5) {
            $status_name  = "Paid ";
            // $status_name  = "Accounts Payment Done";
            $class        = "success";
            $x            = $query->whereHas('parcel', function ($query) {
                $query->where('payment_type', 5);
            })->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($payment_type == 6) {
            $status_name  = "Merchant Payment Reject";
            $class        = "warning";
            $x            = $query->whereHas('parcel', function ($query) {
                $query->where('payment_type', 6);
            })->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        }
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class,
        'time'         => $time
    ];
}

function returnReturnStatusForAdmin($status, $delivery_type, $payment_type, $parcel)
{
    $status_name    = "";
    $class          = "";
    $time = "";

    $query = ParcelLog::where('parcel_id', $parcel->id);

    if ($status >= 25 && $delivery_type && ($delivery_type == 2 || $delivery_type == 4)) {
        /** For Partial Delivery Return */
        if ($status == 26) {
            $status_name  = "Return Transfer";
            $class        = "success";
            $x            = $query->where('status', 26)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 27) {
            $status_name  = "Return Transfer Cancel";
            $class        = "success";
            $x            = $query->where('status', 27)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 28) {
            $status_name  = "Return Transfer Complete";
            $class        = "success";
            $x            = $query->where('status', 28)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 29) {
            $status_name  = "Return Transfer Reject";
            $class        = "success";
            $x            = $query->where('status', 29)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 30) {
            $status_name  = "Return Run Create";
            $class        = "success";
            $x            = $query->where('status', 30)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 31) {
            $status_name  = "Return Run start";
            $class        = "success";
            $x            = $query->where('status', 31)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 32) {
            $status_name  = "Return Run Cancel";
            $class        = "success";
            $x            = $query->where('status', 32)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 33) {
            $status_name  = "Return Run Rider Accept";
            $class        = "success";
            $x            = $query->where('status', 33)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 34) {
            $status_name  = "Return Run Rider Reject";
            $class        = "success";
            $x            = $query->where('status', 34)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 35) {
            $status_name  = "Return Run Complete";
            $class        = "success";
            $x            = $query->where('status', 35)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        } elseif ($status == 36) {
            $status_name  = "Return Complete";
            $class        = "success";
            $x            = $query->where('status', 36)->first();
            $time         =  Carbon::parse($x?->date . ' ' . $x?->time)->format('d-m-Y h:i A');
        }
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class,
        'time'         => $time
    ];
}


function returnParcelStatusNameForBranch($status, $delivery_type, $payment_type, $parcel = null)
{
    $status_name    = "";
    $class          = "";

    $returnableParcel   = ($delivery_type == 2 || $delivery_type == 4);
    $deliveredParcel    = ($delivery_type == 1 || $delivery_type == 2);

    if ($status == 1) {
        $status_name  = "Pick Requested";
        $class        = "success";
    } elseif ($status == 2) {
        $status_name  = "Pick Request Hold";
        $class        = "warning";
    } elseif ($status == 3) {
        // $status_name  = "Parcel Cancel";
        $status_name  = "Deleted";
        $class        = "danger";
    } elseif ($status == 4) {
        $status_name  = "Pick Request Reschedule";
        $class        = "warning";
    } elseif ($status == 5) {
        $status_name  = "Pick Run Create";
        $class        = "success";
    } elseif ($status == 6) {
        $status_name  = "Pick Run Start";
        $class        = "success";
    } elseif ($status == 7) {
        $status_name  = "Pick Run Cancel";
        $class        = "warning";
    } elseif ($status == 8) {
        $status_name  = "Pick Rider Accept";
        $class        = "success";
    } elseif ($status == 9) {
        $status_name  = "Pick Rider Declined";
        $class        = "warning";
    } elseif ($status == 10) {
        $status_name  = "Picked Up by Rider";
        $class        = "success";
    } elseif ($status == 11) {
        $status_name  = "Picked Up";
        $class        = "success";
    } elseif ($status == 12) {
        $status_name  = "Transfer Run Create";
        $class        = "secondary";
    } elseif ($status == 13) {
        $status_name  = "Transfer Run Cancel";
        $class        = "warning";
    } elseif ($status == 14) {
        $status_name  = "At Kitchen";
        $class        = "info";
    } elseif ($status == 15) {
        $status_name  = "Transfer Declined";
        $class        = "warning";
    } elseif ($status == 16) {
        $status_name  = "Rider Assign Run Create";
        $class        = "success";
    } elseif ($status == 17) {
        $status_name  = "Rider Assign Run Start";
        $class        = "success";
    } elseif ($status == 18) {
        $status_name  = "Rider Assign Run Cancel";
        $class        = "warning";
    } elseif ($status == 19) {
        $status_name  = "Rider Accept for Delivery";
        $class        = "success";
    } elseif ($status == 20) {
        $status_name  = "Rider Declined";
        $class        = "warning";
    } elseif ($status == 21) {
        $status_name  = "Rider Requested for Delivery";
        $class        = "success";
    } elseif ($status == 22) {
        $status_name  = "Rider Requested for Partial Delivery";
        $class        = "success";
    } elseif ($status == 23) {
        $status_name  = "Rider Requested for Reschedule";
        $class        = "success";
    } elseif ($status == 24) {
        $status_name  = "Rider Requested for Cancel";
        $class        = "warning";
    } elseif ($status >= 25 && $delivery_type == 1) {
        $status_name  = "Delivered";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 2) {
        $status_name  = "Partial Delivery";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 3) {
        $status_name  = "Reschedule Delivery";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 4) {
        $status_name  = "Cancelled";
        $class        = "danger";

        $x = $parcel;

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Cancelled";
        }
    } elseif ($status == 26 && $delivery_type == 4) {
        $status_name  = "Return Transfer Run Create";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Transfer Run Create";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Transfer Run Create";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Transfer Run Create";
        }
    } elseif ($status == 27 && $delivery_type == 4) {
        $status_name  = "Return Transfer Run Cancel";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Transfer Run Cancel";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Transfer Run Cancel";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Transfer Run Cancel";
        }
    } elseif ($status == 28 && $delivery_type == 4) {
        $status_name  = "Return Transfer Received";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Transfer Received";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Transfer Received";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Transfer Received";
        }
    } elseif ($status == 29 && $delivery_type == 4) {
        $status_name  = "Return Transfer Declined";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return Transfer Declined";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return Transfer Declined";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Transfer Declined";
        }
    } elseif ($status == 30 && $delivery_type == 4) {
        $status_name  = "Return Assign Run Create";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Run Create";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Run Create";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Run Create";
        }
    } elseif ($status == 31 && $delivery_type == 4) {
        $status_name  = "Return Assign Run Start";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Run Start";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Run Start";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Run Start";
        }
    } elseif ($status == 32 && $delivery_type == 4) {
        $status_name  = "Return Assign Declined";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Declined";
        }
    } elseif ($status == 33 && $delivery_type == 4) {
        $status_name  = "Return Assign Accept";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Accept";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Accept";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Accept";
        }
    } elseif ($status == 34 && $delivery_type == 4) {
        $status_name  = "Return Assign Declined";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assign Declined";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assign Declined";
        }
    } elseif ($status == 35 && $delivery_type == 4) {
        $status_name  = "Return Confirmed";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return Confirmed";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return Confirmed";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Confirmed";
        }
    } elseif ($status == 36 && $delivery_type == 4) {
        $status_name  = "Returned";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel->parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Returned";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Returned";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Returned";
        }
    }

    if ($status == 0 && isset($parcel) && $parcel?->is_push) {
        $status_name  = "In Review API";
        $class        = "success";
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class
    ];
}


function returnParcelStatusNameForMerchant($status, $delivery_type, $payment_type, $parcel_invoice = null)
{
    $status_name    = "";
    $class          = "";

    $returnableParcel   = ($delivery_type == 2 || $delivery_type == 4);
    $deliveredParcel    = ($delivery_type == 1 || $delivery_type == 2);

    if ($status == 1) {
        $status_name  = "Pick Requested";
        $class        = "warning";
    } elseif ($status == 2) {
        $status_name  = "Pick Request Hold";
        $class        = "warning";
    } elseif ($status == 3) {
        // $status_name  = "Parcel Cancel";
        $status_name  = "Deleted";
        $class        = "danger";
    } elseif ($status == 4) {
        $status_name  = "Pick Request Reschedule";
        $class        = "warning";
    } elseif ($status == 5) {
        $status_name  = "Pickup Processing";
        $class        = "success";
    } elseif ($status == 6) {
        $status_name  = "Pickup Processing";
        $class        = "success";
    } elseif ($status == 7) {
        $status_name  = "Pickup Processing";
        $class        = "warning";
    } elseif ($status == 8) {
        $status_name  = "Pick Rider On Way";
        $class        = "success";
    } elseif ($status == 9) {
        $status_name  = "Pickup Processing";
        $class        = "warning";
    } elseif ($status == 10) {
        $status_name  = "Picked Up by Rider";
        $class        = "success";
    } elseif ($status == 11) {
        $status_name  = "Picked Up";
        $class        = "primary";
    } elseif ($status == 12) {
        $status_name  = "On way to Kitchen";
        $class        = "success";
    } elseif ($status == 13) {
        $status_name  = "On way to Kitchen";
        $class        = "warning";
    } elseif ($status == 14) {
        $status_name  = "At Kitchen";
        $class        = "info";
    } elseif ($status == 15) {
        $status_name  = "On way to Kitchen";
        $class        = "warning";
    } elseif ($status == 16) {
        $status_name  = "At Kitchen";
        $class        = "success";
    } elseif ($status == 17) {
        $status_name  = "Rider Assigned";
        $class        = "success";
    } elseif ($status == 18) {
        $status_name  = "At Kitchen";
        $class        = "warning";
    } elseif ($status == 19) {
        $status_name  = "On the way to Delivery";
        $class        = "secondary";
    } elseif ($status == 20) {
        $status_name  = "At Kitchen";
        $class        = "warning";
    } elseif ($status == 21) {
        $status_name  = "Delivery Approval Pending";
        $class        = "success";
    } elseif ($status == 22) {
        $status_name  = "Partial Approval Pending";
        $class        = "success";
    } elseif ($status == 23) {
        $status_name  = "Reschedule Approval Pending";
        $class        = "success";
    } elseif ($status == 24) {
        $status_name  = "Cancel Approval Pending";
        $class        = "warning";
    } elseif ($status == 25 && $delivery_type == 1) {
        $status_name  = "Delivered";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 2) {
        $status_name  = "Partial Delivered";
        $class        = "success";
    } elseif ($status == 25 && $delivery_type == 3) {
        $status_name  = "Rescheduled";
        $class        = "warning";
    } elseif ($status == 25 && $delivery_type == 4) {
        $status_name  = "Cancelled";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Cancelled";
        }

        $class        = "danger";
    } elseif ($status == 26 && $delivery_type == 4) {
        $status_name  = "Return To Kitchen";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return to Kitchen";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return to Kitchen";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return to Kitchen";
        }
    } elseif ($status == 27 && $delivery_type == 4) {
        $status_name  = "Return To Kitchen";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return to Kitchen";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return to Kitchen";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return to Kitchen";
        }
    } elseif ($status == 28 && $delivery_type == 4) {
        $status_name  = "Return At Kitchen";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return At Kitchen";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange At Kitchen";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return At Kitchen";
        }
    } elseif ($status == 29 && $delivery_type == 4) {
        $status_name  = "Return Processing";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return Processing";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return Processing";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Processing";
        }
    } elseif ($status == 30 && $delivery_type == 4) {
        $status_name  = "Assigned For Return";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assigned For Return";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assigned For Return";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assigned For Return";
        }
    } elseif ($status == 31 && $delivery_type == 4) {
        $status_name  = "Assigned For Return";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Assigned For Return";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Assigned For Return";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Assigned For Return";
        }
    } elseif ($status == 32 && $delivery_type == 4) {
        $status_name  = "Return Processing";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return Processing";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return Processing";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return Processing";
        }
    } elseif ($status == 33 && $delivery_type == 4) {
        $status_name  = "Return On Way";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return On Way";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return On Way";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return On Way";
        }
    } elseif ($status == 34 && $delivery_type == 4) {
        $status_name  = "Return On Way";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Return On Way";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Return On Way";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Return On Way";
        }
    } elseif ($status == 35 && $delivery_type == 4) {
        $status_name  = "Returned";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Returned";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Returned";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Returned";
        }
    } elseif ($status == 36 && $delivery_type == 4) {
        $status_name  = "Returned";
        $class        = "danger";

        $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Returned";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Returned";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Returned";
        }
    }

    $x = Parcel::where('parcel_invoice', $parcel_invoice)->first();

    if ($status == 0 && isset($x) && $x?->is_push) {
        $status_name  = "In Review API";
        $class        = "success";
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class
    ];
}


function returnPaymentStatusForMerchant($status, $delivery_type, $payment_type)
{
    $status_name    = "";
    $class          = "";

    if ($status >= 25 && ($delivery_type == 1 || $delivery_type == 2 || $delivery_type == 4) && $payment_type) {
        if ($payment_type == 1) {
            $status_name  = "Unpaid";
            $class        = "primary";
        } elseif ($payment_type == 2) {
            $status_name  = "Unpaid";
            $class        = "primary";
        } elseif ($payment_type == 3) {
            $status_name  = "Unpaid";
            $class        = "warning";
        } elseif ($payment_type == 4) {
            $status_name  = "Unpaid";
            $class        = "primary";
        } elseif ($payment_type == 5) {
            $status_name  = "Paid ";
            // $status_name  = "Accounts Payment Done";
            $class        = "success";
        } elseif ($payment_type == 6) {
            $status_name  = "Reject";
            $class        = "warning";
        }
    }

    return [
        'status_name'   => $status_name,
        'class'         => $class
    ];
}


// -------------------------------
function returnParcelLogStatusNameForAdmin($parcelLog, $delivery_type, $parcel = null)
{
    $status         = $parcelLog->status;
    $to_user        = "";
    $from_user      = "";
    $status_name    = "";
    $class          = "";
    $sub_title       = "";

    if ($status == 1) {
        $status_name  = "Order has been Placed by Merchant";
        //$status_name  = "Pickup Request";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->merchant) {
            $to_user    = "Merchant : " . $parcelLog->merchant->company_name;
            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $from_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
        }
    } elseif ($status == 2) {
        return [];
        $status_name  = "Cash Deposited";
        //$status_name  = "Parcel Hold";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->merchant) {
            $to_user    = "Merchant : " . $parcelLog->merchant->company_name;
        }
    } elseif ($status == 3) {
        // $status_name  = "Parcel Cancel";
        $status_name  = "Deleted";
        $class        = "danger";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->merchant) {
            $to_user    = "Merchant : " . $parcelLog->merchant->company_name;
        }
    } elseif ($status == 4) {
        return [];
        $status_name  = "Re-schedule Pickup";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_rider) {
            if (!empty($parcelLog->pickup_rider)) {
                $to_user    = "Pickup Rider : " . $parcelLog->pickup_rider->name;
            }
            if (!empty($parcelLog->pickup_branch)) {
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
                //$to_user    .= "Pickup Branch : ".$parcelLog->pickup_branch->name;
            }
        }
    } elseif ($status == 5) {
        return [];
        $status_name  = "Payment has been disbursed to Merchant";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_branch) {
            if (!empty($parcelLog->pickup_branch)) {

                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
                //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
            }
        }
    } elseif ($status == 6) {
        return [];
        $status_name  = "Rider Assign For Pick";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_branch_user) {
            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
            //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
        }
    } elseif ($status == 7) {
        return [];
        $status_name  = "Pickup Run Cancel";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_branch_user) {
            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
            //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
        }
    } elseif ($status == 8) {
        $sub_title = $parcelLog?->delivery_rider?->name . ' ' . $parcelLog->delivery_rider?->r_id . '-' . $parcelLog->delivery_rider?->branch?->name . '-' . $parcelLog->delivery_rider?->contact_number;

        $status_name  = "Rider Assigned for Pickup";
        //$status_name  = "On the way to Pickup";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_rider) {
            $to_user    = "Pickup Rider : " . $parcelLog->pickup_rider->name;
        }
    } elseif ($status == 9) {
        return [];
        $status_name  = "Pickup Rider Reject";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_rider) {
            $to_user    = "Pickup Rider : " . ($parcelLog->pickup_rider) ? $parcelLog->pickup_rider->name : "";
        }
    } elseif ($status == 10) {
        $status_name  = "Parcel Handover";
        //$status_name  = "Pickup Rider Complete Task";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_rider) {
            $to_user    = (!empty($parcelLog->pickup_rider)) ? "Pickup Rider : " . $parcelLog->pickup_rider->name : '';

            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $to_user  .= (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
            //$to_user    .= (!empty($parcelLog->pickup_branch)) ? "Pickup Branch : ".$parcelLog->pickup_branch->name : '';
        }
    } elseif ($status == 11) {
        $status_name  = "Parcel Received";
        //$status_name  = "Picked Up";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_branch_user) {
            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;
            //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
        }
    } elseif ($status == 12) {
        //$status_name  = "Branch Transfer";

        $sub_title = 'Transfer ID - ' . $parcelLog->parcel?->deliveryBranchTransferDetails?->delivery_branch_transfer;
        $status_name  = "Sent to " . $parcelLog?->pickup_branch_user?->name . " Delivery ";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_branch_user) {
            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;

            $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (" . $parcelLog->delivery_branch_user->name . ")" : " (General)";
            $from_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : " . $parcelLog->delivery_branch->name : "Default";
            $from_user   .= $dbranch_user;
        }
    } elseif ($status == 13) {
        return [];
        $status_name  = "Branch Transfer Cancel";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->pickup_branch_user) {
            $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (" . $parcelLog?->pickup_branch_user?->name . ")" : " (General)";
            $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : " . $parcelLog->pickup_branch->name . $branch_user : "Default" . $branch_user;

            //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
        }
    } elseif ($status == 14) {
        $status_name  = "Received for delivery";
        //$status_name  = "Branch Transfer Complete";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch_user) {

            $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (" . $parcelLog->delivery_branch_user->name . ")" : " (General)";
            $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : " . $parcelLog->delivery_branch->name : "Default";
            $to_user   .= $dbranch_user;

            //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
        }
    } elseif ($status == 15) {
        return [];
        $status_name  = "Delivery Branch Reject";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch_user) {
            $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (" . $parcelLog->delivery_branch_user->name . ")" : " (General)";
            $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : " . $parcelLog->delivery_branch->name : "Default";
            $to_user   .= $dbranch_user;

            //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
        }
    } elseif ($status == 16) {
        return [];
        $status_name  = "Delivery Run Create";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch_user) {
            $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (" . $parcelLog->delivery_branch_user->name . ")" : " (General)";
            $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : " . $parcelLog->delivery_branch->name : "Default";
            $to_user   .= $dbranch_user;

            //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
        }
    } elseif ($status == 17) {
        //$status_name  = "Delivery Run Start";
        $status_name  = "Rider Assigned for Delivery";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch_user) {
            $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (" . $parcelLog->delivery_branch_user->name . ")" : " (General)";
            $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : " . $parcelLog->delivery_branch->name : "Default";
            $to_user   .= $dbranch_user;

            //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
        }
    } elseif ($status == 18) {
        return [];
        $status_name  = "Delivery Run Cancel";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch_user) {
            $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (" . $parcelLog->delivery_branch_user->name . ")" : " (General)";
            $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : " . $parcelLog->delivery_branch->name : "Default";
            $to_user   .= $dbranch_user;

            //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
        }
    } elseif ($status == 19) {
        $status_name  = "On the Way to Delivery";
        $class        = "success";
        $sub_title = $parcelLog?->delivery_rider?->name . ' ' . $parcelLog->delivery_rider?->r_id . '-' . $parcelLog->delivery_rider?->branch?->name . '-' . $parcelLog->delivery_rider?->contact_number;

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 20) {
        return [];
        $status_name  = "Delivery Rider Reject";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 21) {
        $status_name  = "Successfully Delivered";
        $sub_title = 'COD Collected - ' . $parcelLog->parcel?->customer_collect_amount . 'TK';
        // $status_name  = "Delivery Rider Complete Delivery";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 22) {
        return [];
        $status_name  = "Partial Delivered";
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 23) {
        //$status_name  = "Rescheduled";
        $status_name  = "Hold Requested";
        $sub_title = $parcelLog->note ?? 'N/A';
        $class        = "success";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name . " (Reschedule Date : " . \Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') . ")";
        }
    } elseif ($status == 24 && $parcelLog->delivery_type == 2) {
        $status_name  = "Partial Delivery Requested by Rider";
        $sub_title = $parcelLog->note ?? 'N/A';
        //$status_name  = "Exchange Product Received by Rider";

        //$status_name  = "Delivery Rider Return";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 24 && $parcelLog->delivery_type == 4) {
        $status_name  = "Cancel Requested by Rider";
        $sub_title = $parcelLog->note ?? 'N/A';
        //$status_name  = "Exchange Product Received by Rider";

        //$status_name  = "Delivery Rider Return";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 24) {
        $status_name  = "Cancel Requested";
        $sub_title = $parcelLog->note ?? 'N/A';
        //$status_name  = "Exchange Product Received by Rider";

        //$status_name  = "Delivery Rider Return";
        $class        = "warning";

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        }
    } elseif ($status == 25 && $parcelLog->delivery_type == 1) {
        $sub_title = $parcelLog->note ?? 'N/A';
        $status_name  = "Delivered";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch) {
            $to_user    = !empty($parcelLog->delivery_branch) ? "Delivery Branch : " . $parcelLog?->delivery_branch?->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Cancelled";
        }
    } elseif ($status == 25 && $parcelLog->delivery_type == 2) {
        // if (!empty($parcelLog->admin)) {
        //     $to_user    = "Admin : " . $parcelLog->admin->name;
        // } elseif ($parcelLog->delivery_branch) {
        //     $to_user    = !empty($parcelLog->delivery_branch) ? "Delivery Branch : " . $parcelLog?->delivery_branch?->name : "";
        // }

        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch) {
            $to_user    = $parcelLog?->delivery_branch?->name ?? "N/A";
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        } elseif ($parcelLog->pickup_branch) {
            $to_user    = "Pickup Branch : " . $parcelLog?->pickup_branch?->name;
        }

        $sub_title = $parcelLog->note ?? 'N/A';
        $status_name  = "Partial Delivered";
        $class        = "success";

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Cancelled";
        }
    } elseif ($status == 25 && $parcelLog->delivery_type == 3) {
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch) {
            $to_user    = !empty($parcelLog->delivery_branch) ? "Delivery Branch : " . $parcelLog?->delivery_branch?->name : "";
        }

        $sub_title = $parcelLog->note ?? 'N/A';
        $status_name  = "Hold Parcel Received";
        $sub_title = $parcelLog->note ?? 'N/A';
        $class        = "success";
    } elseif ($status == 25 && $parcelLog->delivery_type == 4) {
        $sub_title = $parcelLog->note ?? 'N/A';
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->delivery_branch) {
            $to_user    = $parcelLog?->delivery_branch?->name ?? "N/A";
        } elseif ($parcelLog->delivery_rider) {
            $to_user    = "Delivery Rider : " . $parcelLog?->delivery_rider?->name;
        } elseif ($parcelLog->pickup_branch) {
            $to_user    = "Pickup Branch : " . $parcelLog?->pickup_branch?->name;
        }

        $x = $parcelLog->parcel;

        $status_name  = "Cancelled";

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Collected";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Cancelled";
        }

        $class        = "success";
    } elseif ($status == 25 && ($parcelLog->delivery_type == 1  || $parcelLog->delivery_type == 2 || $parcelLog->delivery_type == 4) && $parcelLog->parcel->payment_type == 2) {
        $to_user    = !empty($parcelLog->delivery_branch) ? "Delivery Branch : " . $parcelLog?->delivery_branch?->name : "";
        $x = $parcelLog->parcel->parcel_invoice;

        $status_name  = "Cash Deposited to Accounts";

        $class        = "success";
    } elseif ($status == 25 && ($parcelLog->delivery_type == 1  || $parcelLog->delivery_type == 2 || $parcelLog->delivery_type == 4) && $parcelLog->parcel->payment_type == 5) {
        $to_user    = !empty($parcelLog->delivery_branch) ? "Delivery Branch : " . $parcelLog?->delivery_branch?->name : "";
        $x = $parcelLog->parcel->parcel_invoice;
        $sub_title = 'Payment Invoice ID ' . $parcelLog->parcel?->merchantDeliveryPayment?->merchant_payment_invoice;
        $status_name  = "Payment has been disbursed to Merchant";

        $class        = "success";
    } elseif ($status == 25) {
        return [];
        $status_name  = "Delivery Rider Run Complete(unknown)";
        $class        = "success";
    } elseif ($status == 26) {
        return [];
        $status_name  = "Return Branch Assign";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Branch Assign";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Branch Assign";
        }
    } elseif ($status == 27) {
        return [];
        $status_name  = "Return Branch Assign Cancel";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Branch Assign Cancel";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Branch Assign Cancel";
        }
    } elseif ($status == 28) {
        return [];
        $status_name  = "Return Branch Assign Received";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Branch Assign Received";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Branch Assign Received";
        }
    } elseif ($status == 29) {
        return [];
        $status_name  = "Return Branch Assign Reject";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Branch Assign Reject";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Branch Assign Reject";
        }
    } elseif ($status == 30) {
        return [];
        $status_name  = "Return Branch Run Create";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Branch Run Create";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Branch Run Create";
        }
    } elseif ($status == 31) {
        $status_name  = "Return Assigned to Rider";
        //$status_name  = "Exchange Assigned to Rider";

        //$status_name  = "Return Branch Run Start";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Assigned to Rider";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Assigned to Rider";
        }
    } elseif ($status == 32) {
        return [];
        $status_name  = "Return Branch Run Cancel";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Branch Run Cancel";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Branch Run Cancel";
        }
    } elseif ($status == 33) {
        return [];
        $status_name  = "Return Rider Accept";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Rider Accept";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Rider Accept";
        }
    } elseif ($status == 34) {
        return [];
        $status_name  = "Return Rider Reject";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Rider Reject";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Rider Reject";
        }
    } elseif ($status == 35) {
        return [];
        $status_name  = "Return Rider Complete";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_rider) {
            $to_user    = "Return Rider : " . $parcelLog->return_rider->name;
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'no') {
            $status_name  = "Partial Return Rider Complete";
        } elseif ($x->suborder && $x->exchange == 'yes') {
            $status_name  = "Exchange Return Rider Complete";
        }
    } elseif ($status == 36) {
        $status_name  = "Return Handover to Merchant";
        //$status_name  = "Exchange Handover to Merchant";

        //$status_name  = "Return Branch Run Complete";
        $class        = "success";
        if (!empty($parcelLog->admin)) {
            $to_user    = "Admin : " . $parcelLog->admin->name;
        } elseif ($parcelLog->return_branch) {
            $to_user    = !empty($parcelLog->return_branch) ? "Return Branch : " . $parcelLog->return_branch->name : "";
        }

        $x = $parcelLog->parcel;

        if ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 1) {
            $status_name  = "Exchange Returned";
        } elseif ($x->suborder && $x->exchange == 'yes' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial & Exchange Returned";
        } elseif ($x->suborder && $x->exchange == 'no' && $x->parent_delivery_type == 2) {
            $status_name  = "Partial Returned";
        }
    } else if ($status == 100) {
        $status_name  = $parcelLog->note ?? 'N/A';
        $to_user  = $parcelLog->updated_by;
    } elseif ($status == 0 && isset($parcel) && $parcel?->is_push) {
        $status_name  = "In Review API";
        $class        = "success";
        $to_user      = "Merchant : " . $parcelLog->merchant->company_name;
    }

    // if ($parcelLog->parcel->payment_type == 5) {
    //     $sub_title = 'Payment Invoice ID ' . $parcelLog->parcel?->merchantDeliveryPayment?->merchant_payment_invoice;
    // } else {
    //     $sub_title =  '----------------';
    // }

    return [
        'to_user'       => $to_user,
        'from_user'     => $from_user,
        'status_name'   => $status_name,
        'class'         => $class,
        'sub_title'      => $sub_title
    ];
}

function getDifference($model)
{
    $changed = $model->getDirty();

    return json_encode([
        'before' => array_intersect_key($model->getOriginal(), $changed),
        'after'  => $changed
    ], true);

    return json_encode([
        'before' => $model->id,
        'after'  => ''
    ], true);
}

function createActivityLog($difference, $parcel, $updated_by)
{
    //$updated_by = 'User not found';

    // if (auth()->guard('admin')->check()) {
    //     $updated_by = auth()->guard('admin')->user()->name;
    // } elseif (auth()->guard('branch')->check()) {
    //     $updated_by = auth()->guard('branch')->user()->name . '-' . auth()->guard('branch')->user()->branch->name;
    // } elseif (auth()->guard('merchant')->check()) {
    //     $updated_by = auth()->guard('merchant')->user()->name;
    // } elseif (auth()->guard('warehouse')->check()) {
    //     $updated_by = auth()->guard('warehouse')->user()->name;
    // }

    $data = [
        'parcel_id' => $parcel->id,
        // 'admin_id' => auth()->guard('admin')->user()->id,
        'date' => date('Y-m-d'),
        'time' => date('H:i:s'),
        'status' => 100,
        'note' => $difference,
        'updated_by' => $updated_by,
    ];

    ParcelLog::create($data);
}
