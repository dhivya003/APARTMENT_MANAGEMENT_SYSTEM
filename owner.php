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

$query = "SELECT  user_name,oid, email, phone_no, move_in_date, flat_no,payment_status FROM owners WHERE user_name = $1";
$result = pg_query_params($conn, $query, array($userName));

if (!$result) {
  die("Query failed. Error: " . pg_last_error($conn));
}

$ownerDetails = pg_fetch_assoc($result); 
if(!$ownerDetails){
  die("Owner details not found");
}
$_SESSION['oid']=$ownerDetails['oid'];
$queryWaterBill = "SELECT amount, payment_status FROM water_bills WHERE owner_id = $1";
$resultWaterBill = pg_query_params($conn, $queryWaterBill, array($ownerDetails['oid']));
$waterBillDetails = pg_fetch_assoc($resultWaterBill);

$waterBillAmount = 0;
$waterBillPaymentStatus = 0;
if ($waterBillDetails) {
  $waterBillAmount = $waterBillDetails['amount'];
  $waterBillPaymentStatus = $waterBillDetails['payment_status'];
}

$queryElectricityBill = "SELECT amount, payment_status FROM electricity_bills WHERE owner_id = $1";
$resultElectricityBill = pg_query_params($conn, $queryElectricityBill, array($ownerDetails['oid']));
$electricityBillDetails = pg_fetch_assoc($resultElectricityBill);

$electricityBillAmount = 0;
$electricityBillPaymentStatus = 0;
if ($electricityBillDetails) {
  $electricityBillAmount = $electricityBillDetails['amount'];
  $electricityBillPaymentStatus = $electricityBillDetails['payment_status'];
}

$queryMaintenanceFees = "SELECT amount, payment_status FROM maintenance_fees WHERE owner_id = $1";
$resultMaintenanceFees = pg_query_params($conn, $queryMaintenanceFees, array($ownerDetails['oid']));
$maintenanceFeesDetails = pg_fetch_assoc($resultMaintenanceFees);

$maintenanceFeesAmount = 0;
$maintenanceFeesPaymentStatus = 0;
if ($maintenanceFeesDetails) {
  $maintenanceFeesAmount = $maintenanceFeesDetails['amount'];
  $maintenanceFeesPaymentStatus = $maintenanceFeesDetails['payment_status'];
}


$totalBillAmount = $waterBillAmount + $electricityBillAmount + $maintenanceFeesAmount;

$balance = (($waterBillDetails['payment_status'] == 'Not Paid') ? $waterBillDetails['amount'] : 0) +
            (($electricityBillDetails['payment_status'] == 'Not Paid') ? $electricityBillDetails['amount'] : 0) +
            (($maintenanceFeesDetails['payment_status'] == 'Not Paid') ?  $maintenanceFeesDetails['amount'] : 0);
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Details</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f0f0f0;
    }
    .owner-details {
      max-width: 800px;
      margin: 0 auto;
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
    .total {
      margin-top: 20px;
      font-weight: bold;
    }
    .balance {
      color: <?php echo ($balance >= 0) ? 'green' : 'red'; ?>;
    }
    .pay-button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .pay-button:hover {
      background-color: #0056b3;
    }

    .back-button {
      display: inline-block;
      background-color: #007bff;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      margin-bottom:10px;
      cursor: pointer;
      float: right; 
    }

    .back-button:hover {
      background-color: #0056b3;
    }
    h2{
      text-align:center;
    }

  </style>
</head>
<body>
  <div class="owner-details">
    <h2>PAY BILLS</h2>
    <table>
      <tr>
        <th>Name</th>
        <td><?php echo $ownerDetails['user_name']; ?></td>
      </tr>
      <tr>
        <th>ID</th>
        <td><?php echo $ownerDetails['oid']; ?></td>
      </tr>
      <tr>
        <th>Flat Number</th>
        <td><?php echo $ownerDetails['flat_no']; ?></td>
      </tr>
      <tr>
        <th>Phone Number</th>
        <td><?php echo $ownerDetails['phone_no']; ?></td>
      </tr>
      <tr>
        <th>Move-in Date</th>
        <td><?php echo $ownerDetails['move_in_date']; ?></td>
      </tr>
      <tr>
        <th>Email</th>
        <td><?php echo $ownerDetails['email']; ?></td>
      </tr>
    </table>
    <br><br>
    <div>
      <h2>Bills Information</h2>
      <table>
        <tr>
          <th>Bill Type</th>
          <th>Amount</th>
          <th>Payment Status</th>
          <th>Pay</th>
        </tr>
       
          <td>Water Bill</td>
          <td><?php echo $waterBillAmount; ?></td>
          <td style="color: <?php echo ($waterBillDetails['payment_status']== 'Paid') ? 'green' : 'red'; ?>">
            <?php echo ($waterBillDetails['payment_status']== 'Paid') ? 'Paid' : 'Unpaid'; ?>
            <td>  <?php echo ($waterBillDetails['payment_status']== 'Paid') ? '-' : 
           
            
           ' <form action="payment_owner.php" method="post">
           <input type="hidden" id="owner_id" name="owner_id" value="<?php echo $owner_id; ?>">
           <input type="hidden" id="bill_type" name="bill_type" value="water">
             <input type="submit" value="Pay" class="pay-button">
           </form>';?>
      
           </td>
          </td>
        </tr>
        <tr>
          <td>Electricity Bill</td>
          <td><?php echo $electricityBillAmount; ?></td>
          <td style="color: <?php echo ($electricityBillDetails['payment_status']== 'Paid') ? 'green' : 'red'; ?>">
            <?php echo ($electricityBillDetails['payment_status']== 'Paid') ? 'Paid' : 'Unpaid'; ?>
          <td>  <?php echo ($electricityBillDetails['payment_status']== 'Paid') ? '-' : 
           
            
              ' <form action="payment_owner.php" method="post">
              <input type="hidden" id="owner_id" name="owner_id" value="<?php echo $owner_id; ?>">
              <input type="hidden" id="bill_type" name="bill_type" value="electricity">
                <input type="submit" value="Pay" class="pay-button">
              </form>';?>
         
              </td>
            </td>
        </tr>
        <tr>
          <td>Maintenance Fees</td>
          <td><?php echo $maintenanceFeesAmount; ?></td>
          <td style="color: <?php echo ($maintenanceFeesDetails['payment_status']== 'Paid') ? 'green' : 'red'; ?>">
            <?php echo ($maintenanceFeesDetails['payment_status'] == 'Paid') ? 'Paid' : 'Unpaid'; ?>
            <td>  <?php echo ($maintenanceFeesDetails['payment_status'] == 'Paid') ? '-' : 
           
            
           ' <form action="payment_owner.php" method="post">
           <input type="hidden" id="owner_id" name="owner_id" value="<?php echo $owner_id; ?>">
           <input type="hidden" id="bill_type" name="bill_type" value="maintenance">
           <input type="submit" value="Pay" class="pay-button">
           </form>';?>
      
           </td>
          </td>
        </tr>
  </table>
      <div class="total">
        <p>Total Bill Amount: <?php echo $totalBillAmount; ?></p>
        <p>Balance of Unpaid Bills: <span class="balance"><?php echo $balance; ?></span></p>
  </form>
          <a href="owner_dashboard.php"><button type="button" class="back-button">Go to Previous</button></a>
        </form>
  </div>
    </div>
  </div>
</body>
</html>