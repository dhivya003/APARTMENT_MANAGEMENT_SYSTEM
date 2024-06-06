
<?php
$servername = "localhost";
$username = "postgres";
$password = "Keerthi23";
$dbname = "postgres";

$conn = pg_connect("host=$servername dbname=$dbname user=$username password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

$email = $_POST['email'];
$userType = $_POST['userType'];

$query = "SELECT check_email_exists('$email')";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error in SQL query: " . pg_last_error());
}

$emailExists = pg_fetch_result($result, 0, 0);

pg_close($conn);

if ($emailExists == 't') {
    if ($userType === 'admin') {
        header("Location: auth_admin.php");
    } elseif ($userType === 'resident') {
        header("Location: resident.php");
    } elseif ($userType === 'staff') {
        header("Location: staff.php");
    }
} else {
    header("Location: login_page.php?error=1");
}
exit();
?>