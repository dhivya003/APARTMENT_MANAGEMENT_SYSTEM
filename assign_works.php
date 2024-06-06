<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Work</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            margin: 0 auto;
            width: 80%;
            padding: 20px;
        }

        .employee-list {
            width: 45%;
            background-color: #F5F5F5; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-right: 20px;
        }

        .employee-list h2 {
            text-align: center;
            color: #000000; 
            margin-bottom: 20px;
        }

        .employee-list ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .employee-list li {
            margin-bottom: 10px;
            background-color: #FFFFFF; 
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .employee-list li:hover {
            background-color: #F0F0F0; 
        }

        .works-list {
            margin-top: 10px;
            padding-left: 20px;
        }

        .form-container {
            width: 45%;
            background-color: #F0F0F0; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #000000; 
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            color: #000000;
        }

        .form-container input[type="text"],
        .form-container textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #000000;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-container input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            background-color: #000000; 
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #333333;
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
    <div class="employee-list">
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
            echo '<h2>Employee List</h2>';
            while ($row = pg_fetch_assoc($employeeResult)) {
                echo '<li>' . $row['emp_name'] . ' (ID: ' . $row['emp_id'] . ')';
                echo '<ul class="works-list">';
                
                $worksQuery = "SELECT work_description FROM works WHERE emp_id = " . $row['emp_id'];
                $worksResult = pg_query($conn, $worksQuery);
                
                if (pg_num_rows($worksResult) > 0) {
                    while ($workRow = pg_fetch_assoc($worksResult)) {
                        echo '<li>' . $workRow['work_description'] . '</li>';
                    }
                } else {
                    echo '<li>No works assigned</li>';
                }
                
                echo '</ul>';
                echo '</li>';
            }
        } else {
            echo '<p>No employees found.</p>';
        }

        pg_close($conn);
        ?>
    </div>

    <div class="form-container">
        <h2>SUPERVISOR WORK ASSIGNMENT</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="empId">Employee ID:</label>
            <input type="text" id="empId" name="empId" required><br><br>

            <label for="flatNo">Flat Number:</label>
            <input type="text" id="flatNo" name="flatNo" required><br><br>

            <label for="workDescription">Work Description:</label>
            <textarea id="workDescription" name="workDescription" required></textarea><br><br>

            <input type="submit" name="submit" value="Assign Work"><br><br>
            <a href="supervisor_dashboard.php"><button type="button" class="back-button">Go to Previous</button></a>
        </form>
    </div>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $empId = isset($_POST['empId']) ? $_POST['empId'] : null;
    $flatNo = isset($_POST['flatNo']) ? $_POST['flatNo'] : null;
    $workDescription = isset($_POST['workDescription']) ? $_POST['workDescription'] : null;
    $status = 'pending'; 

    $dbhost = 'localhost';
    $dbname = 'postgres';
    $dbuser = 'postgres';
    $dbpass = 'Keerthi23';

    $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
    if (!$conn) {
        die("Connection failed. Error: " . pg_last_error());
    }

      $employeeExistsQuery = "SELECT * FROM employee WHERE emp_id = $1";
      $employeeExistsResult = pg_query_params($conn, $employeeExistsQuery, array($empId));
      $employeeExistsRowCount = pg_num_rows($employeeExistsResult);
  
      if ($employeeExistsRowCount > 0) {
          $query = "INSERT INTO works (work_description, emp_id, flat_no, status) VALUES ($1, $2, $3, $4)";
          $result = pg_query_params($conn, $query, array($workDescription, $empId, $flatNo, $status));
  
          if ($result) {
              echo "<script>alert('Work assigned successfully.');</script>";
          } else {
              echo "<script>alert('Failed to assign work: " . pg_last_error($conn) . "');</script>";
          }
      } else {
          echo "<script>alert('Employee ID not found.');</script>";
      }
  
      pg_close($conn);
  }
  ?>
  </body>
  </html>
  