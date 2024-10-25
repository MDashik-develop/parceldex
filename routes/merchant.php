<?php

use App\Models\Parcel;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    $merchant_id = 119;
     $x = Parcel::where('merchant_id', $merchant_id)
        
        ->where('status', '>=', 25)
        ->whereIn('parcel_invoice', [4856, 4859, 4860, 4862])
        ->whereIn('delivery_type', [1, 2, 4])
        ->whereIn('payment_type', [2, 4, 6])
        ->sum('customer_collect_amount');



         $y = Parcel::where('merchant_id', $merchant_id)
        ->where('status', '>=', 25)
        ->whereIn('parcel_invoice', [4856, 4859, 4860, 4862])
        ->whereIn('delivery_type', [1, 2, 4])
        ->whereIn('payment_type', [2, 4, 6])
        ->sum('cancel_amount_collection');

      $total_customer_collect_amount      = $x + $y;

     $total_charge_amount_query = Parcel::where('merchant_id', $merchant_id)
     ->where('status', '>=', 25)
     ->whereIn('parcel_invoice', [4856, 4859, 4860, 4862])
     ->whereIn('delivery_type', [1, 2, 4])
     ->whereIn('payment_type', [2, 4, 6]);

         $cod_charge = $total_charge_amount_query->sum('cod_charge');
          $delivery_charge = $total_charge_amount_query->sum('delivery_charge');
            $weight_package_charge = $total_charge_amount_query->sum('weight_package_charge');
            return $return_charge = $total_charge_amount_query->sum('merchant_service_area_return_charge');

    $total_charge_amount = $cod_charge + $delivery_charge + $weight_package_charge + $return_charge;

    return  number_format($total_customer_collect_amount - $total_charge_amount, 2, '.', '');
});

Route::get('/merchant', [App\Http\Controllers\Merchant\AuthController::class, 'login'])->name('login');
Route::post('/merchant', [App\Http\Controllers\Merchant\AuthController::class, 'login_check'])->name('login');


