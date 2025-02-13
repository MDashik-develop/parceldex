<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\PathaoOrder;
use App\Models\PathaoOrderDetail;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HandsontableDataController extends Controller
{

    private $base_url;
    private $client_id;
    private $client_secret;
    private $username;
    private $password;

    public function __construct()
    {
        $this->base_url = env('PATHAO_BASE_URL', 'https://courier-api-sandbox.pathao.com');
        $this->client_id = env('PATHAO_CLIENT_ID', 'your_client_id');
        $this->client_secret = env('PATHAO_CLIENT_SECRET', 'your_client_secret');
        $this->username = env('PATHAO_USERNAME', 'your_username');
        $this->password = env('PATHAO_PASSWORD', 'your_password');
    }

    public function getAccessToken()
    {
        $cacheKey = 'access_token';
        $expiresInKey = 'access_token_expires_in';

        if (Cache::has($cacheKey) && Cache::has($expiresInKey)) {
            $expiresIn = Cache::get($expiresInKey);

            if ($expiresIn > now()->timestamp) {
                return Cache::get($cacheKey);
            }
        }

        $response = Http::post("{$this->base_url}/aladdin/api/v1/issue-token", [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'password',
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $response->throw();

        $data = $response->json();

        $accessToken = $data['access_token'] ?? null;
        $expiresIn = now()->timestamp + ($data['expires_in'] ?? 0);

        if ($accessToken) {
            Cache::put($cacheKey, $accessToken, now()->addSeconds($expiresIn));
            Cache::put($expiresInKey, $expiresIn, now()->addSeconds($expiresIn));
        }

        return $accessToken;
    }

    public function merchantStore()
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get access token'], 401);
        }

        $data = $this->getStoreList($accessToken);

        return response()->json($data);
    }

    private function getStoreList($accessToken)
    {

        $cacheKey = 'store-list';
        $cacheTtl = 60; // cache for 1 hour

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->get("{$this->base_url}/aladdin/api/v1/stores");

            $response->throw();

            $storeData = json_decode($response->getBody(), true);

            Cache::put($cacheKey, $storeData, $cacheTtl);

            return $storeData;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return null;
        }
    }

    private function getStoreId($list, $name)
    {
        foreach ($list['data']['data'] as $v) {
            if ($v['store_name'] == $name) {
                return $v['city_id'];
            }
        }

        return null;
    }

    //
    public function getParcelInvoices()
    {
        $branch_user = auth()->guard('branch')->user();
        $branch_id = $branch_user->branch->id;
        $branch_type = $branch_user->branch->type;

        if ($branch_type == 1) {
            $where_condition = "status NOT IN (2,3,4)";
        } else {
            $where_condition = "sub_branch_id = {$branch_id} and status NOT IN (2,3,4)";
        }

        // $data = Parcel::get('parcel_invoice')->pluck('parcel_invoice');

        $data = Parcel::where(function ($query) use ($branch_id) {
            $query->where('delivery_branch_id', $branch_id)
                ->orWhere('return_branch_id', $branch_id)
                ->orWhere(function ($subQuery) use ($branch_id) {
                    $subQuery->where('status', '<=', 11)
                        ->where('pickup_branch_id', $branch_id);
                });
        })
            ->whereRaw($where_condition)
            ->get('parcel_invoice')
            ->pluck('parcel_invoice');

        return response()->json($data);
    }

    public function getOrderDetails(Request $request)
    {
        $parcel = Parcel::where('parcel_invoice', $request->parcel_invoice)->first();

        return $arrayData = [
            "recipientName" => $parcel->customer_name,
            "recipientPhone" => $parcel->customer_contact_number,
            "recipientAddress" => $parcel->customer_address,
            "amountToCollect" => intval($parcel->total_collect_amount),
            "itemQuantity" => 1,
            "itemWeight" => floatval($parcel->weight_package->name),
            "itemDescription" => $parcel->product_details,
            "specialInstruction" => $parcel->parcel_note,
        ];
    }

    public function getCities()
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return response()->json(['error' => 'Failed to get access token'], 401);
        }

        $data = $this->getCityList($accessToken);

        return response()->json($data);
    }

    /**
     * Retrieves the city list from cache or API.
     *
     * @param string $accessToken
     * @return array|null
     */
    private function getCityList($accessToken)
    {
        $cacheKey = 'city-list';
        $cacheTtl = 60; // cache for 1 hour

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->get("{$this->base_url}/aladdin/api/v1/city-list");

            $response->throw();

            $cityList = json_decode($response->getBody(), true);

            Cache::put($cacheKey, $cityList, $cacheTtl);

            return $cityList;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return null;
        }
    }

    /**
     * Retrieves the city ID from the city list.
     *
     * @param array $cityList
     * @param string $cityName
     * @return int|null
     */
    private function getCityId($cityList, $cityName)
    {
        foreach ($cityList['data']['data'] as $city) {
            if ($city['city_name'] == $cityName) {
                return $city['city_id'];
            }
        }

        return null;
    }

    public function getZones(Request $request)
    {
        $access_token = $this->getAccessToken();

        if (!$access_token) {
            return response()->json(['error' => 'Failed to get access token'], 401);
        }

        $city_list = $this->getCityList($access_token);

        $city_id = $this->getCityId($city_list, $request->input('city_name'));

        $data = $this->getZoneList($access_token, $city_id);
        return response()->json($data);
    }

    private function getZoneList($access_token, $city_id)
    {
        $cacheKey = "zone-list-$city_id";
        $cacheTtl = 60; // cache for 1 hour

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json',
            ])->get("{$this->base_url}/aladdin/api/v1/cities/{$city_id}/zone-list");

            $response->throw();

            $zoneList = json_decode($response->getBody(), true);

            Cache::put($cacheKey, $zoneList, $cacheTtl);

            return $zoneList;
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return null;
        }
    }

    private function getZoneId($zoneList, $zoneName)
    {
        foreach ($zoneList['data']['data'] as $zone) {
            if ($zone['zone_name'] == $zoneName) {
                return $zone['zone_id'];
            }
        }

        return null;
    }

    public function areaList(Request $request)
    {
        $access_token = $this->getAccessToken();

        if (!$access_token) {
            return response()->json(['error' => 'Failed to get access token'], 401);
        }

        $city_list = $this->getCityList($access_token);

        $city_id = $this->getCityId($city_list, $request->input('city_name'));

        $zone_list = $this->getZoneList($access_token, $city_id);

        $zone_id = $this->getZoneId($zone_list, $request->input('zone_name'));

        $data = $this->getAreaList($access_token, $zone_id);
        return response()->json($data);
    }

    private function getAreaList($accessToken, $zoneId)
    {
        $cacheKey = "area-list-$zoneId";
        $cacheTtl = 60; // cache for 1 hour

        // Check if the response is cached
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->get("{$this->base_url}/aladdin/api/v1/zones/{$zoneId}/area-list");

            $response->throw();

            // Cache the response
            Cache::put($cacheKey, json_decode($response->getBody(), true), $cacheTtl);

            return json_decode($response->getBody(), true);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Handle the exception
            return null;
        }
    }

    private function getAreaId($areaList, $areaName)
    {
        foreach ($areaList['data']['data'] as $zone) {
            if ($zone['area_name'] == $areaName) {
                return $zone['area_id'];
            }
        }

        return null;
    }

    public function confirmOrders(Request $request)
    {
        $orderData = [];
        $access_token = $this->getAccessToken();
        if (!$access_token) {
            return response()->json(['error' => 'Failed to get access token'], 401);
        }
        foreach ($request->data as $row => $value) {
            if ($value[2]) {
                // city name to id
                $city_list = $this->getCityList($access_token);
                $city_id = $this->getCityId($city_list, $value[6]);

                // zone name to id
                $zone_list = $this->getZoneList($access_token, $city_id);
                $zone_id = $this->getZoneId($zone_list, $value[7]);

                // area name to id
                $area_list = $this->getAreaList($access_token, $zone_id);
                $area_id = $this->getAreaId($area_list, $value[8]);

                $store_list = $this->getStoreList($access_token);
                $store_id = $this->getStoreId($store_list, $value[1]);

                $order = [
                    "delivery_type" => 48,
                    "item_type" => $value[0] === 'Parcel' ? 2 : 1,
                    "store_id" => $store_id,
                    "merchant_order_id" => $value[2],
                    "recipient_name" => $value[3],
                    "recipient_phone" => $value[4],
                    "recipient_address" => $value[5],
                    "recipient_city" => $city_id,
                    "recipient_zone" => $zone_id,
                    "amount_to_collect" => $value[9],
                    "item_quantity" => $value[10],
                    "item_weight" => $value[11],
                    "item_description" => $value[12],
                    "special_instruction" => $value[13],
                ];

                if ($area_id) {
                    $order['recipient_area'] = $area_id;
                } else {
                    $order['recipient_area'] = 0;
                }

                $orderData[] = $order;
            }
        }

        if (!$orderData) {
            return response()->json(['error' => 'No parcel to confirm'], 400);
        }

        DB::beginTransaction();

        try {

            $branch_id = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;
            $rider_id = 1;

            // run rider create
            $riderRun = RiderRun::create([
                'run_invoice' => $this->returnUniqueRiderRunInvoice(),
                'rider_id' => $rider_id,
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'create_date_time' => now(),
                'total_run_parcel' => count($orderData),
                'note' => $request->input('note'),
                'run_type' => 2,
                'status' => 1,
            ]);

            foreach ($orderData as $parcelData) {

                $parcel = Parcel::where('parcel_invoice', $parcelData['merchant_order_id'])->first();

                // create pathao order
                $pathaoOrderCreate = create_pathao_order($access_token, $parcelData['recipient_city'], $parcelData['recipient_zone'], $parcelData['recipient_area'], $parcel);

                if ($pathaoOrderCreate['code'] == 200) {

                    // rider run details create
                    $riderRunDetail = RiderRunDetail::create([
                        'rider_run_id' => $riderRun->id,
                        'parcel_id' => $parcel->id,
                    ]);

                    $pathaoOrder = PathaoOrder::create([
                        'order_no' => $this->returnUniquePathaoOrderNo(),
                        'city_id' => $parcelData['recipient_city'],
                        'zone_id' => $parcelData['recipient_zone'],
                        'area_id' => $parcelData['recipient_zone'],
                        'branch_id' => $branch_id,
                        'branch_user_id' => $branch_user_id,
                        'date' => now(),
                        'time' => date('H:i:s'),
                        'total_parcel' => 1,
                        'note' => $request->input('note'),
                    ]);

                    // create pathao order details
                    PathaoOrderDetail::create([
                        'pathao_order_id' => $pathaoOrder->id,
                        'parcel_id' => $parcel->id,
                        'rider_run_detail_id' => $riderRunDetail->id,
                        'consignment_id' => $pathaoOrderCreate['data']['consignment_id'],
                        'merchant_order_id' => $pathaoOrderCreate['data']['merchant_order_id'],
                    ]);

                    $parcel = Parcel::where('id', $parcel->id)->first();

                    $parcel->update([
                        'status' => 16,
                        'parcel_date' => $request->input('date'),
                        'is_pathao' => 1,
                        'pathao_status' => "Pending",
                        'delivery_rider_id' => $rider_id,
                        'delivery_rider_accept_date' => date('Y-m-d'),
                        'delivery_branch_id' => $branch_id,
                        'delivery_branch_user_id' => $branch_user_id,
                    ]);

                    ParcelLog::create([
                        'parcel_id' => $parcel->id,
                        'delivery_rider_id' => $rider_id,
                        'delivery_branch_id' => $branch_id,
                        'delivery_branch_user_id' => $branch_user_id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 16,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                } else {
                    throw new Exception("Error Processing Request", 1);
                }
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return [
            'success' => true,
            'message' => 'Orders confirmed successfully',
            // 'data' => $response->json(),
        ];

        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer ' . $access_token,
        // ])->post($this->base_url . '/aladdin/api/v1/orders', ['orders' => $orderData]);

        // if ($response->successful()) {
        //     // Handle successful response
        //     return [
        //         'success' => true,
        //         'message' => 'Orders confirmed successfully',
        //         'data' => $response->json(),
        //     ];
        // } else {
        //     // Handle failed response

        //     return [
        //         'success' => false,
        //         'message' => 'Failed to confirm orders',
        //         'body' => $response->body(),
        //         'data' => $orderData
        //     ];
        // }
    }
}
