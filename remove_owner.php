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

if (isset($_GET['owner_id'])) {
    $owner_id = $_GET['owner_id'];


    $query = "DELETE FROM owners WHERE oid = $1";
    $result = pg_query_params($conn, $query, array($owner_id));

    if ($result) {
        $affected_rows = pg_affected_rows($result);
        
        if ($affected_rows > 0) {
            $_SESSION['message'] = "Owner removed successfully.";
        } else {
            $_SESSION['message'] = "Owner not found.";
        }
    } else {
        $_SESSION['message'] = "Error removing owner: " . pg_last_error($conn);
    }

    pg_close($conn);

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Owner</title>
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
        <h2>Remove Owner</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
          
            <input type="text" id="owner_id" name="owner_id" placeholder="Enter Owner ID" required>
            <button type="submit">Remove Owner</button>
        </form>
        <a href="admin_dashboard.php" class="back-button">Prev Page </a>
    </div>
</body>
</html>
