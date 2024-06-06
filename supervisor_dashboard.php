


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <style>
       body {
    font-family: sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: url('admin2.jpg');
}

.container {
    text-align: center;
    background-color: blue;
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

.tenant_bill {
    background-color: DodgerBlue;
}

.owner_bill {
    background-color: MediumSeaGreen;
}

.assign_works {
    background-color: orange;
}

.alter_work {
    background-color: purple;
}

.address_complaints {
    background-color: crimson;
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
        <h1>Welcome, Supervisor!</h1>
        <ul>
            <li><a href="supervisor.php"><button class="tenant_bill">Add Tenant Bills</button></a></li>
            <li><a href="owners_bill_manage.php"><button class="owner_bill">Add Owner Bills</button></a></li>
            <li><a href="assign_works.php"><button class="assign_works">Assign new works</button></a></li>
            <li><a href="alter_existing_work.php"><button class="alter_work">Alter work</button></a></li>
            <li><a href="address_complaints.php"><button class="address_complaints">Address complaints</button></a></li>
        </ul>
        <a href="staff.php"><button type="button" class="back-button">Prev Page <<</button></a>
    </div>

</body>
</html>