Route::get('/merchant/forgotPassword', [App\Http\Controllers\Merchant\AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/merchant/forgotPassword', [App\Http\Controllers\Merchant\AuthController::class, 'confirmForgotPassword'])->name('forgotPassword');
Route::get('/merchant/resetPassword/{token}', [App\Http\Controllers\Merchant\AuthController::class, 'resetPassword'])->name('resetPassword');
Route::post('/merchant/resetPassword', [App\Http\Controllers\Merchant\AuthController::class, 'confirmResetPassword'])->name('resetPassword');


Route::group(['middleware' => 'merchant', 'prefix' => 'merchant/'], function () {
    Route::match(['get', 'post'], '/logout', [App\Http\Controllers\Merchant\AuthController::class, 'logout'])->name('logout');

    Route::match(['get', 'post'], '/home', [App\Http\Controllers\Merchant\HomeController::class, 'home'])->name('home');
    Route::get('profile', [App\Http\Controllers\Merchant\HomeController::class, 'profile'])->name('profile');
    Route::get('updateProfile', [App\Http\Controllers\Merchant\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::patch('confirmUpdateProfile', [App\Http\Controllers\Merchant\HomeController::class, 'confirmUpdateProfile'])->name('confirmUpdateProfile');

    Route::get('orderTracking/{parcel_invoice?}', [App\Http\Controllers\Merchant\HomeController::class, 'orderTracking'])->name('orderTracking');
    Route::post('returnOrderTrackingResult', [App\Http\Controllers\Merchant\HomeController::class, 'returnOrderTrackingResult'])->name('returnOrderTrackingResult');
    Route::get('coverageArea', [App\Http\Controllers\Merchant\HomeController::class, 'coverageArea'])->name('coverageArea');
    Route::get('getCoverageAreas', [App\Http\Controllers\Merchant\HomeController::class, 'getCoverageAreas'])->name('getCoverageAreas');
    Route::get('serviceCharge', [App\Http\Controllers\Merchant\HomeController::class, 'serviceCharge'])->name('serviceCharge');
    Route::get('getServiceCharges', [App\Http\Controllers\Merchant\HomeController::class, 'getServiceCharges'])->name('getServiceCharges');

    /** Parcel Filter Route */
    Route::get('parcel/parcel-filter-list/{type}', [App\Http\Controllers\Merchant\ParcelFilterController::class, 'filterParcelList'])->name('parcel.filterList');
    /** End Parcel Filter Route */

    /** Parcel Report */
    Route::match(['get', 'post'], '/report', [App\Http\Controllers\Merchant\ReportController::class, 'index'])->name('report');
    /** Parcel Report end */

    //For getting customer Info
    Route::get('customer/info', [App\Http\Controllers\Merchant\ParcelController::class, 'customerInfo'])->name('customer.info');
    //For getting customer Info

    Route::get('parcel/add', [App\Http\Controllers\Merchant\ParcelController::class, 'add'])->name('parcel.add');
    Route::post('parcel/store', [App\Http\Controllers\Merchant\ParcelController::class, 'store'])->name('parcel.store');
    Route::get('parcel/list', [App\Http\Controllers\Merchant\ParcelController::class, 'list'])->name('parcel.list');
    Route::get('parcel/return/list', [App\Http\Controllers\Merchant\ParcelController::class, 'returnList'])->name('return.list');
    Route::get('parcel/getReturnRiderRunList', [App\Http\Controllers\Merchant\ParcelController::class, 'getReturnRiderRunList'])->name('parcel.getReturnRiderRunList');
    Route::get('parcel/getParcelList', [App\Http\Controllers\Merchant\ParcelController::class, 'getParcelList'])->name('parcel.getParcelList');
    Route::get('parcel/printParcelList', [App\Http\Controllers\Merchant\ParcelController::class, 'printParcelList'])->name('parcel.printParcelList');
    Route::post('parcel/printParcelMultiple', [App\Http\Controllers\Merchant\ParcelController::class, 'printParcelMultiple'])->name('parcel.printParcelMultiple');

    Route::post('parcel/excelAllParcelList', [App\Http\Controllers\Merchant\ParcelController::class, 'excelAllParcelList'])->name('parcel.excelAllParcelList');


    Route::get('/push-request', [App\Http\Controllers\Merchant\ParcelController::class, 'pushRequest'])->name('push.request');
    Route::post('getPushRequestList', [App\Http\Controllers\Merchant\ParcelController::class, 'savePushRequest'])->name('savePushRequest');




    // Route::get('parcel/merchantBulkParcelImport', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImport'] )->name('parcel.merchantBulkParcelImport');
    // Route::post('parcel/merchantBulkParcelImport', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImportStore'] )->name('parcel.merchantBulkParcelImport');

    Route::get('parcel/merchantBulkParcelImport', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImport'])->name('parcel.merchantBulkParcelImport');
    Route::post('parcel/merchantBulkParcelImportEntry', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImportEntry'])->name('parcel.merchantBulkParcelImportEntry');
    Route::get('parcel/merchantBulkParcelImport/check', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImportCheck'])->name('parcel.merchantBulkParcelImport.check');
    Route::get('parcel/merchantBulkParcelImport/reset', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImportReset'])->name('parcel.merchantBulkParcelImport.reset');
    Route::post('parcel/merchantBulkParcelImport', [App\Http\Controllers\Merchant\ParcelController::class, 'merchantBulkParcelImportStore'])->name('parcel.merchantBulkParcelImport');




    Route::post('parcel/parcelHold', [App\Http\Controllers\Merchant\ParcelController::class, 'parcelHold'])->name('parcel.parcelHold');
    Route::post('parcel/parcelStart', [App\Http\Controllers\Merchant\ParcelController::class, 'parcelStart'])->name('parcel.parcelStart');
    Route::post('parcel/parcelCancel', [App\Http\Controllers\Merchant\ParcelController::class, 'parcelCancel'])->name('parcel.parcelCancel');

    Route::get('parcel/{parcel}/edit', [App\Http\Controllers\Merchant\ParcelController::class, 'edit'])->name('parcel.edit');
    Route::patch('parcel/{parcel}/update', [App\Http\Controllers\Merchant\ParcelController::class, 'update'])->name('parcel.update');
    Route::get('parcel/{parcel}/viewParcel', [App\Http\Controllers\Merchant\ParcelController::class, 'viewParcel'])->name('parcel.viewParcel');


    Route::get('account/parcelPaymentList', [App\Http\Controllers\Merchant\ParcelPaymentController::class, 'parcelPaymentList'])->name('account.parcelPaymentList');
    Route::get('account/getParcelPaymentList', [App\Http\Controllers\Merchant\ParcelPaymentController::class, 'getParcelPaymentList'])->name('account.getParcelPaymentList');


    Route::get('account/deliveryPaymentList', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'deliveryPaymentList'])->name('account.deliveryPaymentList');

    Route::get('account/getDeliveryPaymentList', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'getDeliveryPaymentList'])->name('account.getDeliveryPaymentList');
    Route::get('account/{parcelMerchantDeliveryPayment}/viewMerchantDeliveryPayment', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'viewMerchantDeliveryPayment'])->name('account.viewMerchantDeliveryPayment');
    Route::get('account/{parcelMerchantDeliveryPayment}/exportMerchantDeliveryPayment', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'exportMerchantDeliveryPayment'])->name('account.exportMerchantDeliveryPayment');
    Route::get('account/{parcelMerchantDeliveryPayment}/printMerchantDeliveryPayment', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'printMerchantDeliveryPayment'])->name('account.printMerchantDeliveryPayment');




    Route::get('parcel/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentAccept', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'merchantDeliveryPaymentAccept'])->name('account.merchantDeliveryPaymentAccept');
    Route::patch('parcel/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentAcceptConfirm', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'merchantDeliveryPaymentAcceptConfirm'])->name('account.merchantDeliveryPaymentAcceptConfirm');
    Route::get('parcel/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentReject', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'merchantDeliveryPaymentReject'])->name('account.merchantDeliveryPaymentReject');
    Route::patch('parcel/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentRejectConfirm', [App\Http\Controllers\Merchant\DeliveryPaymentController::class, 'merchantDeliveryPaymentRejectConfirm'])->name('account.merchantDeliveryPaymentRejectConfirm');

    /** Parcel Notification Route */
    Route::get('parcel/notification-list', [App\Http\Controllers\Merchant\ParcelNotificationController::class, 'index'])->name('parcel.notification');
    Route::get('parcel/getParcelNotificationList', [App\Http\Controllers\Merchant\ParcelNotificationController::class, 'getParcelNotificationList'])->name('parcel.getParcelNotificationList');
    Route::get('parcel/notification-read', [App\Http\Controllers\Merchant\ParcelNotificationController::class, 'parcelNotificationRead'])->name('parcel.parcelNotificationRead');


    /** email verification route */
    Route::get('emailVerification/{token}', [\App\Http\Controllers\EmailVerificationController::class, 'emailVerify'])->name('emailVerify');
    Route::post('emailVerificationLink/send', [\App\Http\Controllers\EmailVerificationController::class, 'emailVerificationLinkForMerchant'])->name('emailVerifyLink');
    Route::get('email-verify-success', [\App\Http\Controllers\EmailVerificationController::class, 'emailVerifySuccess'])->name('emailVerifySuccess');

    Route::get('shop/getShops', [\App\Http\Controllers\Merchant\ShopController::class, 'getShops'])->name('shop.getShops');
    Route::post('shop/updateStatus', [\App\Http\Controllers\Merchant\ShopController::class, 'updateStatus'])->name('shop.updateStatus');
    Route::delete('shop/delete', [\App\Http\Controllers\Merchant\ShopController::class, 'delete'])->name('shop.delete');
    Route::resource('shop', \App\Http\Controllers\Merchant\ShopController::class);

    Route::get('parcel/parcelPaymentRequest', [App\Http\Controllers\Merchant\ParcelPaymentRequestController::class, 'parcelPaymentRequest'])->name('parcel.parcelPaymentRequest');
    Route::post('parcel/confirmPaymentRequestGenerate', [App\Http\Controllers\Merchant\ParcelPaymentRequestController::class, 'confirmPaymentRequestGenerate'])->name('parcel.confirmPaymentRequestGenerate');
    Route::get('parcel/parcelPaymentRequestList', [App\Http\Controllers\Merchant\ParcelPaymentRequestController::class, 'parcelPaymentRequestList'])->name('parcel.parcelPaymentRequestList');
    Route::get('parcel/getParcelPaymentRequestList', [App\Http\Controllers\Merchant\ParcelPaymentRequestController::class, 'getParcelPaymentRequestList'])->name('parcel.getParcelPaymentRequestList');
    Route::get('parcel/viewParcelPaymentRequest/{parcelPaymentRequest}', [App\Http\Controllers\Merchant\ParcelPaymentRequestController::class, 'viewParcelPaymentRequest'])->name('parcel.viewParcelPaymentRequest');
});
