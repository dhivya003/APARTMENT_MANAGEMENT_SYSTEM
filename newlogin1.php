<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['user_name'];
  $password = $_POST['password'];

  $dbhost = 'localhost';
  $dbname = 'postgres';
  $dbuser = 'postgres';
  $dbpass = 'Keerthi23';

 
  $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
  if (!$conn) {
    die("Connection failed. Error: " . pg_last_error());
  }


  $queryTenants = "SELECT user_name FROM tenants WHERE user_name = $1 AND password = $2";
  $resultTenants = pg_query_params($conn, $queryTenants, array($username, $password));

  if (!$resultTenants) {
    die("Query failed. Error: " . pg_last_error($conn));
  }


  $queryOwners = "SELECT user_name FROM owners WHERE user_name = $1 AND password = $2";
  $resultOwners = pg_query_params($conn, $queryOwners, array($username, $password));

  if (!$resultOwners) {
    die("Query failed. Error: " . pg_last_error($conn));
  }


  if (pg_num_rows($resultTenants) > 0) {
  
    $_SESSION['user_name'] = $username; 
    $_SESSION['password'] = $password;
    header("Location: tenant_dashboard.php");
    exit();
  } elseif (pg_num_rows($resultOwners) > 0) {
  
    $_SESSION['user_name'] = $username; 
    $_SESSION['password'] = $password;
    header("Location: owner_dashboard.php");
    exit();
  } else {
   
    echo '<script>alert("Invalid credentials. Please try again."); window.location.href = "resident.php";</script>';
    exit();
  }

  pg_close($conn);
}
?>