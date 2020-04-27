<h1 style="text-align: center;">Mailchimp & Google Analytics Integration - Report Generation App</h1>
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
    <select id="selectedYear">
        <option class="year-option" value="2020">2020</option>
        <option class="year-option" value="2019">2019</option>
    </select>
    <input id="url" type="hidden" value="byMonthAjax.php"/>
    <input id="viewMonthCampaign" type="submit" value="View Report"/>
</form>
<div id="result">
    <?php include "byMonthAjax.php"?>
</div>
