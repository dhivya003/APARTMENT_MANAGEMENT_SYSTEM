<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Apartment Management System</title>
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background: linear-gradient(rgba(0, 0, 0, 0.5), 
            rgba(0, 0, 0, 0.5)), url('apart3.jpg') 
            no-repeat  center/cover;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        nav {
            position: absolute;
            top: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
        }


        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 300;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #b0d4ff;
        }

        nav ul li a.button {
            background-color: #b0d4ff;
            color: #00457c;
            padding: 10px 20px;
            border-radius: 5px;
            
        }

        .header-content {
            max-width: 600px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .header-content h1 {
            font-size: 4em;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .header-content p {
            font-size: 1.2em;
            margin-bottom: 30px;
            font-weight: 300;
        }

        .header-content .button {
            background-color: #b0d4ff;
            color: #00457c;
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .header-content .button:hover {
            background-color: #99c7ff;
        }

        #gallery {
            text-align: center;
            background-color: #fff;
            padding: 20px 300px;
        }

        
        #amenities {
            text-align: center;
            background-color: #023341;
            padding: 20px 300px;
            color:whitesmoke;
        }

        #gallery h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #00457c;
        }

        .gallery-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding: 10px;
        }

        .gallery {
           
            margin: 5px;
            transition: transform 0.3s;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .gallery img {
            width: 300px;
            height: 300px;
        }
        .gallery1 img {
            width: 300px;
            height: 300px;
            border-radius:30px;
        }

        .gallery:hover,.gallery1:hover {
            transform: scale(1.05);
        }
     

        
        #about {
            padding: 50px;
            text-align: center;
        }

        #about h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #00457c;
        }

        .about-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .about-container p {
            font-size: 1.2em;
            line-height: 1.6;
        }

        #contact {
            padding: 50px;
            text-align: center;
            background-color: #1C564F;
        }

        #contact h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #00457c;
        }

        .contact-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .contact-details {
            text-align: left;
            margin-bottom: 20px;
        }

        .contact-details p {
            margin: 5px 0;
            font-size: 1.2em;
            color: #fff;
        }

        .contact-details p span {
            font-weight: bold;
            color: #fff;
        }

        

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }
    </style>
    
</head>
<body>
    <header>
        <nav>
          
            <ul>
                
                <li><a href="#gallery">Gallery</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#footer">Contact</a></li>
                <li><a href="login_page.php" class="button">Login</a></li>

            </ul>
        </nav>
        <div class="header-content">
            <h1>Welcome to<br>  EASY APART  <br></h1>
            <p>Live Easy, Live Smart</p>
        </div>
    </header>

    <section id="gallery">
        <h2>Our Apartments</h2>
        <div class="gallery-container">
            <div class="gallery"><img src="2ndapart.jpg" alt="Apartment 1"></div>
            <div class="gallery"><img src="a1.jpg" alt="Apartment 2"></div>
            <div class="gallery"><img src="a9.jpg" alt="Apartment 3"></div>
        </div>
    </section>
    <section id="amenities">
        <h2>AMENITIES</h2>
        <div class="gallery-container">
            <div class="gallery1"><img src="pool.jpg" alt="SWIMMING POOL"></div>
            <div class="gallery1"><img src="gym.jpg" alt="GYM"></div>
            <div class="gallery1"><img src="laundry.jpg" alt="LAUNDRY"></div>
            <div class="gallery1"><img src="parking.jpg" alt="PARKING"></div>
            <div class="gallery1"><img src="ground.jpeg" alt="ground"></div>

        </div>
    </section>
 
    <section id="about">
        <h2>About Us</h2>
        <div class="about-container">
            <p>Welcome to EASY APART! We're here to make apartment management easy for everyone involved.
                 Our platform allows all tenants and owners to pay bills online and raise complaints and 
                 allows administrators, supervisors, and employees to manage their tasks efficiently. 
                 Administrators can oversee all property management tasks, supervisors can efficiently assign 
                 tasks and handle tenants/owner complaints, and employees can view their assigned tasks and manage 
                 their workload effectively. Join us today!</p>
        </div>
    </section>
    
  
        


    <section id="footer">
    <footer>
    <div class="contact-details">
            <p>Contact us at: easyapart@gmail.com | +123456789</p>
        </div>
        <p>&copy; 2024 EASYAPART. All rights reserved.</p>
    </footer>
    </section>
</body>
</html>