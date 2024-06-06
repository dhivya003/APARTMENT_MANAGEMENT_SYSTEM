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

        #login-box {
            width:20%;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            
        }

        #login-box input {
            width: 100%;
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

        #login-button:hover {
            background-color: #45a049; 
        }
        .back-button {
            display: inline-block;
            background-color: black;
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
    <title>Resident Page</title>
</head>
<body>

    
    <img src="ap.jpg" alt="Background Image" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index:-9;">

    <div id="login-box">
        <h2 id="form-title">RESIDENT LOGIN</h2>
        <form action="newlogin1.php" method="post">
            <input type="text" name="user_name" placeholder="NAME" required><br><br>
           
            <input type="password" name="password" placeholder="PASSWORD" required><br><br>
            <input type="submit" name="login" value="Login"  id="login-button">
            
        </form><br>
        <a href="login_page.php"><button type="button" class="back-button"> GO TO HOME</button></a>

    </div>
  
</body>
</html>
