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

$buildings_query = "SELECT building_id, building_name, total_units FROM buildings";
$buildings_result = pg_query($conn, $buildings_query);
if (!$buildings_result) {
    die("Error fetching buildings data: " . pg_last_error($conn));
}

$building_data = array();

while ($building = pg_fetch_assoc($buildings_result)) { 
       $building_id = $building['building_id'];
    $building_name = $building['building_name'];

    $total_units_query = "SELECT total_units FROM buildings WHERE building_id = $building_id";
    $total_units_result = pg_query($conn, $total_units_query);
    if (!$total_units_result) {
        die("Error in counting total units for building $building_name: " . pg_last_error($conn));
    }
    $total_units = pg_fetch_assoc($total_units_result)['total_units'];

    $occupied_units_query = "SELECT COUNT(DISTINCT tid) AS occupied_units FROM tenants WHERE building_id = $building_id";
    $occupied_units_result = pg_query($conn, $occupied_units_query);
    if (!$occupied_units_result) {
        die("Error in counting occupied units for building $building_name: " . pg_last_error($conn));
    }
    $occupied_units = pg_fetch_assoc($occupied_units_result)['occupied_units'];

    $vacant_units = $total_units - $occupied_units;

    $vacancy_rate = ($vacant_units / $total_units) * 100;

    $income_query = "SELECT SUM(rent_amt + total_water_bill + total_electricity_bill + total_maintenance_fee) AS total_income FROM tenant_bills WHERE building_id = $building_id";
    $income_result = pg_query($conn, $income_query);
    if (!$income_result) {
        die("Error in  fetching total income for building $building_name: " . pg_last_error($conn));
    }
    $total_income = pg_fetch_assoc($income_result)['total_income'];

    $total_tenants_query = "SELECT COUNT(*) AS total_tenants FROM tenants WHERE building_id = $building_id";
    $total_tenants_result = pg_query($conn, $total_tenants_query);
    if (!$total_tenants_result) {
        die("Error in counting total tenants for building $building_name: " . pg_last_error($conn));
    }
    $total_tenants = pg_fetch_assoc($total_tenants_result)['total_tenants'];

    $total_owners_query = "SELECT COUNT(*) AS total_owners FROM owners WHERE building_id = $building_id";
    $total_owners_result = pg_query($conn, $total_owners_query);
    if (!$total_owners_result) {
        die("Error in counting total owners for building $building_name: " . pg_last_error($conn));
    }
    $total_owners = pg_fetch_assoc($total_owners_result)['total_owners'];

    $building_data[] = array(
        'building_name' => $building_name,
        'total_units' => $total_units,
        'occupied_units' => $occupied_units,
        'vacant_units' => $vacant_units,
        'vacancy_rate' => $vacancy_rate,
        'total_income' => $total_income,
        'total_tenants' => $total_tenants,
        'total_owners' => $total_owners
    );
}
pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Occupancy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f7f7f7;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .building {
            background-color: #196269; 
            border-radius: 10px;
            padding: 30px;
            width: 400px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

       

        .building h2 {
            text-align: center;
            margin-top: 0;
        }

        .statistic-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff; 
            transition: background-color 0.3s ease;
        }

        .building:hover .statistic-item {
            background-color: #f0f0f0; 
        }

        .statistic-item span.label {
            font-weight: bold;
            display: block;
        }

        .statistic-item span.value {
            font-size: 18px;
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
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1 style="text-align: center;">OCCUPANCIES</h1>

<div class="container">
    <?php foreach ($building_data as $building): ?>
        <div class="building">
            <h2><?= $building['building_name'] ?></h2>
            <div class="statistic">
                <div class="statistic-item">
                    <span class="label">Total Flats</span>
                    <span class="value"><?= isset($building['total_units']) ? $building['total_units'] : 'N/A' ?></span>
                </div>
                <div class="statistic-item">
                    <span class="label">Occupied Flats</span>
                    <span class="value"><?= isset($building['occupied_units']) ? $building['occupied_units'] : 'N/A' ?></span>
                </div>
                <div class="statistic-item">
                    <span class="label">Vacant Flats</span>
                    <span class="value"><?= isset($building['vacant_units']) ? $building['vacant_units'] : 'N/A' ?></span>
                </div>
                <div class="statistic-item">
                    <span class="label">Vacancy Rate (%)</span>
                    <span class="value"><?= isset($building['vacancy_rate']) ? round($building['vacancy_rate'], 2) : 'N/A' ?></span>
                </div>
                <div class="statistic-item">
                    <span class="label">Total Income (₹)</span>
                    <span class="value"><?= isset($building['total_income']) ? round($building['total_income'], 2) : 'N/A' ?></span>
                </div>
                <div class="statistic-item">
                    <span class="label">Total Tenants</span>
                    <span class="value"><?= isset($building['total_tenants']) ? $building['total_tenants'] : 'N/A' ?></span>
                </div>
                <div class="statistic-item">
                    <span class="label">Total Owners</span>
                    <span class="value"><?= isset($building['total_owners']) ? $building['total_owners'] : 'N/A' ?></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<a href="admin_dashboard.php"><button type="button" class="back-button">Previous Page</button></a>

</body>
</html>
