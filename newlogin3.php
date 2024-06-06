<?php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '1234567890') {
        $_SESSION['username'] = $username;

        header("Location: admin_dashboard.php");
        exit(); 
    } else {
      
        echo "Invalid username or password. Please try again.";
    }
} else {
    header("Location: auth_admin.php");
    exit(); 
}
?>
