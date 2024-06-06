<?php
session_start();

if (!isset($_SESSION['sname']) || !isset($_SESSION['password'])) {
    header("Location: staff.php");
    exit();
}

$dbhost = 'localhost';
$dbname = 'postgres';
$dbuser = 'postgres';
$dbpass = 'Keerthi23';

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
if (!$conn) {
    die("Connection failed. Error: " . pg_last_error());
}

$query = "SELECT c.id, c.user_name, t.flat_no, c.complaint FROM complaints c
          JOIN tenants t ON c.user_name = t.user_name
          WHERE c.status = 'pending'";
$result = pg_query($conn, $query);
if (!$result) {
    die("Query failed. Error: " . pg_last_error($conn));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaintId = $_POST['complaint_id'];
    $response = $_POST['response'];

    $queryUpdate = "UPDATE complaints SET status = 'addressed', response = $1, addressed_at = NOW(), notification = TRUE WHERE id = $2";
    $resultUpdate = pg_query_params($conn, $queryUpdate, array($response, $complaintId));

    if (!$resultUpdate) {
        die("Failed to address complaint: " . pg_last_error($conn));
    } else {
        $queryTenantUpdate = "UPDATE tenants SET response = $1 WHERE user_name = (SELECT user_name FROM complaints WHERE id = $2)";
        $resultTenantUpdate = pg_query_params($conn, $queryTenantUpdate, array($response, $complaintId));
        
        if (!$resultTenantUpdate) {
            die("Failed to update tenant's record with response: " . pg_last_error($conn));
        } else {
            echo "<script>alert('Complaint addressed successfully ');</script>";
        }

        echo "<script>window.location.href = window.location.href;</script>";
    }
}

pg_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Complaints</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 5px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        
        .back-button {
            display: block;
            width: fit-content;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
           
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        h2{
            text-align:center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Address Complaints</h2>

    <table>
        <tr>
            <th>Complaint ID</th>
            <th>User Name</th>
            <th>Flat Number</th>
            <th>Complaint</th>
            <th>Action</th>
        </tr>
        <?php while ($row = pg_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['flat_no']); ?></td>
            <td><?php echo htmlspecialchars($row['complaint']); ?></td>
            <td>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                    <textarea name="response" placeholder="Enter your response..." required></textarea><br>
                    <input type="submit" value="Address Complaint">
                </form>

            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="supervisor_dashboard.php"><button type="button" class="back-button" > Prev Page<<</button></a>

</div>

</body>
</html>
