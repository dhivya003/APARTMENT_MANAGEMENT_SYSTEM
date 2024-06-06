<?php
session_start();

if (!isset($_SESSION['emp_name']) || !isset($_SESSION['password'])) {
    header("Location: staff.php");
    exit();
}

$emp_name = $_SESSION['emp_name'];
$password = $_SESSION['password'];

$dbhost = 'localhost';
$dbname = 'postgres';
$dbuser = 'postgres';
$dbpass = 'Keerthi23';

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
if (!$conn) {
    die("Connection failed. Error: " . pg_last_error());
}

$queryEmpDetails = "SELECT * FROM employee WHERE emp_name = $1 AND password = $2";
$resultEmpDetails = pg_query_params($conn, $queryEmpDetails, array($emp_name, $password));
if (!$resultEmpDetails) {
    die("Query failed. Error: " . pg_last_error($conn));
}
$empDetails = pg_fetch_assoc($resultEmpDetails);
if (!$empDetails) {
    die("Employee not found.");
}

$queryWorks = "SELECT work_description, flat_no, status, completed_date 
               FROM works 
               WHERE emp_id = $1 AND (status = 'pending' OR (status = 'completed' AND completed_date >= NOW() - INTERVAL '2 weeks'))";
$resultWorks = pg_query_params($conn, $queryWorks, array($empDetails['emp_id']));
if (!$resultWorks) {
    die("Query failed. Error: " . pg_last_error($conn));
}

$queryPendingCount = "SELECT COUNT(*) AS pending_count FROM works WHERE emp_id = $1 AND status = 'pending'";
$resultPendingCount = pg_query_params($conn, $queryPendingCount, array($empDetails['emp_id']));
if (!$resultPendingCount) {
    die("Query failed. Error: " . pg_last_error($conn));
}
$pendingCount = pg_fetch_assoc($resultPendingCount)['pending_count'];

$queryCompletedCount = "SELECT COUNT(*) AS completed_count FROM works WHERE emp_id = $1 AND status = 'completed' AND completed_date >= NOW() - INTERVAL '2 weeks'";
$resultCompletedCount = pg_query_params($conn, $queryCompletedCount, array($empDetails['emp_id']));
if (!$resultCompletedCount) {
    die("Query failed. Error: " . pg_last_error($conn));
}
$completedCount = pg_fetch_assoc($resultCompletedCount)['completed_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .employee-details {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        
        button:hover {
            filter: brightness(85%); 
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
    </style>
</head>
<body>
    <h2>Employee Dashboard</h2>
    
    <div class="employee-details">
        <h3>Employee Details</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($empDetails['emp_name']); ?></p>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($empDetails['emp_id']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($empDetails['emp_email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($empDetails['phone']); ?></p>
    
    </div>

    <table>
        <tr>
            <th>Work Description</th>
            <th>Flat Number</th>
            <th>Status</th>
            <th>Completed Date</th>
        </tr>
        <?php while ($row = pg_fetch_assoc($resultWorks)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['work_description']); ?></td>
            <td><?php echo htmlspecialchars($row['flat_no']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['completed_date']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div>
        <p><strong>Number of Pending Jobs:</strong> <?php echo $pendingCount; ?></p>
        <p><strong>Number of Jobs Completed:</strong> <?php echo $completedCount; ?></p>
        
    </div>
    <a href="staff.php"><button type="button" class="back-button" > Prev Page</button>
    <?php
    
    pg_free_result($resultWorks);
    pg_free_result($resultPendingCount);
    pg_free_result($resultCompletedCount);
    pg_close($conn);
    ?>
</body>
</html>