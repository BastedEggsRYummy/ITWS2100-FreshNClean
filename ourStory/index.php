<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Our Story</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel ="stylesheet" href="index.css" >
        <link rel ="stylesheet" href="/resources/index.css" >
        <link rel ="stylesheet" href="/resources/headerFooter.css" >
        <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <!--NAVBAR-->
        <?php 
        include("../resources/header.php");
        ?>
    
        <div id="contentContainer">
            <div id="hero">
                <div>
                    <h1>Our Story</h1>
                    <p>
                        Laundry is that one chore that is necessary but takes way too long to finish. And as college students, we don’t want to lug our laundry baskets down the stairs, out the dorms, and walk in the freezing cold to the closest laundromat. And then have to wait  two hours for everything to wash and dry.
                    </p>
                    <p>
                        That’s why we created Fresh n’ Clean, a laundry delivery service. Think of food delivery but for laundry. Schedule a laundry run, have one of our drivers pick up your laundry, and let them do their magic. That way busy people like us can spend our precious time doing more important tasks.
                    </p>
                </div>
                <div>
                    <img src="OurStory.png" alt="placeholder image">
                </div>
            </div>
            <div id="formContainer">
                <div>
                    <h2 id="contact">Contact Us</h2>
                    <form id="form">
                        <div id="name">
                            <div>
                                <label for="fname">First Name</label><br>
                                <input type="text" id="fname" name="fname" placeholder="John" required><br>
                            </div>
                            <div>
                                <label for="lname">Last Name</label><br>
                                <input type="text" id="lname" name="lname" placeholder="Doe" required><br>
                            </div>
                        </div>
                        
                        <label for="email">Email</label><br>
                        <input type="email" id="email" name="email" placeholder="johndoe@gmail.com" required><br>
                        <label for="subject">Subject</label><br>
                        <input type="text" id="subject" name="subject" placeholder="Enter the subject" required><br>
                        <label for="message">Message</label><br>
                        <textarea id="message" name="message" placeholder="Enter your question or message" required></textarea><br>
                        <button id="submit" type="submit" class="btn btn-dark">Submit</button>
                    </form>
                </div>
                <div id="notificationContainer">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div id="notification"></div>
                </div>
            </div>
        </div>
        
        
        <?php 
        include("../resources/footer.php");
        ?>
        <script src="index.js"></script>
    </body>