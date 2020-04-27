<?php
include "../../templates/header.php";
$year = "2020";
if (isset($_POST['selectedYear']) || isset($year)) {
    try {
        require "../config.php";
        require "../common.php";
        if (isset($_POST['selectedYear'])) {
            $year = $_POST['selectedYear'];
        }
        $connection = new PDO($dsn, $username, $password, $options);

        $sql = "SELECT mc.`Months`, mc.`Years`, mc.`Promo Name`, COALESCE(cd.`Campaign Details`,'') AS `Campaign Details`, SUM(mc.`Emails Sent`) AS `Emails Sent`, SUM(mc.`Unique Opens`)*100/(SUM(mc.`Emails Sent`)-SUM(mc.`Bounces`)) AS `Unique Open Rate`,
                SUM(mc.`Unique Clicks`)*100/(SUM(mc.`Emails Sent`) - SUM(mc.`Bounces`)) AS `Unique Click Rate`, (SUM(mc.`Emails Sent`)-SUM(mc.`Bounces`))*100/SUM(mc.`Emails Sent`) AS `Deliverability Rate`, 
                SUM(mc.`Unsub`)*100/SUM(mc.`Emails Sent`) AS `Unsub Rate`, SUM(mc.`Spam`)*100/SUM(mc.`Emails Sent`) AS `Spam Rate`, SUM(mc.`Transactions`)*100/SUM(mc.`Sessions`) AS `ECR`, SUM(mc.`Transactions`) AS `Transactions`,
                SUM(mc.`Revenue ($)`) AS `Revenue`, SUM(mc.`Revenue ($)`)/SUM(mc.`Emails Sent`) AS `RPE`
                FROM mc_ga mc
                LEFT JOIN campaign_details cd 
                ON mc.`Months` = cd.`Months` AND mc.`Years` = cd.`Years` AND mc.`Promo Name` = cd.`Promo Name`
                WHERE mc.`Years` = ?
                GROUP BY mc.`Months`, mc.`Promo Name`
                ORDER BY FIELD(mc.`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'), mc.`Promo Name`;";

        $statement = $connection->prepare($sql);
        $statement->execute([$year]);

        $result = $statement->fetchAll();

    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php
if (isset($_POST['selectedYear']) || isset($year)) {
    include "../../templates/header.php";
    if ($result && $statement->rowCount() > 0) { ?>
        <h2 style="text-align: center; text-align: center">Campaign Breakdown Report <?php echo $year?></h2>
        <table id="campaign_table">
            <thead class="table_head">
            <tr>
                <th style="text-align: left">Months</th>
                <th style="text-align: left">Promo Name</th>
                <th style="text-align: left">Campaign Details</th>
                <th>Emails Sent</th>
                <th>Unique Open Rate</th>
                <th>Unique Click Rate</th>
                <th>Deliverability Rate</th>
                <th>Unsub Rate</th>
                <th>Spam Rate</th>
                <th>ECR</th>
                <th>Transactions</th>
                <th>Revenue</th>
                <th>RPE</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td style="text-align: left"><?php echo escape($row["Months"]); ?></td>
                    <td class="long_name" style="text-align: left"><?php echo escape($row["Promo Name"]); ?></td>
                    <td class="very_long_name" style="text-align: left"><?php echo escape($row["Campaign Details"]); ?></td>
                    <td><?php echo escape(number_format($row["Emails Sent"],0, '.',',')); ?></td>
                    <td class="long_name"><?php echo escape(number_format($row["Unique Open Rate"],2,'.',','))."%"; ?></td>
                    <td class="long_name"><?php echo escape(number_format($row["Unique Click Rate"], 2, '.', ','))."%"; ?></td>
                    <td class="long_name"><?php echo escape(number_format($row["Deliverability Rate"], 2, '.', ','))."%"; ?></td>
                    <td><?php echo escape(number_format($row["Unsub Rate"], 2, '.', ','))."%"; ?></td>
                    <td><?php echo escape(number_format($row["Spam Rate"], 2, '.', ','))."%"; ?></td>
                    <td><?php echo escape(number_format($row["ECR"], 2, '.', ','))."%"; ?></td>
                    <td><?php echo escape(number_format($row["Transactions"],0, '.',',')); ?></td>
                    <td><?php echo "$".escape(number_format($row["Revenue"], 2, '.', ',')); ?></td>
                    <td><?php echo "$".escape(number_format($row["RPE"], 2, '.', ',')); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No Result found!</p>
    <?php } ?>
<?php } ?>
<?php include "../../templates/footer.php"; ?>
