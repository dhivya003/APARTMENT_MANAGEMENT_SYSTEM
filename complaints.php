
<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: resident.php");
    exit();
}

$userName = $_SESSION['user_name'];

$dbhost = 'localhost';
$dbname = 'postgres';
$dbuser = 'postgres';
$dbpass = 'Keerthi23';

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
if (!$conn) {
    die("Connection failed. Error: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint = $_POST['complaint'];

    $query = "INSERT INTO complaints (user_name, complaint, status) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $query, array($userName, $complaint, 'pending'));

    if (!$result) {
        die("Failed to raise complaint: " . pg_last_error($conn));
    } else {
        echo "<script>alert('Complaint raised successfully.');</script>";
       
          
            exit();

    }
}

$queryTenant = "SELECT * FROM tenants WHERE user_name = $1";
$resultTenant = pg_query_params($conn, $queryTenant, array($userName));
$tenantDetails = pg_fetch_assoc($resultTenant);
if (!$tenantDetails) {
    die("Tenant details not found.");
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raise Complaint</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        textarea {
            width: 90%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            float:right;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        button:hover {
            filter: brightness(85%); 
        }
        .back-button {
            display: block;
            width: fit-content;
            background-color: #007bff;
            color: white;
            padding: 10px 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
            margin-left: auto;
            margin-right: auto;
            text-decoration: none;
            float:left;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Raise Complaint</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="complaint">Complaint:</label><br>
        <textarea id="complaint" name="complaint" rows="4" cols="50" required>Enter your complaint here..</textarea><br><br>
        <input type="submit" value="Submit">
  
    <a href="tenant_dashboard.php"><button type="button" class="back-button"> Prev Page</button>
    </form>

</div>

</body>
</html>