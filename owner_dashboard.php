<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .button {
            padding: 12px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Owner Dashboard</h1>
        <div class="button-container">
            <a href="owner.php" class="button">View Bills</a>
            <a href="complaints_owner.php" class="button" style="background-color: #ff9800;">Raise Complaint</a>
            <a href="view_response.php" class="button" style="background-color: #2196f3;">View Responses</a>
            <a href="monthly_report_form_owner.php" class="button" style="background-color: #f44336;">Monthly Report</a>
        </div>
        <div class="footer">
        
            <a href="resident.php">Logout</a>
        </div>
    </div>

</body>
</html>
