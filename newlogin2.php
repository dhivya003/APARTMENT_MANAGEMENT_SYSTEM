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


  $queryemployees = "SELECT emp_name FROM employee WHERE emp_name = $1 AND password = $2";
  $resultemployees = pg_query_params($conn, $queryemployees, array($username, $password));

  if (!$resultemployees) {
    die("Query failed. Error: " . pg_last_error($conn));
  }


  $querysupervisors = "SELECT sname FROM supervisor WHERE sname = $1 AND password = $2";
  $resultsupervisors = pg_query_params($conn, $querysupervisors, array($username, $password));

  if (!$resultsupervisors) {
    die("Query failed. Error: " . pg_last_error($conn));
  }


  if (pg_num_rows($resultemployees) > 0) {
   
    $_SESSION['emp_name'] = $username; 
    $_SESSION['password'] = $password;
    header("Location: employee.php");
    exit();
  } elseif (pg_num_rows($resultsupervisors) > 0) {
   
    $_SESSION['sname'] = $username; 
    $_SESSION['password'] = $password;
    header("Location: supervisor_dashboard.php");
    exit();
  } else {
   
    echo '<script>alert("Invalid credentials. Please try again."); window.location.href = "staff.php";</script>';
    exit();
  }


  pg_close($conn);
}
?>