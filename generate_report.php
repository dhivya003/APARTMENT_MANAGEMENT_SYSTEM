<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top:40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .message {
            text-align: center;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenant_id = $_POST['tenant_id'];
        $report_month = $_POST['report_month'];
        $report_year = $_POST['report_year'];

        $dbhost = 'localhost';
        $dbname = 'postgres';
        $dbuser = 'postgres';
        $dbpass = 'Keerthi23';

        $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
        if (!$conn) {
            die("Connection failed. Error: " . pg_last_error());
        }

        $query = "SELECT * FROM payments WHERE tenant_id = $1 
                  AND EXTRACT(MONTH FROM payment_date) = $2 AND EXTRACT(YEAR FROM payment_date) = $3";

        $result = pg_query_params($conn, $query, array($tenant_id, $report_month, $report_year));

        if ($result) {
            if (pg_num_rows($result) > 0) {
                echo "<h2>Monthly Report for Tenant</h2>";
                echo "<table>
                        <tr>
                            <th>Payment Date</th>
                            <th>Amount Paid</th>
                            <th>Bill Type</th>
                        </tr>";
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['payment_date']}</td>
                            <td>{$row['paid_amount']}</td>
                            <td>{$row['bill_type']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='message'>No payments found for Tenant $tenant_id in $report_month/$report_year</p>";
            }
        } else {
            echo "<p class='message'>Error executing query: " . pg_last_error($conn) . "</p>";
        }

        pg_close($conn);
    } else {
        header("Location: monthly_report_form.php");
        exit;
    }
    ?>
    <p class="message"><a href="monthly_report_form.php">Generate Another Report</a></p>
</div>

</body>
</html>
