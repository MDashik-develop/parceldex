<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParcelMerchantDeliveryPayment;
use Illuminate\Http\Request;

class BulkMerchantPaymentOkController extends Controller
{
    //
    public function bulkConfirm(Request $request)
    {
        $invoiceNumbers = array_map('trim', explode("\n", $request->input('invoice_numbers')));

        $p = ParcelMerchantDeliveryPayment::whereIn('merchant_payment_invoice', $invoiceNumbers)->get();

        foreach ($p as $v) {
            $v->update([
                'status' => 2,
                'transfer_reference' => $request->input('transfer_reference'),
            ]);
        }

        return redirect()->back()->with('success', 'Bulk payment confirmed successfully');
    }
}
