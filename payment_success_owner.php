<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            margin-top: 100px;
        }

        .tick-symbol {
            width: 200px;
            height: 150px;
        }

        p {
            font-size: 24px;
            color: #333;
            margin-top: 20px;
        }
        .receipt{
            background-color: blue;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 20px;
            transition: background-color 0.3s;
        } 
        
        button {
            background-color:green;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="tick_symbol1.png" alt="Tick Symbol" class="tick-symbol">
        <p>YOUR PAYMENT IS SUCCESSFUL</p>
        <audio autoplay>
            <source src="success_sound1.mp3" type="audio/mpeg">
        </audio>
        <audio autoplay>
            <source src="success_sound2.mp3" type="audio/mpeg">
        </audio>
        <br>
        <a href="receipt_owner.php">
            <button class="receipt">CLICK HERE TO VIEW RECEIPT</button>
            <a href="receipt.php?amount=<?php echo isset($_GET['amount']) ? $_GET['amount'] : ''; ?>&bill_type=<?php echo isset($_GET['bill_type']) ? $_GET['bill_type'] : ''; ?>">
            <button type="button" class="back-button">BACK TO HOME</button></a>

        </a>
    </div>
</body>
</html>
