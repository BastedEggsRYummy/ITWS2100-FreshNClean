<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Guidelines</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="stylesheet" href="/resources/index.css">
    <link rel ="stylesheet" href="/resources/headerFooter.css" >
    <link rel="stylesheet" href="/resources/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>

<body>
  <?php 
      include("../../resources/header.php");
  ?>

    <div id="content">
        <div class="p-4 p-md-5 mb-4 rounded text-body-emphasis bg-body-secondary">
            <h1 id="CGTitle">Fresh 'n Clean's Community Guidelines</h1>
            <p>Here at Fresh 'n Clean we value everyone's logistical and legal well-being. Please look through our various documents to make yourself informed, even if the paradox of the active user says you never will.</p>
            <p>Thank you for your time.</p>
        </div>
        
        <h2 class="sub-heading">The Big Ones</h2>

        <a href="index.php"><h3>Community Guidelines</h3></a>
        <a href="privacyPolicy.php"><h3>Privacy Policy</h3></a>
        <a href="faq.php"><h3>Frequently Asked Questions</h3></a>

        <h2 class="sub-heading">More Specific Documents</h2>

        <a href="copyrightPolicy.php"><h3>Copyright Policy</h3></a>
        <a href="feedbackPolicy.php"><h3>Feedback Policy</h3></a>
        <a href="firearmsProhibitionPolicy.php"><h3>Firearms Prohibition Policy</h3></a>
        <a href="nonDiscriminationPolicy.php"><h3>Non Discrimination Policy</h3></a>
        <a href="thirdPartyDataRequestsGuidelines.php"><h3>Third Party Data Requests Guidelines</h3></a>
        <a href="unsolicitedIdeaPolicy.php"><h3>Unsolicited Idea Policy</h3></a>
        <a href="userGeneratedContentTerms.php"><h3>User Generated Content Policy</h3></a>
        <a href="zeroTolerancePolicy.php"><h3>Zero Tolerance Policy</h3></a>

    </div>

  <?php
    include("../../resources/footer.php");
  ?>
</body>
</html>