<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelMerchantDeliveryPayment;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeMerchantDeliveryPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-merchant-delivery-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            // get parcel $ merchant
            $data = $this->getParcel();
            $parcels = $data['parcels'];
            $merchants = $data['merchants'];

            // dd($data);

            foreach ($merchants as $merchant) {
                $merchant_id = $merchant->id;
                $total_payment_parcel = 0;
                $total_payment_amount = 0;

                $merchant_parcels = $parcels->where('merchant_id', $merchant_id);

                foreach ($merchant_parcels as $parcel) {
                    if ($parcel->merchant_id == $merchant_id) {
                        $total_payment_parcel++;
                        $total_payment_amount += $parcel->merchant_paid_amount;
                    }
                }

                if ($total_payment_parcel > 0) {
                    // gemerate merchant delivery payment
                    $this->gemerateInvoice($merchant, $total_payment_parcel, $total_payment_amount, $merchant_parcels);
                }
            }

            DB::commit();

            return 1;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return 0;
        }

    }


    private function getParcel()
    {
        $data['parcels'] = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number');
            },
            'weight_package' => function ($query) {
                $query->select('id', 'name');
            }
        ])->whereRaw("
                ((parcels.delivery_type in (1) AND parcels.payment_type IN (2,6))
                OR (parcels.delivery_type in (2) AND parcels.payment_type IN (2,6))
                OR (parcels.delivery_type in (4) AND (parcels.payment_type is NULL || parcels.payment_type in (2,6))))
            ")->get();

        $data['merchants'] = Merchant::whereHas('parcel', function ($query) {
            $query->whereRaw("
                ((parcels.delivery_type in (1) AND parcels.payment_type in (2,6))
                OR (parcels.delivery_type in (2) AND parcels.payment_type in (2,6))
                OR (parcels.delivery_type in (4)  AND (parcels.payment_type is NULL || parcels.payment_type in (2,6))))
                ");
        })->orderBy('id')->get();

        return $data;
    }

    private function gemerateInvoice($merchant, $total_payment_parcel, $total_payment_amount, $parcels)
    {

        $merchant_payment_invoice = $this->returnUniqueMerchantDeliveryPaymentInvoice();

        $data = [
            'merchant_payment_invoice' => $merchant_payment_invoice,
            'admin_id' => 0,
            'merchant_id' => $merchant->id,
            'date_time' => date('Y-m-d H:i:s'),
            'total_payment_parcel' => $total_payment_parcel,
            'total_payment_amount' => $total_payment_amount,
            'status' => 1,
        ];

        $parcelMerchantDeliveryPayment = ParcelMerchantDeliveryPayment::create($data);

        foreach ($parcels as $parcel) {
            $cod_charge = $parcel->cod_charge;

            $returnCharge = 0;

            if ($parcel->delivery_type == 4 || $parcel->delivery_type == 2) {
                $returnCharge = $parcel->merchant_service_area_return_charge;
            } elseif (
                $parcel->suborder &&
                $parcel->exchange == 'yes' &&
                $parcel->parent_delivery_type == 1
            ) {
                $returnCharge = $parcel->merchant_service_area_return_charge;
            } elseif (
                $parcel->suborder &&
                $parcel->exchange == 'yes' &&
                $parcel->parent_delivery_type == 2
            ) {
                $returnCharge = $parcel->merchant_service_area_return_charge;
            } elseif (
                $parcel->suborder &&
                $parcel->exchange == 'no' &&
                $parcel->parent_delivery_type == 2
            ) {
                $returnCharge = $parcel->merchant_service_area_return_charge;
            }

            $sum_charge = $parcel->weight_package_charge + $parcel->delivery_charge + ceil($cod_charge) + $returnCharge;

            ParcelMerchantDeliveryPaymentDetail::create([
                'parcel_merchant_delivery_payment_id' => $parcelMerchantDeliveryPayment->id,
                'parcel_id' => $parcel->id,
                'collected_amount' => $parcel->customer_collect_amount,
                'cod_charge' => ceil($cod_charge),
                'delivery_charge' => $parcel->delivery_charge,
                'weight_package_charge' => $parcel->weight_package_charge,
                'return_charge' => $returnCharge,
                'paid_amount' => $sum_charge,
            ]);

            Parcel::where('id', $parcel->id)->update([
                'payment_type' => 4,
                'return_charge' => $returnCharge,
                'cod_charge' => ceil($cod_charge),
                'merchant_paid_amount' => $sum_charge,
            ]);
        }
    }

    private function returnUniqueMerchantDeliveryPaymentInvoice()
    {
        $lastDeliveryPayment = ParcelMerchantDeliveryPayment::orderBy('id', 'desc')->first();

        if (!empty($lastDeliveryPayment)) {
            $get_serial = explode("-", $lastDeliveryPayment->merchant_payment_invoice);
            $current_serials = $get_serial[1] + 1;
            $merchant_payment_invoice = 'MPAY-' . str_pad($current_serials, 5, '0', STR_PAD_LEFT);
        } else {
            $merchant_payment_invoice = 'MPAY-00001';
        }
        return $merchant_payment_invoice;
    }

}
