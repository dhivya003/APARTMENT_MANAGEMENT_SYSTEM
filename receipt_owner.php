<?php
session_start();

if (!isset($_SESSION['paid_amount']) || !isset($_SESSION['bill_type'])) {
    die("No payment information available.");
}

$amount = $_SESSION['paid_amount'];
$bill_type = $_SESSION['bill_type'];
$cardholder_name = isset($_SESSION['cardholder_name']) ? $_SESSION['cardholder_name'] : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .receipt {
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .details {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
            margin: 20px 0;
        }

        p {
            margin: 5px 0;
            color: #555;
        }

        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        .back-button, .download-button {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            text-decoration: none;
            font-size: 16px;
        }

        .back-button:hover, .download-button:hover {
            background-color: #0056b3;
        }

        .receipt-footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h1>Payment Receipt</h1>
        <div class="details">
            <p><span class="detail-label">Name:</span> <?php echo htmlspecialchars($cardholder_name); ?></p>
            <p><span class="detail-label">Amount Paid:</span> <?php echo htmlspecialchars($amount); ?></p>
            <p><span class="detail-label">Bill Type:</span> <?php echo htmlspecialchars($bill_type); ?></p>
            <p><span class="detail-label">Date & Time:</span> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><span class="detail-label">Recipient:</span> EasyApart</p>
        </div>
        <a href="owner.php" class="back-button">BACK TO HOME</a>
        <div class="receipt-footer">
            Thank you for your payment!
        </div>
    </div>
</body>
</html>
