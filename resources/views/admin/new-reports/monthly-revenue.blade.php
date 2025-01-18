<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --width: 210mm;
            --height: 297mm;
            --margin: 10mm;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .print-container {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin: auto;

        }

        .label {
            width: var(--width);
            min-height: var(--height);
            padding: var(--margin);
            background: #fff;
            border: 1px solid #dee2e6;
            /* border-radius: 10px; */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: scroll;
        }
    </style>

    <style>
        @page {
            /* size: var(--width) var(--height); */
            margin: var(--margin) var(--margin) var(--margin) var(--margin);
            /* change the margins as you want them to be. */
        }

        @media print {

            body {
                background-color: #fff;
                min-height: auto;
            }

            .print-container {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
                margin: auto;
            }

            .label {
                width: 100%;
                height: auto;
                min-height: auto;
                padding: 0;
                background: #fff;
                border: none;
                border-radius: 0px;
                box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);
                overflow-x: hidden;
            }

            .print-hidden {
                display: none;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <!-- Include the QRCode library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>

<body>
    <section style="background: #f8f9fa;" class="print-hidden">
        <div class="container py-5" style="max-width: 600px; min-width:300px">
            <div class="d-flex gap-5 wrap">
                <select class="form-select" aria-label="Default select example">
                    <option selected>Select Mounth</option>

                    <?php
                    $currentMonth = date('m');
                    $currentYear = date('Y');
                    $months = 50;
                    for ($i = 0; $i < $months; $i++) {
                        $month = $currentMonth - $i;
                        $year = $currentYear;
                        if ($month < 1) {
                            $month += 12;
                            $year--;
                        }
                        echo '<option value="' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '">' . $year . ' - ' . date('F', mktime(0, 0, 0, $month, 1)) . '</option>';
                    }
                    ?>

                </select>
                <button type="button" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </section>

    <div class="print-container">
        <div class="label">
            <h3>Parceldex Limited</h3>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th></th>
                        <th>Grand Total</th>
                        <th>Dec-2024</th>
                        <th>Nov-2024</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Number of Order</strong></td>
                        <td>19,921</td>
                        <td>10,027</td>
                        <td>9,894</td>
                    </tr>
                    <tr>
                        <td><strong>Amount to be Collect</strong></td>
                        <td>35,499,425</td>
                        <td>17,735,567</td>
                        <td>17,763,858</td>
                    </tr>
                    <tr>
                        <td><strong>Collected Amount</strong></td>
                        <td>30,928,038</td>
                        <td>14,774,019</td>
                        <td>16,154,019</td>
                    </tr>
                    <tr>
                        <td><strong>Weight Charge</strong></td>
                        <td>212,190</td>
                        <td>59,405</td>
                        <td>152,785</td>
                    </tr>
                    <tr>
                        <td><strong>COD Charge</strong></td>
                        <td>117,372</td>
                        <td>50,369</td>
                        <td>67,004</td>
                    </tr>
                    <tr>
                        <td><strong>Delivery Charge</strong></td>
                        <td>1,246,020</td>
                        <td>616,245</td>
                        <td>629,775</td>
                    </tr>
                    <tr>
                        <td><strong>Return Charge</strong></td>
                        <td>39,375</td>
                        <td>14,640</td>
                        <td>24,735</td>
                    </tr>
                    <tr>
                        <td><strong>Other Charges</strong></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr class="table-info">
                        <td><strong>Total Revenue</strong></td>
                        <td>1,614,957</td>
                        <td>740,659</td>
                        <td>874,299</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>Average Revenue Per Order</strong></td>
                        <td>81</td>
                        <td>74</td>
                        <td>88</td>
                    </tr>
                    <tr>
                        <td><strong>Average Order Per Day</strong></td>
                        <td>327</td>
                        <td>323</td>
                        <td>330</td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        JsBarcode("#barcode", "Hi!", {
            // format: "pharmacode",
            // lineColor: "#0aa",
            // width: 4,
            height: 30,
            fontSize: 15
            // displayValue: false
        });
    </script>

    <!-- Your script that uses QRCode -->
    <script>
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "https://example.com",
            width: 80,
            height: 80
        });
    </script>
</body>

</html>
