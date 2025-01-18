<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --width: 297mm;
            --height: 210mm;
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

            .label {
                width: 100%;
                height: auto;
                padding: 0;
                background: #fff;
                border: none;
                border-radius: 0px;
                box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);
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
            <div class="d-flex gap-3 wrap">
                <select class="form-select" aria-label="Default select example">
                    <option selected>Location Select</option>
                    <option value="1">Dhaka South</option>
                </select>

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

            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Hub Name</th>
                        <th>Rider Name</th>
                        <th>Total Order</th>
                        <th>Delivered</th>
                        <th>Pending</th>
                        <th>Cancel</th>
                        <th>Success Ratio</th>
                        <th>Pending Ratio</th>
                        <th>Return Ratio</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dhanmondi Hub -->
                    <tr>
                        <td rowspan="4">Dhanmondi Hub</td>
                        <td>MD. Masum billah</td>
                        <td>8</td>
                        <td>5</td>
                        <td>2</td>
                        <td>1</td>
                        <td>63%</td>
                        <td>25%</td>
                        <td>13%</td>
                    </tr>
                    <tr>
                        <td>Nirmol Saha</td>
                        <td>20</td>
                        <td>16</td>
                        <td>2</td>
                        <td>2</td>
                        <td>80%</td>
                        <td>10%</td>
                        <td>10%</td>
                    </tr>
                    <tr>
                        <td>Mir Nurunnobi Chand</td>
                        <td>30</td>
                        <td>27</td>
                        <td>2</td>
                        <td>1</td>
                        <td>90%</td>
                        <td>7%</td>
                        <td>3%</td>
                    </tr>
                    <tr>
                        <td>Md. Rasel</td>
                        <td>12</td>
                        <td>7</td>
                        <td>4</td>
                        <td>1</td>
                        <td>58%</td>
                        <td>33%</td>
                        <td>8%</td>
                    </tr>
                    <tr class="fw-bold text-center">
                        <td colspan="2">Dhanmondi Hub Total</td>
                        <td>70</td>
                        <td>55</td>
                        <td>10</td>
                        <td>5</td>
                        <td>79%</td>
                        <td>14%</td>
                        <td>7%</td>
                    </tr>

                    <!-- Jatrabari Hub -->
                    <tr>
                        <td rowspan="5">Jatrabari Hub</td>
                        <td>Md Sohel Rana</td>
                        <td>19</td>
                        <td>14</td>
                        <td>4</td>
                        <td>1</td>
                        <td>74%</td>
                        <td>21%</td>
                        <td>5%</td>
                    </tr>
                    <tr>
                        <td>MD alamin</td>
                        <td>15</td>
                        <td>11</td>
                        <td>3</td>
                        <td>1</td>
                        <td>73%</td>
                        <td>20%</td>
                        <td>7%</td>
                    </tr>
                    <tr>
                        <td>Md. Nahid islam</td>
                        <td>16</td>
                        <td>10</td>
                        <td>6</td>
                        <td>0</td>
                        <td>63%</td>
                        <td>38%</td>
                        <td>0%</td>
                    </tr>
                    <tr>
                        <td>Md. Jihad gazi</td>
                        <td>8</td>
                        <td>7</td>
                        <td>0</td>
                        <td>1</td>
                        <td>88%</td>
                        <td>0%</td>
                        <td>13%</td>
                    </tr>
                    <tr>
                        <td>Md Hriday</td>
                        <td>16</td>
                        <td>7</td>
                        <td>8</td>
                        <td>1</td>
                        <td>44%</td>
                        <td>50%</td>
                        <td>6%</td>
                    </tr>
                    <tr class="fw-bold text-center">
                        <td colspan="2">Jatrabari Hub Total</td>
                        <td>74</td>
                        <td>49</td>
                        <td>21</td>
                        <td>4</td>
                        <td>66%</td>
                        <td>28%</td>
                        <td>5%</td>
                    </tr>

                    <!-- Malibag Hub -->
                    <tr>
                        <td rowspan="4">Malibag HUB</td>
                        <td>Muhammad Hriday</td>
                        <td>15</td>
                        <td>10</td>
                        <td>4</td>
                        <td>1</td>
                        <td>67%</td>
                        <td>27%</td>
                        <td>7%</td>
                    </tr>
                    <tr>
                        <td>MD Rakib Hasan</td>
                        <td>24</td>
                        <td>18</td>
                        <td>5</td>
                        <td>1</td>
                        <td>75%</td>
                        <td>21%</td>
                        <td>4%</td>
                    </tr>
                    <tr>
                        <td>Emarot Hossain</td>
                        <td>12</td>
                        <td>12</td>
                        <td>0</td>
                        <td>0</td>
                        <td>100%</td>
                        <td>0%</td>
                        <td>0%</td>
                    </tr>
                    <tr>
                        <td>Sazzad Hossain Anik</td>
                        <td>22</td>
                        <td>18</td>
                        <td>2</td>
                        <td>2</td>
                        <td>82%</td>
                        <td>9%</td>
                        <td>9%</td>
                    </tr>
                    <tr class="fw-bold text-center">
                        <td colspan="2">Malibag HUB Total</td>
                        <td>73</td>
                        <td>58</td>
                        <td>11</td>
                        <td>4</td>
                        <td>79%</td>
                        <td>15%</td>
                        <td>5%</td>
                    </tr>

                    <!-- Old Town HUB -->
                    <tr>
                        <td rowspan="2">Old Town HUB</td>
                        <td>Md. Suman Ahmed</td>
                        <td>16</td>
                        <td>11</td>
                        <td>4</td>
                        <td>1</td>
                        <td>69%</td>
                        <td>25%</td>
                        <td>6%</td>
                    </tr>
                    <tr>
                        <td>Md. Minhaj</td>
                        <td>27</td>
                        <td>24</td>
                        <td>1</td>
                        <td>2</td>
                        <td>89%</td>
                        <td>4%</td>
                        <td>7%</td>
                    </tr>
                    <tr class="fw-bold text-center">
                        <td colspan="2">Old Town HUB Total</td>
                        <td>43</td>
                        <td>35</td>
                        <td>5</td>
                        <td>3</td>
                        <td>81%</td>
                        <td>12%</td>
                        <td>7%</td>
                    </tr>

                    <!-- Grand Total -->
                    <tr class="table-dark fw-bold text-center">
                        <td colspan="2">Grand Total</td>
                        <td>260</td>
                        <td>197</td>
                        <td>47</td>
                        <td>16</td>
                        <td>76%</td>
                        <td>18%</td>
                        <td>6%</td>
                    </tr>
                </tbody>
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
