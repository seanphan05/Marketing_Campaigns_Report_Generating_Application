<?php
$month = "Jan";
$year = "2020";
include "../../templates/header.php";
if (isset($_POST['selectedMonth'], $_POST['selectedYear']) || isset($month, $year)) {
    if (isset($_POST['selectedMonth'], $_POST['selectedYear'])) {
        $month = substr($_POST['selectedMonth'],0,3);
        $year = $_POST['selectedYear'];
    }
    try {
        require "../config.php";
        require "../common.php";
        $connection = new PDO($dsn, $username, $password, $options);

        // need to be modified later
        if ($year == '2020') {
            $sql = "SELECT `Months`,`Years`, SUBSTRING(`Promo Name`, 1, 12) AS `Promo`, `Segment Name`, `Launch Date`, SUM(`Emails Sent`) AS `Emails Sent`, SUM(`Unique Opens`)*100/(SUM(`Emails Sent`)- SUM(`Bounces`)) AS `Unique Open Rate`,
        SUM(`Unique Clicks`)*100/(SUM(`Emails Sent`) - SUM(`Bounces`)) AS `Unique Click Rate`, (SUM(`Emails Sent`)-SUM(`Bounces`))*100/SUM(`Emails Sent`) AS `Deliverability Rate`, 
        SUM(`Unsub`) AS `Unsubs`, SUM(`Unsub`)*100/SUM(`Emails Sent`) AS `Unsub Rate`, SUM(`Spam`) AS `Spams`, SUM(`Spam`)*100/SUM(`Emails Sent`) AS `Spam Rate`, SUM(`Transactions`)*100/SUM(`Sessions`) AS `ECR`, SUM(`Transactions`) AS `Transactions`,
        SUM(`Revenue ($)`) AS `Revenue`, SUM(`Revenue ($)`)/SUM(`Emails Sent`) AS `RPE`
        FROM mc_ga
        WHERE `Months`= ?
        AND `Years` = ?
        GROUP BY `Months`, `Promo`, `Segment Name`
        ORDER BY `Revenue` DESC";
        } else {
            $sql = "SELECT `Months`,`Years`, SUBSTRING(`Promo Name`, 1, 12) AS `Promo`, `Segments`, `Launch Date`, SUM(`Emails Sent`) AS `Emails Sent`, SUM(`Unique Opens`)*100/(SUM(`Emails Sent`)- SUM(`Bounces`)) AS `Unique Open Rate`,
        SUM(`Unique Clicks`)*100/(SUM(`Emails Sent`) - SUM(`Bounces`)) AS `Unique Click Rate`, (SUM(`Emails Sent`)-SUM(`Bounces`))*100/SUM(`Emails Sent`) AS `Deliverability Rate`, 
        SUM(`Unsub`) AS `Unsubs`, SUM(`Unsub`)*100/SUM(`Emails Sent`) AS `Unsub Rate`, SUM(`Spam`) AS `Spams`, SUM(`Spam`)*100/SUM(`Emails Sent`) AS `Spam Rate`, SUM(`Transactions`)*100/SUM(`Sessions`) AS `ECR`, SUM(`Transactions`) AS `Transactions`,
        SUM(`Revenue ($)`) AS `Revenue`, SUM(`Revenue ($)`)/SUM(`Emails Sent`) AS `RPE`
        FROM mc_ga
        WHERE `Months`= ?
        AND `Years` = ?
        GROUP BY `Months`, `Promo`, `Segments`
        ORDER BY `Revenue` DESC";
        }


        $statement = $connection->prepare($sql);
        $statement->execute([$month, $year]);

        $result = $statement->fetchAll();
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php
if (isset($_POST['selectedMonth']) || isset($month, $year)) {
    if ($result && $statement->rowCount() > 0) {
        $new_result = array();
        foreach ($result as $key => $item) {
            $new_result[$item['Promo']][$key] = $item;
        }
        ksort($new_result); ?>
        <h3><?php echo $month.' '. $year?> Summary Report:</h3>
        <?php foreach ($new_result as $promo) { ?>
            <h4><?php echo array_search($promo, $new_result) ?> Report</h4>
        <table>
            <thead class="table_head">
            <tr>
                <th style="text-align: left">Segment Name</th>
                <th>Emails Sent</th>
                <th>Unique Open Rate</th>
                <th>Unique Click Rate</th>
<!--                <th>Deliverability Rate</th>-->
                <th>Unsubscribed</th>
                <th>Spam Report</th>
                <th>ECR</th>
                <th>Transactions</th>
                <th>Revenue</th>
                <th>RPE</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($promo as $row) { ?>
                <tr>
<!--                    need to be modified later-->
                    <?php if ($year == "2020") { ?>
                        <td style="text-align: left"><?php echo escape($row["Segment Name"]); ?></td>
                    <?php }  else { ?>
                    <td style="text-align: left"><?php echo escape($row["Segments"]); ?></td>
                    <?php } ?>

                    <td><?php echo escape(number_format($row["Emails Sent"],0, '.',',')); ?></td>
                    <td class="long_name"><?php echo escape(number_format($row["Unique Open Rate"],2,'.',','))."%"; ?></td>
                    <td class="long_name"><?php echo escape(number_format($row["Unique Click Rate"], 2, '.', ','))."%"; ?></td>
<!--                    <td class="long_name">--><?php //echo escape(number_format($row["Deliverability Rate"], 2, '.', ','))."%"; ?><!--</td>-->
                    <td><?php echo escape(number_format($row["Unsubs"], 0, '.', ',')); ?></td>
                    <td><?php echo escape(number_format($row["Spams"], 0, '.', ',')); ?></td>
                    <td><?php echo escape(number_format($row["ECR"], 2, '.', ','))."%"; ?></td>
                    <td><?php echo escape(number_format($row["Transactions"],0, '.',',')); ?></td>
                    <td><?php echo "$".escape(number_format($row["Revenue"], 2, '.', ',')); ?></td>
                    <td><?php echo "$".escape(number_format($row["RPE"], 2, '.', ',')); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    <?php } else { ?>
        <p>No Result found!</p>
    <?php } ?>
<?php }
include "../../templates/footer.php";
?>
