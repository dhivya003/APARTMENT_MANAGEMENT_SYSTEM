<?php
session_start();

$dbhost = 'localhost';
$dbname = 'postgres';
$dbuser = 'postgres';
$dbpass = 'Keerthi23';

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
if (!$conn) {
    die("Connection failed. Error: " . pg_last_error());
}

$monthly_income_data = array();

$current_year = date('Y');

pg_query($conn, "BEGIN");

for ($month = 1; $month <= 12; $month++) {
    $cursor_name = "monthly_income";
    
    $query = "DECLARE $cursor_name CURSOR FOR 
              SELECT bill_type, COALESCE(SUM(paid_amount), 0) AS total_amount 
              FROM payments 
              WHERE EXTRACT(MONTH FROM payment_date) = $1 
              AND EXTRACT(YEAR FROM payment_date) = $2 
              GROUP BY bill_type";
    pg_query_params($conn, $query, array($month, $current_year));

    $query = "FETCH ALL IN $cursor_name";
    $result = pg_query($conn, $query);

    $total_rent = $total_water_bill = $total_electricity_bill = $total_maintenance_fees = 0;

    while ($row = pg_fetch_assoc($result)) {
        switch ($row['bill_type']) {
            case 'rent':
                $total_rent = $row['total_amount'];
                break;
            case 'water':
                $total_water_bill = $row['total_amount'];
                break;
            case 'electricity':
                $total_electricity_bill = $row['total_amount'];
                break;
            case 'maintenance':
                $total_maintenance_fees = $row['total_amount'];
                break;
        }
    }

    $total_income = $total_rent + $total_water_bill + $total_electricity_bill + $total_maintenance_fees;

    $monthly_income_data[] = array(
        'month' => $month,
        'total_rent' => $total_rent,
        'total_water_bill' => $total_water_bill,
        'total_electricity_bill' => $total_electricity_bill,
        'total_maintenance_fees' => $total_maintenance_fees,
        'total_income' => $total_income
    );

    $query = "CLOSE $cursor_name";
    pg_query($conn, $query);
}

pg_query($conn, "COMMIT");

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Income Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #007bff;
            color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        tfoot {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #999;
        }
        .back-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Monthly Income Report for <?php echo $current_year; ?></h1>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Rent</th>
                    <th>Total Water Bill</th>
                    <th>Total Electricity Bill</th>
                    <th>Total Maintenance Fees</th>
                    <th>Total Income</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monthly_income_data as $data): ?>
                    <tr>
                        <td><?php echo $data['month']; ?></td>
                        <td><?php echo $data['total_rent']; ?></td>
                        <td><?php echo $data['total_water_bill']; ?></td>
                        <td><?php echo $data['total_electricity_bill']; ?></td>
                        <td><?php echo $data['total_maintenance_fees']; ?></td>
                        <td><?php echo $data['total_income']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    </div>
</body>
<a href="admin_dashboard.php"><button type="button" class="back-button">Previous Page</button></a>

</html>

