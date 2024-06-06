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

$query = "SELECT t.tid, t.user_name, t.email, t.phone_no, t.move_in_date, t.flat_no, t.rent_amt,
                 t.payment_status AS rent_amount_paid,
                 w.amount AS water_bill_amount, w.payment_status AS water_bill_paid,
                 e.amount AS electricity_bill_amount, e.payment_status AS electricity_bill_paid,
                 m.amount AS maintenance_fee_amount, m.payment_status AS maintenance_fee_paid,b.building_name
          FROM tenants t
          LEFT JOIN water_bills w ON t.tid = w.tenant_id
          LEFT JOIN electricity_bills e ON t.tid = e.tenant_id
          LEFT JOIN maintenance_fees m ON t.tid = m.tenant_id
           LEFT JOIN buildings b ON t.building_id = b.building_id";

$result = pg_query($conn, $query);

if (!$result) {
    die("Error retrieving tenant details: " . pg_last_error($conn));
}

$tenants = [];

while ($row = pg_fetch_assoc($result)) {
    $row['rent_amount_paid'] = ($row['rent_amount_paid'] == 'Paid') ? 'Paid' : 'Not Paid';
    $row['water_bill_paid'] = ($row['water_bill_paid'] == 'Paid') ? 'Paid' : 'Not Paid';
    $row['electricity_bill_paid'] = ($row['electricity_bill_paid'] == 'Paid') ? 'Paid' : 'Not Paid';
    $row['maintenance_fee_paid'] = ($row['maintenance_fee_paid'] == 'Paid') ? 'Paid' : 'Not Paid';
    $tenants[] = $row;
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            text-align: left;
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
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
        .not-paid {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Tenant Details</h1>

<?php if (count($tenants) > 0): ?>

    <table>
        <thead>
        <tr>
            <th>Tenant ID</th>
            <th>Building Name</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Move-in Date</th>
            <th>Flat Number</th>
            <th>Rent Amount</th>
            <th>Rent Amount Paid</th>
            <th>Water Bill Amount</th>
            <th>Water Bill Paid</th>
            <th>Electricity Bill Amount</th>
            <th>Electricity Bill Paid</th>
            <th>Maintenance Fee Amount</th>
            <th>Maintenance Fee Paid</th>
            
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tenants as $tenant): ?>
            <tr>
                <td><?= htmlspecialchars($tenant['tid']) ?></td>
                <td><?= htmlspecialchars($tenant['building_name']) ?></td>
                <td><?= htmlspecialchars($tenant['user_name']) ?></td>
                <td><?= htmlspecialchars($tenant['email']) ?></td>
                <td><?= htmlspecialchars($tenant['phone_no']) ?></td>
                <td><?= htmlspecialchars($tenant['move_in_date']) ?></td>
                <td><?= htmlspecialchars($tenant['flat_no']) ?></td>
                <td><?= htmlspecialchars($tenant['rent_amt']) ?></td>
                <td class="<?= ($tenant['rent_amount_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= htmlspecialchars($tenant['rent_amount_paid']) ?>
                </td>
                <td><?= htmlspecialchars($tenant['water_bill_amount']) ?></td>
                <td class="<?= ($tenant['water_bill_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= htmlspecialchars($tenant['water_bill_paid']) ?>
                </td>
                <td><?= htmlspecialchars($tenant['electricity_bill_amount']) ?></td>
                <td class="<?= ($tenant['electricity_bill_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= htmlspecialchars($tenant['electricity_bill_paid']) ?>
                </td>
                <td><?= htmlspecialchars($tenant['maintenance_fee_amount']) ?></td>
                <td class="<?= ($tenant['maintenance_fee_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= htmlspecialchars($tenant['maintenance_fee_paid']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No tenants found.</p>

<?php endif; ?>

<br>
<a href="admin_dashboard.php"><button type="button" class="back-button">Previous Page</button></a>

</body>
</html>
   

