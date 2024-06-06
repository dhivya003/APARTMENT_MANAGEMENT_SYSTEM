<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report Form</title>
    <style>
        .form-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Monthly Report Form</h2>
        <form action="generate_report_owners.php" method="post">
            <label for="owner_id">Owner ID:</label>
            <input type="text" id="owner_id" name="owner_id" required>

            <label for="report_month">Month:</label>
            <input type="number" id="report_month" name="report_month" min="1" max="12" required>

            <label for="report_year">Year:</label>
            <input type="number" id="report_year" name="report_year" min="1900" max="9999" required>

            <input type="submit" value="Generate Report">
        </form>
    </div>
</body>
</html>
