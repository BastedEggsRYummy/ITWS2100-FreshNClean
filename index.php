<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh n' Clean</title>
    <link rel="stylesheet" href="./resources/index.css">
    <link rel ="stylesheet" href="/resources/headerFooter.css" >
    <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Encode+Sans+Expanded:wght@100;200;300;400;500;600;700;800;900&family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap');
    </style>
</head>
<body>
    <?php 
        include("resources/header.php");
    ?>

    <div class="parallax-container" id="start">
        <div class="parallax-background"></div>
        <div class="content">
            <div class="full-screen">
                <h1>Fresh n' Clean</h1>
                <p>Stress-free laundry runs for anyone, anytime.</p>
                <div class="button-container">
                    <button class="btn btn-dark" onclick="window.location.href='./login/createAccount.php';">Create Account</button>
                    <button class="btn btn-dark" onclick="scrollToSection('more')">Learn More</button>
                </div>
            </div>
        </div>
    </div>

    <div class="parallax-container" id="more">
        <div class="parallax-background"></div>
        <div class="content">
            <h2 class="sub-heading">Why Choose Us?</h2>
            <div class="columns">
                <div class="attribute">
                    <h3>Time</h3>
                    <p>Busy? Spend the time doing more important tasks and not worrying about your laundry!</p>
                </div>
                <div class="attribute">
                    <h3>Affordable</h3>
                    <p>Reasonable pricing for everyone's budget!</p>
                </div>
                <div class="attribute">
                    <h3>Convenient</h3>
                    <p>Forgot to do laundry? Schedule a repeated laundry run for every week or every other week. Your choice!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="parallax-container">
        <div class="parallax-background"></div>
        <div class="content">
            <h2 class="sub-heading">FAQ</h2>
            <div class="columns">
                <div class="FAQ">
                    <h3>How does this work?</h3>
                    <p>Select a time and laundry amount and one of our employees will pick up your laundry. After the laundry is done, they will drop it off at the designated location.</p>
                    <h3>How is the price calculated?</h3>
                    <p>The cost depends on the amount of laundry you have and the distance from your location to the nearest laundromat.</p>
                    <h3>Is this available around the world?</h3>
                    <p>We're curently only available in the cities located on our Location page but we're looking to expand further.</p>
                    <button class="button" onclick="window.location.href='./Schedule/schedule.php';">Schedule a Run Now!</button><br>
                </div>
                <div class="FAQ">
                    <img src="./resources/laundry.jpg" alt="Laundry Image">
                </div>
            </div>
        </div>
    </div>

    <div class="parallax-container">
        <div class="parallax-background"></div>
        <div class="content">
            <h2 class="sub-heading" id="partners">Our Partners</h2>
            <div class="columns" style="gap: 20px;">
                <div class="card">
                    <img src="./resources/troyLaundromat.jpg" alt="Troy Laundromat">
                    <p><b>Troy Laundromat</b><br>1999 Burdett Ave Troy, NY 12180</p>
                </div>
                <div class="card">
                    <img src="./resources/quadLaundromat.jpg" alt="Quad Laundry Room">
                    <p><b>Quad Laundry Room</b><br>1999 Burdett Ave Troy, NY 12180</p>
                </div>
            </div>
        </div>
    </div>

    <div class="parallax-container">
        <div class="parallax-background"></div>
        <div class="content">
            <h2 class="sub-heading" id="reviews">Recent Reviews</h2>
            <div class="columns">
                <div class="review">
                    <div class="star-rating" data-rating="5"></div>
                    <p>"Amazing. 10/10 would do it again"</p>
                    <p><b>John Doe</b></p>
                </div>
                <div class="review">
                    <div class="star-rating" data-rating="4"></div>
                    <p>"Love being able to schedule my weekly laundry runs. One less thing to think about."</p>
                    <p><b>Kirby</b></p>
                </div>
                <div class="review">
                    <div class="star-rating" data-rating="5"></div>
                    <p>"Super affordable. Love this!!"</p>
                    <p><b>Mario</b></p>
                </div>
            </div>
        </div>
    </div>

    <div class="parallax-container">
        <div class="parallax-background"></div>
        <div class="content">
            <div id="schedule-laundry">
                <h3>Schedule your laundry run today!</h3><br>
                <button class="button" onclick="window.location.href='./login/createAccount.php';">Create Account</button>
            </div>
        </div>
    </div>

    <button id="scrollToTopBtn" onclick="scrollToTop()">Top</button>

    <?php 
        include("./resources/footer.php");
    ?>

    <script src="resources/index.js"></script>
</body>
</html>