<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
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
        <h1 id="CGTitle">Resources</h1>
        <p>
            Once in a while we get asked a question or two - so here's your questions and some answers!
        </p>
    </div>

    <h2 class="sub-heading">Where do the clothes go?</h2>
    <p>
        You choose the laundromat, and your driver brings it there. You can keep track of where it's going and when it's back as well!
    </p>

    <h2 class="sub-heading">What products/process do they use?</h2>
    <p>
        We have some standard products we recommend, which you can find here:
    </p>
    <ul>
        <li><a href="https://www.amazon.com/Amazon-Basics-Concentrated-Detergent-Lavender/dp/B09CLS6DYH/ref=sr_1_1?crid=TKC1SQC8X0CU" target="_blank">Amazon Basics Laundry Soap</a></li>
        <li><a href="https://www.amazon.com/Tide-Laundry-Detergent-Spring-Meadow/dp/B0BJMV9BXJ/ref=sr_1_6?crid=ZKKDHVC0DI68" target="_blank">Tide Pods</a></li>
        <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank">Organic Cage-free Tide Pods</a> (They exist, I swear)</li>
    </ul>
    <p>
        While these are recommended, they are not required. If the clothes do not meet your personal cleanliness requirement, they certainly do not meet ours. Reach out to us at <a href="mailto:customersupportfreshnclean@gmail.com">customersupportfreshnclean@gmail.com</a>.
    </p>

    <h2 class="sub-heading">What happens if my products are stolen?</h2>
    <p>
        We take claims of stolen laundry very seriously. If you have a claim, please email customer support at <a href="mailto:customersupportfreshnclean@gmail.com">customersupportfreshnclean@gmail.com</a>. Make sure to provide all relevant details, including:
    </p>
    <ul>
        <li>The time of the incident</li>
        <li>A description of the stolen elements</li>
        <li>Any supporting evidence (e.g., photos, receipts, communications)</li>
    </ul>
    <p>
        Fresh n' Clean will investigate thoroughly and consult both parties. We'll keep everyone updated throughout the process. If there are legal and insurance claims, we may have to consult legal services. For requesting data, see our <a href="thirdPartyDataRequestsGuidelines.php">Third Party Data Requests Form</a>.
    </p>
    <p>
        Actions taken from a claim can be very serious, up to and including permanent suspension from our service. See our <a href="index.php">Community Guidelines</a> for more information. Additionally, we recommend ensuring your items are packed securely and labeled properly, as well as retaining receipts and records as preventive measures.
    </p>

    <h2 class="sub-heading">What happens if someone reports something stolen that isn't?</h2>
    <p>
        This is very malicious, and we take it very seriously. We follow the process listed above, and if a report is found to be false, we will take appropriate action against the reporter.
    </p>

    <h2 class="sub-heading">What happens if my clothes are tampered with?</h2>
    <p>
        Again, this would go through our report system at <a href="mailto:customersupportfreshnclean@gmail.com">customersupportfreshnclean@gmail.com</a> and will likely result in a ban for the person who tampered with the clothes.
    </p>
</div>

    <?php
      include("../../resources/footer.php");
    ?>
</body>
</html>