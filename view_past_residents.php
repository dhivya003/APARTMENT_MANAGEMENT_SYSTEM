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

$query = "SELECT pr.resident_id, pr.resident_name, pr.resident_type, pr.move_in_date, pr.move_out_date, b.building_name
FROM past_residents pr
LEFT JOIN buildings b ON pr.building_id = b.building_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Query failed. Error: " . pg_last_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Residents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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

<h1>Past Residents</h1>

<?php
if (pg_num_rows($result) > 0) {
    echo '<table>';
    echo '<tr><th>Resident ID</th><th>Name</th><th>Type</th><th>Move-in Date</th><th>Move-out Date</th><th>Building Name</th></tr>';
    while ($row = pg_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['resident_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['resident_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['resident_type']) . '</td>';
        echo '<td>' . htmlspecialchars($row['move_in_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['move_out_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['building_name']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No past residents found.';
}

pg_close($conn);
?><br>
<a href="admin_dashboard.php"><button type="button" class="back-button">Previous Page</button></a><br>

</body>
</html>
