<?php

use Illuminate\Support\Facades\Route;

Route::post('/merchant/login', [App\Http\Controllers\API\Merchant\AuthController::class, 'login'])->name('login');
Route::post('/merchant/registration', [App\Http\Controllers\API\Merchant\AuthController::class, 'registration'])->name('registration');
Route::post('/merchant/webRegistration', [App\Http\Controllers\API\Merchant\AuthController::class, 'webRegistration'])->name('webRegistration');
Route::post('/merchant/confirmContactNumber', [App\Http\Controllers\API\Merchant\AuthController::class, 'confirmContactNumber'])->name('confirmContactNumber');
Route::post('/merchant/forgotPassword', [App\Http\Controllers\API\Merchant\AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/merchant/confirmForgotPassword', [App\Http\Controllers\API\Merchant\AuthController::class, 'confirmForgotPassword'])->name('confirmForgotPassword');

Route::post('/merchant/login', [App\Http\Controllers\API\Merchant\AuthController::class, 'login'])->name('login');



Route::group(['middleware' => 'jwt', 'prefix' => 'merchant/'], function () {
    Route::post('/', [App\Http\Controllers\API\Merchant\AuthController::class, 'me']);
    Route::post('refresh', [App\Http\Controllers\API\Merchant\AuthController::class, 'refresh']);
    Route::post('logout', [App\Http\Controllers\API\Merchant\AuthController::class, 'logout']);
    Route::post('payload', [App\Http\Controllers\API\Merchant\AuthController::class, 'payload']);
    Route::post('dashboard', [App\Http\Controllers\API\Merchant\HomeController::class, 'dashboard']);
    Route::get('view/news', [App\Http\Controllers\API\Merchant\HomeController::class, 'viewNews']);


    Route::post('profileUpdate', [App\Http\Controllers\API\Merchant\AuthController::class, 'profileUpdate']);


    //
    Route::post('v1/addParcel', [App\Http\Controllers\API\Merchant\ParcelController::class, 'addParcel2']);
    Route::post('v1/viewParcel', [App\Http\Controllers\API\Merchant\ParcelController::class, 'viewParcel2']);




    //================ Parcel ================================
    Route::post('addParcel', [App\Http\Controllers\API\Merchant\ParcelController::class, 'addParcel']);
    Route::post('getParcelList', [App\Http\Controllers\API\Merchant\ParcelController::class, 'getParcelList']);
    Route::post('filterParcelList', [App\Http\Controllers\API\Merchant\ParcelController::class, 'filterParcelList']);
    Route::post('getOrderTrackingResult', [App\Http\Controllers\API\Merchant\ParcelController::class, 'getOrderTrackingResult']);
    Route::post('parcelCancel', [App\Http\Controllers\API\Merchant\ParcelController::class, 'parcelCancel']);
    Route::post('parcelStart', [App\Http\Controllers\API\Merchant\ParcelController::class, 'parcelStart']);
    Route::post('parcelHold', [App\Http\Controllers\API\Merchant\ParcelController::class, 'parcelHold']);
    Route::post('viewParcel', [App\Http\Controllers\API\Merchant\ParcelController::class, 'viewParcel']);
    Route::post('editParcel', [App\Http\Controllers\API\Merchant\ParcelController::class, 'editParcel']);
    //================ Parcel ================================

    //================ Delivery Payment ================================
    Route::post('getDeliveryPaymentList', [App\Http\Controllers\API\Merchant\DeliveryPaymentController::class, 'getDeliveryPaymentList']);
    Route::post('getDeliveryPayment', [App\Http\Controllers\API\Merchant\DeliveryPaymentController::class, 'getDeliveryPayment']);
    Route::post('parcel/{parcelMerchantDeliveryPayment}/merchantDeliveryPaymentAcceptConfirm', [App\Http\Controllers\API\Merchant\DeliveryPaymentController::class, 'merchantDeliveryPaymentAcceptConfirm']);
    //================ Delivery Payment ================================

    //================ Parcel Payment ================================
    Route::post('getParcelPaymentList', [App\Http\Controllers\API\Merchant\ParcelPaymentController::class, 'getParcelPaymentList']);

    //================ Parcel Payment ================================

    // Payment Request
    Route::get('paymentRequestGenerate', [App\Http\Controllers\API\Merchant\ParcelPaymentRequestController::class, 'parcelPaymentRequest']);
    Route::post('confirmPaymentRequestGenerate', [App\Http\Controllers\API\Merchant\ParcelPaymentRequestController::class, 'confirmPaymentRequestGenerate']);
    Route::get('getParcelPaymentRequestList', [App\Http\Controllers\API\Merchant\ParcelPaymentRequestController::class, 'getParcelPaymentRequestList']);
    Route::post('viewParcelPaymentRequest', [App\Http\Controllers\API\Merchant\ParcelPaymentRequestController::class, 'viewParcelPaymentRequest']);


});

