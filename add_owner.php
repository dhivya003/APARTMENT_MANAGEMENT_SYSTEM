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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['oid'];
    $owner_name = $_POST['user_name'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $move_in_date = $_POST['move_in_date'];
    $flat_no = $_POST['flat_no'];
    $password = $_POST['password'];
    
    $query = "INSERT INTO owners (oid, user_name, email, phone_no, move_in_date, flat_no, password, building_id) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
    $result = pg_query_params($conn, $query, array($user_id, $owner_name, $email, $phone_no, $move_in_date, $flat_no, $password, $_POST['building_id']));
    
    if ($result) {
        $_SESSION['message'] = "Owner added successfully.";
        echo "<script>alert('Owner added successfully.'); window.location.href='add_owner.php';</script>";
    } else {
        $_SESSION['message'] = "Error adding owner: " . pg_last_error($conn);
        echo "<script>alert('Error adding owner: " . pg_last_error($conn) . "'); window.location.href='add_owner.php';</script>";
    }

    pg_close($conn);
    exit();
}

$query = "SELECT building_id, building_name FROM buildings";
$result = pg_query($conn, $query);
if (!$result) {
    die("Query failed. Error: " . pg_last_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Owner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 50px 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        select {
            width: 100%;
            padding: 12px; 
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .back-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            float: right;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add Owner</h1>
    
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="oid">Owner ID:</label>
        <input type="text" name="oid" id="oid" required>

        <label for="user_name">Owner Name:</label>
        <input type="text" name="user_name" id="user_name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="phone_no">Phone No:</label>
        <input type="text" name="phone_no" id="phone_no" required>

        <label for="move_in_date">Move-in Date:</label>
        <input type="date" name="move_in_date" id="move_in_date" required>

        <label for="flat_no">Flat No:</label>
        <input type="text" name="flat_no" id="flat_no" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="building_id">Select Building:</label><br>
        <select name="building_id" id="building_id" required>
            <option value="">Select Building</option>
            <?php
            while ($row = pg_fetch_assoc($result)) {
                echo '<option value="' . $row['building_id'] . '">' . $row['building_name'] . '</option>';
            }
            ?>
        </select><br><br>
        <input type="submit" value="Add Owner"><br><br>
        <a href="admin_dashboard.php"><button type="button" class="back-button"> Prev Page</button></a>

    </form>
</div>

</body>
</html>
