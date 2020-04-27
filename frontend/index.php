<?php include "templates/header.php"; ?>
<h1 style="text-align: center;">Mailchimp & Google Analytics Integration - Report Generation App</h1>
<div class="container">
    <div class="column" style="text-align: center;">
        <p><strong>View Report</strong></p>
        <hr>
        <form>
            <button class="main_button" type="button" onclick="location.href='php/viewReport/byMonth.php';">Monthly Summary</button>
            <button class="main_button" type="button" onclick="location.href='php/viewReport/byCampaign.php';">Campaign Breakdown</button>
            <button class="main_button" type="button" onclick="location.href='php/viewReport/bySubscriberList.php';">Subscriber List Breakdown</button>
        </form>
    </div>
    <div class="column" style="text-align: center;">
        <p><strong>Edit Report</strong></p>
        <hr>
        <form>
            <button class="main_button" type="button" onclick="location.href='php/editReport/addCampaignDetails.php';">Add Campaign Details</button>
            <button class="main_button" type="button" onclick="location.href='php/editReport/changeCampaignDetails.php';">Change Campaign Details</button>
            <button class="main_button" type="button" onclick="location.href='php/editReport/deleteCampaignDetails.php';">Delete Campaign Details</button>
        </form>
    </div>
    <div class="column" style="text-align: center;">
        <p><strong>Reports Comparison</strong></p>
        <hr>
        <form>
            <button class="main_button" type="button" onclick="location.href='php/reportComparison/reportComparison.php';">Reports Comparison</button>
        </form>
    </div>
</div>
<?php include "templates/footer.php"; ?>










