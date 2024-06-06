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

$queryComplaints = "SELECT complaint, status, response, created_at FROM complaints WHERE user_name = $1 ORDER BY created_at DESC";
$resultComplaints = pg_query_params($conn, $queryComplaints, array($userName));

if (!$resultComplaints) {
  die("Query failed. Error: " . pg_last_error($conn));
  pg_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Complaints</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f0f0f0;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      background-color: #fff;
      padding: 50px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .complaint {
      margin-top: 20px;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f9f9f9;
    }
    .response {
      margin-top: 10px;
      padding: 10px;
      border-left: 3px solid #007bff;
      background-color: #e9e9e9;
    }
    h1, h2 {
      text-align: center;
    }
    .back-button {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      float:right;
      margin-top:20px;
    }
    .back-button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Responses</h1>
    <?php while ($complaint = pg_fetch_assoc($resultComplaints)): ?>
      <div class="complaint">
        <p><strong>Complaint:</strong> <?php echo $complaint['complaint']; ?></p>
        <p><strong>Status:</strong> <?php echo $complaint['status']; ?></p>
        <?php if ($complaint['response']): ?>
          <div class="response">
            <p><strong>Response:</strong> <?php echo $complaint['response']; ?></p>
            <p><strong>Response Date:</strong> <?php echo $complaint['created_at']; ?></p>
          </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
    <a href="tenant_dashboard.php"><button type="button" class="back-button">Go Back</button></a><br><br>

  </div>
</body>
</html>
