<?php include "../../templates/header.php"; ?>
<h1 style="text-align: center;">Mailchimp & Google Analytics Integration - Report Generation App</h1>
<h2 style="text-align: center;">Subscriber List Breakdown Report</h2>
<div style="text-align: center;">
    <button onclick="window.location.href='../../index.php'">Home Page</button>
    <button type="button" id="refresh">Refresh Data</button><br><br>
    <div id="before" style="display:none;">
        <strong>Data is being refreshed. Please wait!</strong>
    </div>
    <div id="after" style="display:none;"></div>
    <div id="fail" style="display:none;">
        <strong>The request has been failed to execute. Please check again!</strong>
    </div>
</div>
<form>
    <select id="selectedMonth" class="month-select">
        <option class="month-option" value="January">January</option>
        <option class="month-option" value="February">February</option>
        <option class="month-option" value="March">March</option>
        <option class="month-option" value="April">April</option>
        <option class="month-option" value="May">May</option>
        <option class="month-option" value="June">June</option>
        <option class="month-option" value="July">July</option>
        <option class="month-option" value="August">August</option>
        <option class="month-option" value="September">September</option>
        <option class="month-option" value="October">October</option>
        <option class="month-option" value="November">November</option>
        <option class="month-option" value="December">December</option>
    </select>
    <select id="selectedYear" class="">
        <option class="year-option" value="2020">2020</option>
        <option class="year-option" value="2019">2019</option>
    </select>
    <input id="viewSubscriberList" type="submit" value="View Report"/>
</form>
<div id="result">
    <?php include "bySubscriberListAjax.php"?>
</div>
<?php include "../../templates/footer.php"; ?>
