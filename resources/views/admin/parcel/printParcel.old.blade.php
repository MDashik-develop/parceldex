<!DOCTYPE html>
<html>

<head>
    <title>{{ session()->get('company_name') ?? config('app.name', 'Inventory') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href='https://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=IBM Plex Mono' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .div-table {
            display: table;
            width: 100%;
            border-left: 1px solid #000000;
            border-top: 1px solid #000000;
            /*margin: 10px;*/
        }

        .div-table-row {
            display: table-row;
            width: auto;
            clear: both;
        }

        .div-table-col {
            float: left;
            /* fix for  buggy browsers */
            display: table-column;
            border-bottom: 1px solid #000000;
            border-right: 1px solid #000000;
            margin: 0px;
        }

        p {
            margin: 0 0 5px;
        }
    </style>


    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('print/print_this.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.labelPrint').printThis({
                importCSS: false,
                loadCSS: "{{ asset('print/barcode-printer/label-css.css') }}",
                afterPrint: function() {
                    // window.close();
                }
            });
        });
    </script>

</head>

<body class="labelPrint">
    <style>
        .div-table {
            display: table;
            width: 100%;
            border-left: 1px solid #000000;
            border-top: 1px solid #000000;
            /*margin: 10px;*/
        }

        .div-table-row {
            display: table-row;
            width: auto;
            clear: both;
        }

        .div-table-col {
            float: left;
            /* fix for  buggy browsers */
            display: table-column;
            border-bottom: 1px solid #000000;
            border-right: 1px solid #000000;
            margin: 0;
        }

        p {
            margin: 0 0 0;
        }
    </style>
    <div class="div-table">
        <div class="div-table-row">
            <div class="div-table-col" style="width: 50%; text-align: center; font-size: 20px; margin-left: -1px; ">
                <strong>Parceldex Limited</strong>

            </div>
            <div class="div-table-col" style="width: 50%; text-align: center; font-size: 20px; margin-left: -1px; ">
                <strong>ID: {{ $parcel->merchant_order_id }}</strong>

            </div>
        </div>

        <div class="">
            <div class="" style="">
                <strong style="margin-right: 5px">Sent By:</strong>
            </div>
            <div class="" style="width: 50%;height: 40px;margin-right: -1px">
                <strong style="margin-left: 0px">{{ $parcel->merchant->company_name }}</strong>
                <p style="margin-left: 0px">{{ $parcel->merchant->business_address }}</p>
                <p style="margin-left: 0px">{{ $parcel->merchant->contact_number }}</p>
            </div>
        </div>

        <div class="">
            <div class="" style="width: 50%;height: 40px;margin-right: -1px">
                <strong style="margin-right: 5px">Sent To:</strong>
            </div>
            <div class="">
                <strong style="margin-left: 5px">{{ $parcel->customer_name }}</strong>
                <p style="margin-left: 5px">{{ $parcel->customer_address }}</p>
                <p style="margin-left: 5px">{{ $parcel->customer_contact_number }}</p>
            </div>

        </div>


        <div class="div-table-row" style="text-align: center">

            <div class="div-table-col" style="width: 50%;margin-left: -1px; text-align: left">
                <strong style="margin-left: 5px">Send by:</strong>
                <strong style="margin-left: 5px">
                    {{ str_limit($parcel->merchant->company_name, $limit = 18, $end = '..') }}</strong>
                <p style="margin-left: 5px"><b> {{ $parcel->merchant->contact_number }}</b></p>
                <p style="margin-left: 5px"> </p>
                <p style="margin-left: 5px"> </p>
            </div>
            <div class="div-table-col" style="width: 50%;margin-right: -1px; text-align: left">
                <strong style="margin-left: 5px"> Send to: </strong>
                <strong style="margin-left: 5px; "> {{ str_limit($parcel->customer_name, $limit = 18, $end = '..') }}
                </strong><br>

                <p style="margin-left: 5px"><b> {{ $parcel->customer_contact_number }}</b></p>

            </div>

        </div>

        <div class="div-table-row" style="text-align: center">
            <div class="div-table-col" style="width: 100%;"><b>
                    {{ str_limit($parcel->customer_address, $limit = 50, $end = '...') }}</b>
                <!--{{ $parcel->customer_address }}-->
            </div>

        </div>









        <div class="div-table-row" style="text-align: center;">

            <div class="div-table-col" style="width: 100%;height: 110px;margin-right: -1px">
                <p class="align-center"> <b>{{ $parcel->area->name }}</b></p>
                <img class="img-bar_code"
                    src="data:image/png; base64,{{ \DNS1D::getBarcodePNG($parcel->parcel_invoice, 'C128', 2, 30) }}"
                    alt="barcode" style="height:50px; width:70%; margin-top: 5px;" />
                <p class="align-center"> <b>PDL {{ $parcel->parcel_invoice }}</b></p>
            </div>

            <!--<div class="div-table-col" style="width: 50%;height: 95px;margin-right: -1px;padding-bottom: 1px">-->
            <!--    <img class="img-qr_code"-->
            <!--         src="data:image/png; base64,{{ \DNS2D::getBarcodePNG($parcel->parcel_invoice, 'QRCODE') }}"-->
            <!--         alt="QR code"-->
            <!--         style="height:55px; width:55px;margin: 10px"/>-->
            <!--</div>-->

        </div>

        <div class="div-table-row" style="text-align: left">
            <div class="div-table-col" style="width: 100%;">
                <b> Special Instruction: {{ str_limit($parcel->parcel_note, $limit = 50, $end = '...') }} </b>
            </div>
        </div>

        <div class="div-table-row" style="text-align: center">
            <div class="div-table-col" style="width: 50%;margin-right: -1px">
                <strong>Weight | {{ $parcel->weight_package->name }} </strong>
            </div>
            <div class="div-table-col" style="width: 50%;margin-right: -1px">
                <strong>COD | {{ $parcel->total_collect_amount }} TK</strong>
            </div>
        </div>

        <div class="div-table-col" style="width: 50%;height: 22px;margin-right: -1px">
            <p style="font-size: 12px;margin-left: 5px" id="currentDateTime">
                <!--Created: {{ $parcel->created_at->format('Y-m-d h:i') }}-->
            </p>
        </div>

        <div class="div-table-col" style="width: 50%;height: 22px;margin-right: -1px; text-align: right">
            <p style="font-size: 12px; margin-right: 5px"> Powered by: <b>Parceldex Limited </b></p>
        </div>

    </div>


    <script>
        // JavaScript code to update current date and time
        function updateDateTime() {
            var options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            var currentDate = new Date();
            var formattedDate = currentDate.toLocaleDateString('en-US', options);
            var formattedTime = currentDate.toLocaleTimeString();
            var currentDateTime = formattedDate + ' ' + formattedTime;

            // Update the content of the element with id "currentDateTime"
            document.getElementById('currentDateTime').innerText = '  ' + currentDateTime;
        }

        // Call the function to update date and time
        updateDateTime();
    </script>
</body>

</html>
