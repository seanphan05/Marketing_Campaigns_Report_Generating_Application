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
        $sql = "SELECT `Months`, `Years`, SUM(`Emails Sent`) AS `Emails Sent`, SUM(`Unique Opens`)*100/(SUM(`Emails Sent`)-SUM(`Bounces`)) AS `Unique Open Rate`,
            SUM(`Unique Clicks`)*100/(SUM(`Emails Sent`) - SUM(`Bounces`)) AS `Unique Click Rate`, (SUM(`Emails Sent`)-SUM(`Bounces`))*100/SUM(`Emails Sent`) AS `Deliverability Rate`, 
            SUM(`Unsub`)*100/SUM(`Emails Sent`) AS `Unsub Rate`, SUM(`Spam`)*100/SUM(`Emails Sent`) AS `Spam Rate`, SUM(`Transactions`)*100/SUM(`Sessions`) AS `ECR`, SUM(`Transactions`) AS `Transactions`,
            SUM(`Revenue ($)`) AS `Revenue`, SUM(`Revenue ($)`)/SUM(`Emails Sent`) AS `RPE`
            FROM mc_ga
            WHERE `Years` = ?
            GROUP BY `Months`
            ORDER BY FIELD(`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');";

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
    if ($result && $statement->rowCount() > 0) { ?>
        <h2 style="text-align: center; text-align: center">Monthly Summary Report <?php echo $year ?></h2>
        <div>
            <table>
                <thead class="table_head">
                <tr>
                    <th style="text-align: left">Months</th>
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
        </div>
    <?php } else { ?>
        <p>No Result found!</p>
    <?php } ?>
<?php }
include "../../templates/footer.php"; ?>
