<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tenant_id']) && isset($_POST['bill_type'])) {
    $tenant_id = $_POST['tenant_id'];
    $bill_type = $_POST['bill_type'];

    if ($tenant_id != $_SESSION['tid']) {
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
        case 'rent':
            $table = 'tenants';
            $column = 'payment_status';
            $condition = "tid = $1";
            break;
        case 'water':
        case 'electricity':
            $table = ($bill_type === 'water') ? 'water_bills' : 'electricity_bills';
            $column = 'payment_status';
            $condition = "tenant_id = $1";
            break;
        case 'maintenance':
            $table = 'maintenance_fees';
            $column = 'payment_status';
            $condition = "tenant_id = $1";
            break;
        default:
            die("Invalid bill type.");
    }

    $amount_query = ($bill_type === 'rent') ? "SELECT rent_amt AS amount FROM tenants WHERE tid = $1" : "SELECT amount FROM $table WHERE tenant_id = $1";
    $amount_result = pg_query_params($conn, $amount_query, array($tenant_id));
    if (!$amount_result) {
        die("Error retrieving amount: " . pg_last_error($conn));
    }
    $amount_row = pg_fetch_assoc($amount_result);
    if (!$amount_row) {
        die("No amount found for the given tenant_id.");
    }
    $amount = $amount_row['amount'];

    $tenant_info_query = "SELECT user_name AS name, email, phone_no AS phone FROM tenants WHERE tid = $1";
    $tenant_info_result = pg_query_params($conn, $tenant_info_query, array($tenant_id));
    if (!$tenant_info_result) {
        die("Error retrieving tenant info: " . pg_last_error($conn));
    }
    $tenant_info_row = pg_fetch_assoc($tenant_info_result);
    if (!$tenant_info_row) {
        die("No tenant found for the given tenant_id.");
    }
    $name = $tenant_info_row['name'];
    $email = $tenant_info_row['email'];
    $phone = $tenant_info_row['phone'];

    $query = "UPDATE $table SET $column = 'Paid' WHERE $condition";
    $result = pg_query_params($conn, $query, array($tenant_id));
    if (!$result) {
        die("Error updating payment status: " . pg_last_error($conn));
    }

    $payment_date = date('Y-m-d'); 
    $insert_payment_query = "INSERT INTO payments (payment_id, tenant_id, bill_type, paid_amount, payment_date, name, email, phone) VALUES (DEFAULT, $1, $2, $3, $4, $5, $6, $7)";
    $insert_payment_result = pg_query_params($conn, $insert_payment_query, array($tenant_id, $bill_type, $amount, $payment_date, $name, $email, $phone));
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
    echo "Tenant ID or Bill Type not provided.";
}
?>
