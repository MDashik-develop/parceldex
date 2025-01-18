<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Parcel Report</title>
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

            <h1 class="text-center">Pickup Parcel Report</h1>
            <p class="text-center">Date: 13.01.2025</p>

            <div class="row">
                <div class="col-md-8">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Sl</th>
                                <th>Merchant</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Purity The Hijab Store</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Labonno</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Kizuna By Skin Care</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Kizuna By Zaara</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Thelagari</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Tortaza Khabar</td>
                                <td>11</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Nom Nom</td>
                                <td>4</td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Taqwanur</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Different Look</td>
                                <td>12</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Azreen's</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Buy And Sell</td>
                                <td>38</td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Gadget Bangla</td>
                                <td>36</td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>Toymoy</td>
                                <td>22</td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td>Cosmic Bags</td>
                                <td>68</td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Indian sarees Mela</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>Emoji</td>
                                <td>14</td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>Zigzag Shopping</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td>Happy Mommy</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>19</td>
                                <td>Anzaar Lifestyle</td>
                                <td>22</td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td>Dress fair And Accessories</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>21</td>
                                <td>Nj Skin And Body care</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>22</td>
                                <td>Agistar 2</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>23</td>
                                <td>Pretty Bags</td>
                                <td>8</td>
                            </tr>
                            <tr>
                                <td>24</td>
                                <td>Megashop Ltd</td>
                                <td>52</td>
                            </tr>
                            <tr>
                                <td>25</td>
                                <td>Imrose Collection</td>
                                <td>81</td>
                            </tr>
                            <tr>
                                <td>26</td>
                                <td>T'z Store</td>
                                <td>31</td>
                            </tr>
                            <tr>
                                <td>27</td>
                                <td>sarees by Sakyla</td>
                                <td>16</td>
                            </tr>
                            <tr>
                                <td>28</td>
                                <td>China gallery</td>
                                <td>4</td>
                            </tr>
                            <tr>
                                <td>29</td>
                                <td>glamshoppers</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>30</td>
                                <td>Ayojon</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>31</td>
                                <td>Glowella E</td>
                                <td>2</td>
                            </tr>
                            <tr>
                                <td>32</td>
                                <td>99 Shop Bangladesh</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>33</td>
                                <td>Evaly Sena Food & Beverage</td>
                                <td>24</td>
                            </tr>
                            <tr>
                                <td>34</td>
                                <td>Nasfiya's look</td>
                                <td>3</td>
                            </tr>
                            <tr>
                                <td>35</td>
                                <td>Keedlee Wholesale 1</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>36</td>
                                <td>Lishaniya</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>37</td>
                                <td>Muslim ghrito vander</td>
                                <td>1</td>
                            </tr>
                            <tr>
                                <td>38</td>
                                <td>Area Change</td>
                                <td>4</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <td colspan="2" class="text-end fw-bold">Total</td>
                                <td>480</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="col-md-4">
                    <h4>Hub Summary</h4>
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Hub Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mirpur Hub</td>
                                <td>72</td>
                            </tr>
                            <tr>
                                <td>Mohakhali Hub</td>
                                <td>31</td>
                            </tr>
                            <tr>
                                <td>Badda Hub</td>
                                <td>38</td>
                            </tr>
                            <tr>
                                <td>Malibagh Hub</td>
                                <td>44</td>
                            </tr>
                            <tr>
                                <td>Uttara Hub</td>
                                <td>57</td>
                            </tr>
                            <tr>
                                <td>Dhanmondi Hub</td>
                                <td>44</td>
                            </tr>
                            <tr>
                                <td>Mohammadpur Hub</td>
                                <td>33</td>
                            </tr>
                            <tr>
                                <td>Jatrabari Hub</td>
                                <td>41</td>
                            </tr>
                            <tr>
                                <td>Old Town Hub</td>
                                <td>21</td>
                            </tr>
                            <tr>
                                <td>ISD Total</td>
                                <td>381</td>
                            </tr>
                            <tr>
                                <td>OSD Total</td>
                                <td>99</td>
                            </tr>
                            <tr>
                                <td>Hold</td>
                                <td>1</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <td class="text-end fw-bold">Total</td>
                                <td>480</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

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