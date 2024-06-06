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

$query = "SELECT o.oid, o.user_name, o.email, o.phone_no,
                 w.amount AS water_bill_amount, w.payment_status AS water_bill_paid,
                 e.amount AS electricity_bill_amount, e.payment_status AS electricity_bill_paid,
                 m.amount AS maintenance_fee_amount, m.payment_status AS maintenance_fee_paid,b.building_name
          FROM owners o
          LEFT JOIN water_bills w ON o.oid = w.owner_id
          LEFT JOIN electricity_bills e ON o.oid = e.owner_id
          LEFT JOIN maintenance_fees m ON o.oid = m.owner_id
          LEFT JOIN buildings b ON o.building_id = b.building_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error retrieving owner details: " . pg_last_error($conn));
}

$owners = [];

while ($row = pg_fetch_assoc($result)) {
    $row['water_bill_paid'] = ($row['water_bill_paid'] == 1) ? 'Paid' : 'Not Paid';
    $row['electricity_bill_paid'] = ($row['electricity_bill_paid'] == 1) ? 'Paid' : 'Not Paid';
    $row['maintenance_fee_paid'] = ($row['maintenance_fee_paid'] == 1) ? 'Paid' : 'Not Paid';
    $owners[] = $row;
}

pg_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Details</title>
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

<h1>Owner Details</h1>

<?php if (count($owners) > 0): ?>

    <table>
        <thead>
        <tr>
            <th>Owner ID</th>
            <th>Building Name</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Water Bill Amount</th>
            <th>Water Bill Paid</th>
            <th>Electricity Bill Amount</th>
            <th>Electricity Bill Paid</th>
            <th>Maintenance Fee Amount</th>
            <th>Maintenance Fee Paid</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($owners as $owner): ?>
            <tr>
                <td><?= $owner['oid'] ?></td>
                <td><?= $owner['building_name'] ?></td>
                <td><?= $owner['user_name'] ?></td>
                <td><?= $owner['email'] ?></td>
                <td><?= $owner['phone_no'] ?></td>
                <td><?= $owner['water_bill_amount'] ?></td>
                <td class="<?= ($owner['water_bill_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= $owner['water_bill_paid'] ?>
                </td>
                <td><?= $owner['electricity_bill_amount'] ?></td>
                <td class="<?= ($owner['electricity_bill_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= $owner['electricity_bill_paid'] ?>
                </td>
                <td><?= $owner['maintenance_fee_amount'] ?></td>
                <td class="<?= ($owner['maintenance_fee_paid'] == 'Not Paid') ? 'not-paid' : '' ?>">
                    <?= $owner['maintenance_fee_paid'] ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p>No owners found.</p>

<?php endif; ?>

<br>
<a href="admin_dashboard.php"><button type="button" class="back-button">Previous Page</button></a>

</body>
</html>
