<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Parcel;
use App\Models\BookingParcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ParcelMerchantDeliveryPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use App\Models\ParcelMerchantDeliveryPaymentDetail;

class AdminMerchantPaymentExport implements
    FromCollection,
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithEvents,
    WithProperties
{

    private $count;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $request = $this->request;


        $model = ParcelMerchantDeliveryPayment::with('parcel_merchant_delivery_payment_details.parcel', 'merchant')
            ->where(function ($query) use ($request) {
                $merchant_id = $request->input('merchant_id');
                $status = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');

                if (($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0)
                    || ($request->has('status') && !is_null($status) && $status != '' && $status != 0)
                    || ($request->has('from_date') && !is_null($from_date) && $from_date != '')
                    || ($request->has('to_date') && !is_null($to_date) && $to_date != '')
                ) {
                    if ($request->has('merchant_id') && !is_null($merchant_id) && $merchant_id != '' && $merchant_id != 0) {
                        $query->where('merchant_id', $request->input('merchant_id'));
                    }

                    if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
                        $query->where('status', $request->input('status'));
                    }


                    if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                        $query->whereDate('date_time', '>=', $request->input('from_date'));
                    }

                    if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                        $query->whereDate('date_time', '<=', $request->input('to_date'));
                    }
                } else {
                    // $query->whereDate('date_time', '>=', date('Y-m-d'));
                    // $query->whereDate('date_time', '<=', date('Y-m-d'));
                    $query->where('status', '!=', '3');
                }
            })
            ->orderBy('id', 'desc');


        // ->editColumn('total_payment_amount', function ($data) {
        //     return number_format($data->total_payment_amount, 2);
        // })
        // ->editColumn('total_payment_received_amount', function ($data) {
        //     return number_format($data->total_payment_received_amount, 2);
        // })


        $payments = $model->get();
        $data_parcel_array = [];
        if (count($payments) > 0) {
            foreach ($payments as $key => $payment) {

                $customer_collect_amount = 0;
                foreach ($payment->parcel_merchant_delivery_payment_details as $v_data) {
                    $customer_collect_amount += $v_data?->parcel?->customer_collect_amount;
                }

                $total_collect_amount = 0;
                foreach ($payment->parcel_merchant_delivery_payment_details as $v_data) {
                    $total_collect_amount += $v_data?->parcel?->total_collect_amount;
                }

                $total_return_charge = 0;
                $total_cod_charge = 0;
                $total_weight_charge = 0;
                $total_delivery_charge = 0;

                foreach ($payment->parcel_merchant_delivery_payment_details as $v_data) {
                    $total_return_charge += $v_data?->parcel?->return_charge;
                    $total_cod_charge += $v_data?->parcel?->cod_charge;
                    $total_weight_charge += $v_data?->parcel?->weight_charge;
                    $total_delivery_charge += $v_data?->parcel?->delivery_charge;
                }

                $total = $total_delivery_charge + $total_weight_charge + $total_cod_charge + $total_return_charge;
                
                $data_parcel_array[] = (object)[
                    'serial' => $key + 1,
                    'payment_date' => date('d-m-Y', strtotime($payment->date_time)),
                    'merchant_name' => $payment->merchant->company_name,
                    'merchant_id' => $payment->merchant->m_id,
                    'amount_to_be_collect' => number_format($total_collect_amount, 2),
                    'collected' => number_format($customer_collect_amount, 2),
                    'delivery_charge' => number_format($total_delivery_charge, 2),
                    'weight_charge' => number_format($total_weight_charge, 2),
                    'cod_charge' => number_format($total_cod_charge, 2),
                    'return_charge' => number_format($total_return_charge, 2),
                    'total_charge' => number_format($total, 2),
                    'payment_method' => $payment->merchant->payment_recived_by ?? 'N/A',
                    'payment_type' => $parcel->merchant->payment_type ?? 'N/A',
                    'payment_invoice_id' => $payment->merchant_payment_invoice,
                    'routing_number' => $parcel->merchant->routing_number ?? 'N/A',
                    'account_number' => $payment->merchant->bank_account_no,
                    'payable_amount' => number_format($payment->total_payment_amount, 2),
                    'account_name' => $payment->merchant->bank_account_name,
                    'mobile_number' => $payment->merchant->contact_number,
                    'email' => $payment->merchant->email,
                    'bkash_number' => $payment->merchant->bkash_number,
                    'nagad_number' => $payment->merchant->nagad_number,
                    'rocket_number' => $payment->merchant->rocket_name
                ];
            }
        }


        return new Collection($data_parcel_array);
    }

    public function map($row): array
    {
        return [
            $row->serial,
            $row->payment_date,
            $row->merchant_name,
            $row->merchant_id,
            $row->amount_to_be_collect,
            $row->collected,
            $row->delivery_charge,
            $row->weight_charge,
            $row->cod_charge,
            $row->return_charge,
            $row->total_charge,
            $row->payment_method,
            $row->payment_type,
            $row->payment_invoice_id,
            $row->routing_number,
            $row->account_number,
            $row->payable_amount,
            $row->account_name,
            $row->mobile_number,
            $row->email,
            $row->bkash_number,
            $row->nagad_number,
            $row->rocket_number,
        ];
    }

    public function headings(): array
    {
        return [
            'SL',
            'Payment Date',
            'Merchant Name',
            'Merchant ID',
            'Total Amount to Be Collect',
            'Collected',
            'Total Delivery Charge',
            'Total Weight Charge',
            'Total COD charge',
            'Total Return Charge',
            'Total Charge',
            'Payment method',
            'Payment Type',
            'Payment Invoice ID',
            'Routing Number',
            'Account Number',
            'Payable Amount',
            'Account Name',
            'Mobile number',
            'Email',
            'bkash Number',
            'Nagad Number',
            'Rocket Number',
        ];
    }

    public function properties(): array
    {
        return [
            //            'creator'        => 'Patrick Brouwers',
            //            'lastModifiedBy' => 'Patrick Brouwers',
            'title' => 'Merchant Delivery Payment List',
            //            'description'    => 'Latest Invoices',
            //            'subject'        => 'Invoices',
            //            'keywords'       => 'invoices,export,spreadsheet',
            //            'category'       => 'Invoices',
            //            'manager'        => 'Patrick Brouwers',
            //            'company'        => 'Maatwebsite',
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getStyle('A1:Z1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);

                //                $event->sheet->getStyle('A'.$this->count.':K'.$this->count)->applyFromArray([
                //                    'font'  => [
                //                        'bold'  => true,
                //                    ]
                //                ]);

                if ('pdf' == "pdf") {

                    foreach (range('B', 'Z') as $columnID) {
                        $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                    }

                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                }

                //                $event->sheet->getStyle(
                //                    'B2:G8',
                //                    [
                //                        'borders' => [
                //                            'outline' => [
                //                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                //                                'color' => ['argb' => 'FFFF0000'],
                //                            ],
                //                        ]
                //                    ]
                //                );

            },
        ];
    }
}
