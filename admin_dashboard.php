<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family:  sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background:url('admin2.jpg');
        }

        .container {
            text-align: center;
            background-color: ; 
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.9);
            background: rgba(255, 255, 255, 0.8);
            width: 400px;
            max-width: 90%;
        }

        h1 {
            color: #333333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            color: #666666;
            font-size: 18px;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap; 
        }

        li {
            margin-bottom: 15px;
            margin-right: 10px; 
        }

        a {
            text-decoration: none;
        }

        button {
            display: inline-block;
            width: 180px;
            padding: 12px;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            outline: none;
        }

        .add-tenant {
            background-color: DodgerBlue; 
        }

        .add-owner {
            background-color: #5E5E5E; 
        }

        .view-tenants {
            background-color: #689F38; 
        }

        .view-owners {
            background-color: #EF6C00; 
        }
        .view-occupancy {
            background-color: brown; 
        }
        .view-past-residents{
            background-color:#37474F;
        }
        .remove_tenant{
            background-color:crimson;
        }
        .remove_owner{
            background-color:#00838F;
        }
        .view-income{
            background-color:#87681A;
        }

        button:hover {
            filter: brightness(85%); 
        }
        .back-button {
            display: block;
            width: fit-content;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome, Admin!</h1>
        <ul>
            <li><a href="add_tenant.php"><button class="add-tenant">Add Tenants</button></a></li>
            <li><a href="add_owner.php"><button class="add-owner">Add Owners</button></a></li>
            <li><a href="remove_tenant.php"><button class="remove_tenant">Remove Tenants</button></a></li>
            <li><a href="remove_owner.php"><button class="remove_owner">Remove Owners</button></a></li>

            <li><a href="view_tenants.php"><button class="view-tenants">View Tenants</button></a></li>
            <li><a href="view_owners.php"><button class="view-owners">View Owners</button></a></li>
            <li><a href="displaycount_admin.php"><button class="view-occupancy">View Occupancy</button></a></li>
            <li><a href="view_past_residents.php"><button class="view-past-residents">View Past-Residents</button></a></li>
            <li><a href="view_income.php"><button class="view-income">View INCOME</button></a></li>

        </ul>
        <a href="login_page.php"><button type="button" class="back-button"> Prev Page</button>
    </div>

</body>
</html>
