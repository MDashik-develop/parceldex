<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            font-size: 20px;
            font-weight: bold;
        }

        .content {
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Dear valued Merchant {{ $merchant->name }},</div>
        <div class="content">
            <p>A Payment invoice has been generated on {{ now()->format('d/m/Y') }} and invoiced amount has been paid to
                your desired payment method.</p>
            <p><strong>Invoice:</strong> {{ $invoiceId }}<br>
                <strong>Amount Paid:</strong> Tk {{ number_format($amount, 2) }}
            </p>
        </div>
        <div class="footer">
            <p>Regards,<br>Parceldex Limited</p>
            <p>Any dispute must be notified in written within 15 days from the date of this invoice.</p>
        </div>
    </div>
</body>

</html>
