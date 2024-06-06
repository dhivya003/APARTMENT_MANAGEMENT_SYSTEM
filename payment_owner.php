<?php
session_start();

if (isset($_SESSION['oid'])) {
    $owner_id = $_SESSION['oid'];
} else {
    echo "OWNER ID not found in session.";
    exit(); 
}

if (isset($_POST['bill_type'])) {
    $bill_type = $_POST['bill_type'];
} else {
    echo "Bill type not found in form data.";
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 15px;
        }
        .payment-method img {
            width: 60px;
            height: 60px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .payment-method img:hover {
            transform: scale(1.1);
        }
        .additional-details {
            margin-bottom: 15px;
        }
        .additional-details label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .additional-details input[type="text"],
        .additional-details input[type="email"],
        .additional-details input[type="tel"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Details</h1>

        <div class="payment-methods">
            <div class="payment-method">
                <img src="credit_card.jpg" alt="Credit/Debit Card" >
            </div>
            <div class="payment-method">
                <img src="google_pay.jpg" alt="Google Pay" >
            </div>
            <div class="payment-method">
                <img src="phonepe.jpg" alt="PhonePe" >
            </div>
        </div>

        <div class="additional-details">
            <h2>Additional Details</h2>
            <form id="paymentForm" action="update_payment_owner.php" method="post">
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="card-number" placeholder="Enter card number" required>

                <label for="expiry-date">Expiry Date:</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required>

                <label for="name">Cardholder Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter cardholder name" required>

                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter phone number" required>

                <input type="hidden" id="owner_id" name="owner_id" value="<?php echo $owner_id; ?>">
                <input type="hidden" id="bill_type" name="bill_type" value="<?php echo $bill_type; ?>">

                <input type="submit" value="Proceed to Payment" class="submit-button" onclick="proceedToPayment()">
            </form>
        </div>
        <script>
        function proceedToPayment() {
            document.getElementById('paymentForm').submit();
        }
    </script>
    </div>

</body>
</html>
