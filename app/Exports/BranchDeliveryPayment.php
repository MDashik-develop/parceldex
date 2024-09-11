<?php

namespace App\Exports;

use App\Models\BookingParcel;
use App\Models\Parcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;

class BranchDeliveryPayment implements
    FromCollection,
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithEvents,
    WithProperties
{

    private $count;

    protected $parcelDeliveryPayment;

    public function __construct($parcelDeliveryPayment)
    {
        $this->parcelDeliveryPayment = $parcelDeliveryPayment;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $parcel_delivery_payment_details = $this->parcelDeliveryPayment->parcel_delivery_payment_details;
        $data_parcel_array  = [];
        if (count($parcel_delivery_payment_details) > 0) {
            foreach ($parcel_delivery_payment_details as $key => $parcel_delivery_payment_detail) {

                $status = returnParcelStatusNameForBranch(
                    $parcel_delivery_payment_detail?->parcel?->status,
                    $parcel_delivery_payment_detail?->parcel?->delivery_type,
                    $parcel_delivery_payment_detail?->parcel?->payment_type,
                );

                $status_name = $status['status_name'];

                // if ($parcel_delivery_payment_detail->status == 1) {
                //     $status_name = 'Send Request';
                // } else if ($parcel_delivery_payment_detail->status == 2) {
                //     $status_name = 'Request Accepted';
                // } else if ($parcel_delivery_payment_detail->status == 3) {
                //     $status_name = 'Request Rejected';
                // }

                $data_parcel_array[] = (object)[
                    'invoice' => $parcel_delivery_payment_detail->parcel->parcel_invoice,
                    'order_id' => $parcel_delivery_payment_detail->parcel->merchant_order_id ?? "---",
                    'status' => $status_name,
                    'company_name' => $parcel_delivery_payment_detail->parcel->merchant->company_name,
                    // 'merchant_number' => $parcel_delivery_payment_detail->parcel->merchant->contact_number,
                    // 'merchant_address' => $parcel_delivery_payment_detail->parcel->merchant->address,
                    'customer_name' => $parcel_delivery_payment_detail->parcel->customer_name,
                    'customer_contact_number' => $parcel_delivery_payment_detail->parcel->customer_name,
                    'total_collect_amount' => number_format($parcel_delivery_payment_detail->parcel->total_collect_amount, 2),
                    'collected' => number_format($parcel_delivery_payment_detail->parcel->cancel_amount_collection != 0 ? $parcel_delivery_payment_detail->parcel->cancel_amount_collection :  $parcel_delivery_payment_detail->parcel->customer_collect_amount, 2),
                ];
            }
        }

        return new Collection($data_parcel_array);
    }

    public function map($row): array
    {
        return  [
            $row->invoice,
            $row->order_id,
            $row->status,
            $row->company_name,
            // $row->merchant_number,
            // $row->merchant_address,
            $row->customer_name,
            $row->customer_contact_number,
            $row->total_collect_amount,
            $row->collected,

        ];
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Order Id',
            'Status',
            'Company Name',
            // 'Merchant Number',
            // 'Merchant Address',
            'Customer Name',
            'Customer Phone Number',
            'Amount to be Collect',
            'Collected',
        ];
    }

    public function properties(): array
    {
        return [
            //            'creator'        => 'Patrick Brouwers',
            //            'lastModifiedBy' => 'Patrick Brouwers',
            'title'             => 'Branch Delivery Payment Parcel',
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
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getStyle('A1:Z1')->applyFromArray([
                    'font'  => [
                        'bold'  => true,
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
