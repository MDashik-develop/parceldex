<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    //
    public function handle(Request $request)
    {
        // Define the expected secret
        $expectedSecret = 'f3992ecc-59da-4cbe-a049-a13da2018d51';

        // Check if the request contains the expected secret header
        if ($request->header('X-Pathao-Merchant-Webhook-Integration-Secret') !== $expectedSecret) {
            Log::warning('Unauthorized webhook request', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        // Log the received event
        Log::info('Webhook received', $request->all());

        // Process the event
        if ($request->input('event') === 'webhook_integration') {
            // Handle webhook integration event
            // You can perform additional logic here if needed
        }

        // Extract payload data
        $event = $request->input('event');
        $consignmentId = $request->input('consignment_id');
        $merchantOrderId = $request->input('merchant_order_id');
        $updatedAt = $request->input('updated_at');
        $timestamp = $request->input('timestamp');
        $storeId = $request->input('store_id');

        // Process only "Assigned for Delivery" event
        if ($event === 'order.assigned-for-delivery') {
            // Here, you can store the details in a database, notify users, etc.
            Log::info("Order Assigned for Delivery", [
                'consignment_id' => $consignmentId,
                'merchant_order_id' => $merchantOrderId,
                'updated_at' => $updatedAt,
                'timestamp' => $timestamp,
                'store_id' => $storeId
            ]);
        }

        // Respond with 202 status code
        return response()->json(['message' => 'Webhook received successfully'], Response::HTTP_ACCEPTED);
    }
}
