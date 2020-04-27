<?php include "../../templates/header.php";
require "../config.php";
$con = new PDO($dsn, $username, $password, $options);
$stmt = $con->prepare("SELECT `Months`, `Years` FROM mc_ga GROUP BY `Years`");
$stmt->execute();
$result = $stmt->fetchAll(); ?>
    <div style="text-align: center;">
        <h1>Mailchimp & Google Analytics Integration - Report Generation App</h1>
        <button onclick="window.location.href='../../index.php'">Home Page</button>
        <button type="button" id="refresh">Refresh Data</button><br><br>
        <div id="before" style="display:none;">
            <strong>Data is being refreshed. Please wait!</strong>
        </div>
        <div id="after" style="display:none;"></div>
        <div id="fail" style="display:none;">
            <strong>The request has been failed to execute. Please check again!</strong>
        </div>
        <h3>Monthly and Yearly Report Comparison</h3>
    </div>
    <div style="text-align: center;" class="container">                             <!--Start Container-->
        <div class="year_comp_form">                                                <!--Start Year 1-->
            <label><strong>Year :</strong></label>
            <select id="getCompYear1">
                <option selected="selected">--Select Year--</option>
                <?php
                if ($result && $stmt->rowCount() > 0) {
                    foreach ($result as $row) {
                        echo '<option name="' . ['Years'] . '">' . $row['Years'] . '</option>';
                    }
                } ?>
            </select><br><br>
            <div id="select_month1" style="display:none;">
                <label><strong>Month :</strong></label>
                <select id="getCompMonth1"></select><br><br>
            </div>
            <div id="comp_report1_result">
                <?php include "../editReport/getMonthAjax.php" ?>
            </div>
        </div>                                                                      <!--End Year 1-->
        <div style="text-align: center;" class="year_comp_form">                    <!--Start Year 2-->
            <label><strong>Year :</strong></label>
            <select id="getCompYear2">
                <option selected="selected">--Select Year--</option>
                <?php
                if ($result && $stmt->rowCount() > 0) {
                    foreach ($result as $row) {
                        echo '<option name="' . ['Years'] . '">' . $row['Years'] . '</option>';
                    }
                } ?>
            </select><br><br>
            <div id="select_month2" style="display:none;">
                <label><strong>Month :</strong></label>
                <select id="getCompMonth2"></select><br><br>
            </div>
            <div id="comp_report2_result">
                <?php include "../editReport/getMonthAjax.php" ?>
            </div>
        </div>                                                                      <!--End Year 2-->
    </div>                                                                          <!--End Container-->
<?php include "../../templates/footer.php"; ?>