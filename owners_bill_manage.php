<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Bill Management for Owners</title>
</head>
<style>
    .center-box {
        width: 400px;
        margin: 0 auto;
        padding: 60px;
        background-color: #f0f0f0;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 5%;
    }

    .center-box h2 {
        text-align: center;
        color: #333;
    }

    .center-box form label {
        display: block;
        margin-bottom: 10px;
        color: #555;
    }

    .center-box form input[type="text"],
    .center-box form input[type="number"],
    .center-box form select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .center-box form input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .center-box form input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .back-button {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
        float: right;
    }

    .back-button:hover {
        background-color: #0056b3;
    }
</style>
<body>
<div class="center-box">
    <h2>Supervisor Bill Management For Owners</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="ownerId">Owner ID :</label>
        <input type="text" id="ownerId" name="ownerId" required><br><br>

        <label for="billType">Select Bill Type:</label>
        <select id="billType" name="billType" required>
            <option value="water">Water Bill</option>
            <option value="electricity">Electricity Bill</option>
            <option value="maintenance">Maintenance fees</option>
        </select><br><br>

        <label for="newAmount">New Amount (in Rs.):</label>
        <input type="number" id="newAmount" name="newAmount" min="0" required><br><br>

        <input type="submit" name="submit" value="Update Bill"><br><br>
        <a href="supervisor_dashboard.php"><button type="button" class="back-button">Go to Previous</button></a>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ownerId = isset($_POST['ownerId']) ? $_POST['ownerId'] : null;
    $billType = $_POST['billType'];
    $newAmount = $_POST['newAmount'];

    $dbhost = 'localhost';
    $dbname = 'postgres';
    $dbuser = 'postgres';
    $dbpass = 'Keerthi23';

    $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
    if (!$conn) {
        die("Connection failed. Error: " . pg_last_error());
    }

    switch ($billType) {
        case 'water':
        case 'electricity':
            $tableName = ($billType === 'water') ? 'water_bills' : 'electricity_bills';
            $updateColumn = 'amount'; 
            $idColumnName = 'owner_id'; 
            break;
       
        case 'maintenance':
            $tableName = 'maintenance_fees';
            $updateColumn = 'amount';
            $idColumnName = 'owner_id'; 
            break;
        default:
            echo "Invalid bill type.";
            exit;
    }

    $ownerExistsQuery = "SELECT * FROM owners WHERE oid = $1";
    $ownerExistsResult = pg_query_params($conn, $ownerExistsQuery, array($ownerId));
    $ownerExistsRowCount = pg_num_rows($ownerExistsResult);

    if ($ownerExistsRowCount > 0) {
        $checkBillQuery = "SELECT * FROM $tableName WHERE $idColumnName = $1";
        $checkBillResult = pg_query_params($conn, $checkBillQuery, array($ownerId));
        $checkBillRowCount = pg_num_rows($checkBillResult);

        if ($checkBillRowCount > 0) {
            $query = "UPDATE $tableName SET $updateColumn = $1 WHERE $idColumnName = $2";
            $result = pg_query_params($conn, $query, array($newAmount, $ownerId));
        } else {
            $query = "INSERT INTO $tableName ($idColumnName, $updateColumn) VALUES ($1, $2)";
            $result = pg_query_params($conn, $query, array($ownerId, $newAmount));
        }

        if ($result) {
            if ($newAmount == 0) {
                $paymentStatus = 'Paid';
            } else {
                $paymentStatus = 'Not Paid';
            }

            $updatePaymentStatusQuery = "UPDATE $tableName SET payment_status = $1 WHERE $idColumnName = $2";
            $resultPaymentStatus = pg_query_params($conn, $updatePaymentStatusQuery, array($paymentStatus, $ownerId));

            if ($resultPaymentStatus) {
                echo "<script>alert('Bill updated successfully.');</script>";
            } else {
                echo "<script>alert('Failed to update payment status: " . pg_last_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Failed to update or insert bill: " . pg_last_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Owner ID not found.');</script>";
    }

    pg_close($conn);
}
?>

</body>
</html>
