<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
           
        }

        #background-img {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
         
            z-index: -1; 
        }

        #login-box {
            background: rgba(255, 255, 255, 0.8);
            width: 30%;
            height: 30%;
            padding: 80px 30px;
            padding-top:40px;
            border-radius: 10px;
            text-align: center;
            z-index: 1; 
        }

        #login-box select, #login-box input {
            width: 50%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 5px;
        }

        #login-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error_message{
            color:red;
            margin-top:10px;
        }
    </style>
    <title>Login Page</title>
</head>
<body>

<img id="background-img" src="staff.jpg" alt="Background Image">

<div id="login-box">
    <h2>Login</h2>
    <form id="login-form" action="login_email_check.php" method="POST">
        <input type="email" id="email" name="email" placeholder="ENTER YOUR EMAIL" required>
        <select id="user-type" name="userType" required>
            <option value="" disabled selected>SELECT USER TYPE</option>
            <option value="admin">ADMIN</option>
            <option value="resident">RESIDENT</option>
            <option value="staff">STAFF</option>
        </select>
        <br>
        <input type="submit" id="login-button" value="Login">
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error-message">Invalid email</p>';
        }
        ?>
    </form>
</div>

    

</body>
</html>