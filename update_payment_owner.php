<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['owner_id']) && isset($_POST['bill_type'])) {
    $owner_id = $_POST['owner_id'];
    $bill_type = $_POST['bill_type'];

    if ($owner_id != $_SESSION['oid']) {
        die("Unauthorized access.");
    }
    
    $dbhost = 'localhost';
    $dbname = 'postgres';
    $dbuser = 'postgres';
    $dbpass = 'Keerthi23';

    $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
    if (!$conn) {
        die("Connection failed. Error: " . pg_last_error());
    }

    switch ($bill_type) {
        
        case 'water':
        case 'electricity':
            $table = ($bill_type === 'water') ? 'water_bills' : 'electricity_bills';
            $column = 'payment_status';
            $condition = "owner_id = $1";
            break;
        case 'maintenance':
            $table = 'maintenance_fees';
            $column = 'payment_status';
            $condition = "owner_id = $1";
            break;
        default:
            die("Invalid bill type.");
    }

    $amount_query =  "SELECT amount FROM $table WHERE owner_id = $1";
    $amount_result = pg_query_params($conn, $amount_query, array($owner_id));
    if (!$amount_result) {
        die("Error retrieving amount: " . pg_last_error($conn));
    }
    $amount_row = pg_fetch_assoc($amount_result);
    if (!$amount_row) {
        die("No amount found for the given tenant_id.");
    }
    $amount = $amount_row['amount'];

    

    $owner_info_query = "SELECT user_name AS name, email, phone_no AS phone FROM owners WHERE oid = $1";
    $owner_info_result = pg_query_params($conn, $owner_info_query, array($owner_id));
    if (!$owner_info_result) {
        die("Error retrieving owner info: " . pg_last_error($conn));
    }
    $owner_info_row = pg_fetch_assoc($owner_info_result);
    if (!$owner_info_row) {
        die("No tenant found for the given owner_id.");
    }
    $name = $owner_info_row['name'];
    $email = $owner_info_row['email'];
    $phone = $owner_info_row['phone'];

    $query = "UPDATE $table SET $column = 'Paid' WHERE $condition";
    $result = pg_query_params($conn, $query, array($owner_id));
    if (!$result) {
        die("Error updating payment status: " . pg_last_error($conn));
    }

    $payment_date = date('Y-m-d'); 
    $insert_payment_query = "INSERT INTO payments (payment_id, owner_id, bill_type, paid_amount, payment_date, name, email, phone) VALUES (DEFAULT, $1, $2, $3, $4, $5, $6, $7)";
    $insert_payment_result = pg_query_params($conn, $insert_payment_query, array($owner_id, $bill_type, $amount, $payment_date, $name, $email, $phone));
    if (!$insert_payment_result) {
        die("Error inserting payment details: " . pg_last_error($conn));
    }

    $_SESSION['payment_status'] = 'Paid';
    $_SESSION['paid_amount'] = $amount;
    $_SESSION['bill_type'] = $bill_type;
    $_SESSION['cardholder_name'] = $name;

    header("Location: payment_success.php");
    exit();

    pg_close($conn);

} else {
    echo "owner ID or Bill Type not provided.";
}
?>
