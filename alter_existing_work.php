<?php
session_start();

if (!isset($_SESSION['sname']) || !isset($_SESSION['password'])) {
    header("Location: staff.php");
    exit();
}

$dbhost = 'localhost';
$dbname = 'postgres';
$dbuser = 'postgres';
$dbpass = 'Keerthi23';

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
if (!$conn) {
    die("Connection failed. Error: " . pg_last_error());
}

$emp_id = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $emp_id = $_POST['emp_id'];

    $pendingWorksQuery = "SELECT work_id, work_description, flat_no FROM works WHERE emp_id = $1 AND status = 'pending'";
    $pendingWorksResult = pg_query_params($conn, $pendingWorksQuery, array($emp_id));

    if (pg_num_rows($pendingWorksResult) == 0) {
        echo "<script>alert('No pending works found for the given Employee ID.');</script>";
    }
}

if (isset($_POST['update_work'])) {
    if (isset($_POST['work_id']) && isset($_POST['emp_id'])) {
        $work_id = $_POST['work_id'];
        $emp_id = $_POST['emp_id'];

        $updateQuery = "UPDATE works SET status = 'completed', completed_date = NOW() WHERE work_id = $1";
        $result = pg_query_params($conn, $updateQuery, array($work_id));

        if ($result) {
            echo "<script>alert('Work status updated successfully.');</script>";
        } else {
            echo "<script>alert('Failed to update work status: " . pg_last_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Employee ID or Work ID is missing.');</script>";
    }
}

pg_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alter Existing Work</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            width: 80%;
            padding: 10px;
        }

        .employee-details {
            width: 45%;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .employee-details h2 {
            text-align: center;
            color: #333;
        }

        .employee-box {
            border: 1px solid #ccc;
            padding: 0.5px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .employee-box:hover {
            background-color: #f9f9f9;
            cursor: pointer;
        }

        .center-box {
            width: 45%;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .center-box h2 {
            text-align: center;
            color: #333;
        }

        .center-box form label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        .center-box form input[type="text"],
        .center-box form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .center-box form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .center-box form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            float: right;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="employee-details">
        <h2>Employee Details</h2>
        <?php
        $dbhost = 'localhost';
        $dbname = 'postgres';
        $dbuser = 'postgres';
        $dbpass = 'Keerthi23';

        $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
        if (!$conn) {
            die("Connection failed. Error: " . pg_last_error());
        }

        $employeeQuery = "SELECT emp_id, emp_name FROM employee";
        $employeeResult = pg_query($conn, $employeeQuery);

        if (pg_num_rows($employeeResult) > 0) {
            while ($row = pg_fetch_assoc($employeeResult)) {
                echo '<div class="employee-box">';
                echo '<p>ID: ' . htmlspecialchars($row['emp_id']) . '</p>';
                echo '<p>Name: ' . htmlspecialchars($row['emp_name']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No employees found.</p>';
        }

        pg_close($conn);
        ?>
    </div>

    <div class="center-box">
        <h2>Alter Existing Work</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="emp_id">Employee ID:</label>
            <input type="text" id="emp_id" name="emp_id" value="<?php echo htmlspecialchars($emp_id); ?>" required><br><br>
            <input type="submit" name="submit" value="Fetch Pending Works"><br><br>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            $emp_id = $_POST['emp_id'];

            $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
            if (!$conn) {
                die("Connection failed. Error: " . pg_last_error());
            }

            $pendingWorksQuery = "SELECT work_id, work_description, flat_no FROM works WHERE emp_id = $1 AND status = 'pending'";
            $pendingWorksResult = pg_query_params($conn, $pendingWorksQuery, array($emp_id));

            if ($pendingWorksResult) {
                if (pg_num_rows($pendingWorksResult) > 0) {
                    echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">';
                    echo '<input type="hidden" name="emp_id" value="' . htmlspecialchars($emp_id) . '">';
                    while ($row = pg_fetch_assoc($pendingWorksResult)) {
                        echo '<input type="radio" name="work_id" value="' . htmlspecialchars($row['work_id']) . '" required>';
                        echo htmlspecialchars($row['work_description']) . " (Flat No: " . htmlspecialchars($row['flat_no']) . ")<br>";
                    }
                    echo '<input type="submit" name="update_work" value="Update Work Status">';
                    echo '</form>';
                } else {
                    echo '<p>No pending works found for the selected employee.</p>';
                }
            } else {
                echo "Error fetching pending works: " . pg_last_error($conn);
            }

            pg_close($conn);
        }

        if (isset($_POST['update_work'])) {
            if (isset($_POST['work_id']) && isset($_POST['emp_id'])) {
                $work_id = $_POST['work_id'];
                $emp_id = $_POST['emp_id'];

                $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
                if (!$conn) {
                    die("Connection failed. Error: " . pg_last_error());
                }

                $updateQuery = "UPDATE works SET status = 'completed', completed_date = NOW() WHERE work_id = $1";
                $result = pg_query_params($conn, $updateQuery, array($work_id));

                if ($result) {
                    echo "<script>alert('Work status updated successfully.');</script>";
                } else {
                    echo "<script>alert('Failed to update work status: " . pg_last_error($conn) . "');</script>";
                }

                pg_close($conn);
            } else {
                echo "<script>alert('Employee ID or Work ID is missing.');</script>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
