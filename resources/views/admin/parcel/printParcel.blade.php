<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            gap: 10px;
            flex-wrap: wrap;
        }

        .label {
            width: 600px;
            height: 600px;
            padding: 20px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .label .hedding {
            font-size: 17px;
            color: #343a40;
            border-bottom: 2px solid #6c757d;
            padding-bottom: 5px;
        }

        .section {
            margin-bottom: 10px;
            font-size: 17px;
            line-height: 1.6;
        }

        .barcode {
            text-align: center;
            margin: 15px 0;
        }

        .barcode img {
            max-width: 100%;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            width: 100%;
            flex-wrap: wrap;
        }

        .info div {
            flex: 1;
            text-align: center;
            font-size: 14px;
            /* background-color: #e9ecef; */
            border-radius: 5px;
            padding: 5px;
            margin: 0 5px;
        }

        .footer {
            text-align: right;
            font-size: 12px;
            color: #6c757d;
            margin-top: 10px;
        }
    </style>

    <style>
        @page {
            size: 600px 600px;
            /* margin: 30mm 45mm 30mm 45mm; */
            margin: 0;
            /* change the margins as you want them to be. */
        }

        @media print {

            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                gap: 0px;
                flex-wrap: wrap;
            }

            .label {
                width: 600px;
                height: 600px;
                padding: 20px;
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: 0px;
                box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <!-- Include the QRCode library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>

<body>

    <div class="label">
        <div class="d-flex align-items-end mb-2">
            <img src="/black-logo.png" alt="Logo" width="170">
            <strong style="font-size: 11px;font-weight: bolder;font-style: italic;">COURIER</strong>
        </div>
        <hr class="my-2 mb-1" style="opacity: 1;border: 1px solid black;">
        <div class="d-flex justify-content-center flex-wrap">
            <img alt="Barcode" id="barcode" style="width: 100%;height: 35px;">
            <div style="text-align: center"> {{ $parcel->parcel_invoice }}</div>
        </div>
        <div class="section mb-0">
            <strong>Marchant: {{ $parcel->merchant->company_name }} ({{ $parcel->merchant->m_id }})</strong> <br>
            <strong>Mobile: {{ $parcel->merchant->contact_number }}</strong> <br>
            <strong>Order ID: {{ $parcel->merchant_order_id }}</strong>
        </div>
        <hr class="my-0 mb-2" style="opacity: 1;border: 1px solid black;">
        <div class="section">
            <strong>Customer: {{ $parcel->customer_name }}</strong>
            <div>
                {{ $parcel->customer_address }}
            </div>
            <strong>Mobile:
                {{ $parcel->customer_contact_number }}{{ $parcel->customer_contact_number2 ? ', ' . $parcel->customer_contact_number2 : '' }}</strong>
        </div>
        <table class="table table-bordered text-center" style="border-color: black;">
            <tr>
                <th>{{ $parcel->area->name }}</th>
            </tr>
        </table>
        <div class="d-flex gap-2">
            <div id="qrcode"></div>
            <div class="info">
                <table class="table table-bordered text-center mb-0" style="border-color: black;">
                    <tbody style="font-size: 34px;">
                        <tr>
                            <th>{{ $parcel->total_collect_amount ? 'COD: ' . number_format($parcel->total_collect_amount) . ' BDT' : 'PAID' }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2">{{ $parcel?->district?->service_area?->name }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>www.parceldex.com</div>
            <div><strong>Hotline:</strong> 09642735735</div>
        </div>
        <hr class="my-0" style="opacity: 1;border: 1px solid black;">
        <div class="d-flex justify-content-between wrap">
            <div>Print By: {{ auth('merchant')->user()->name ?? 'Guest' }}</div>
            <div>{{ date('j F g:i a') }}</div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        JsBarcode("#barcode", "{{ $parcel->parcel_invoice }}", {
            // format: "pharmacode",
            // lineColor: "#0aa",
            width: 10,
            height: 30,
            fontSize: 15,
            displayValue: false,
            margin: 0,
        });
    </script>

    <!-- Your script that uses QRCode -->
    <script>
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "{{ $parcel->parcel_invoice }}",
            width: 140,
            height: 140
        });
    </script>

    <script>
        // Automatically trigger the print dialog when the page loads
        window.print();

        // Close the window after printing
        window.onafterprint = function() {
            window.close(); // Close the current window/tab
        };
    </script>
</body>

</html>
