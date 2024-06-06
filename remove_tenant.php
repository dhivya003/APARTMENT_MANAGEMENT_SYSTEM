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

if (isset($_GET['tenant_id'])) {
    $tenant_id = $_GET['tenant_id'];


    $query = "DELETE FROM tenants WHERE tid = $1";
    $result = pg_query_params($conn, $query, array($tenant_id));

    if ($result) {
        $affected_rows = pg_affected_rows($result);
        
        if ($affected_rows > 0) {
            $_SESSION['message'] = "Tenant removed successfully.";
        } else {
            $_SESSION['message'] = "Tenant not found.";
        }
    } else {
        $_SESSION['message'] = "Error removing tenant: " . pg_last_error($conn);
    }

    pg_close($conn);

   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Tenant</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
    <script>
        window.onload = function() {
            <?php if (isset($_SESSION['message'])): ?>
                alert("<?php echo $_SESSION['message']; ?>");
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Remove Tenant</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
           
            <input type="text" id="tenant_id" name="tenant_id" placeholder=" Enter Tenant ID" required>
            <button type="submit">Remove Tenant</button>
        </form>
        <a href="admin_dashboard.php" class="back-button">Prev Page</a>
    </div>
</body>
</html>
