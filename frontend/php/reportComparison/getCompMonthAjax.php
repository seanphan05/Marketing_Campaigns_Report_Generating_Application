<?php
include "../../templates/header.php";
if (isset($_POST['compYear'])) {
    try {
        require "../config.php";
        require "../common.php";
        if (isset($_POST['compMonth'])) {
            $month = $_POST['compMonth'];
        }
        $year = $_POST['compYear'];

        $connection = new PDO($dsn, $username, $password, $options);
        if (isset($_POST['compMonth'])) {
            $sql = "SELECT `Months`, `Years`, SUM(`Emails Sent`) AS `Emails Sent`, SUM(`Unique Opens`)*100/(SUM(`Emails Sent`)-SUM(`Bounces`)) AS `Unique Open Rate`,
                SUM(`Unique Clicks`)*100/(SUM(`Emails Sent`) - SUM(`Bounces`)) AS `Unique Click Rate`, (SUM(`Emails Sent`)-SUM(`Bounces`))*100/SUM(`Emails Sent`) AS `Deliverability Rate`,
                SUM(`Unsub`)*100/SUM(`Emails Sent`) AS `Unsub Rate`, SUM(`Spam`)*100/SUM(`Emails Sent`) AS `Spam Rate`, SUM(`Transactions`)*100/SUM(`Sessions`) AS `ECR`, SUM(`Transactions`) AS `Transactions`,
                SUM(`Revenue ($)`) AS `Revenue`, SUM(`Revenue ($)`)/SUM(`Emails Sent`) AS `RPE`
                FROM mc_ga
                WHERE `Years` = ?
                AND `Months` = ?";
            $statement = $connection->prepare($sql);
            $statement->execute([$year, $month]);
        } else {
            $sql = "SELECT `Years`, SUM(`Emails Sent`) AS `Emails Sent`, SUM(`Unique Opens`)*100/(SUM(`Emails Sent`)-SUM(`Bounces`)) AS `Unique Open Rate`,
                SUM(`Unique Clicks`)*100/(SUM(`Emails Sent`) - SUM(`Bounces`)) AS `Unique Click Rate`, (SUM(`Emails Sent`)-SUM(`Bounces`))*100/SUM(`Emails Sent`) AS `Deliverability Rate`,
                SUM(`Unsub`)*100/SUM(`Emails Sent`) AS `Unsub Rate`, SUM(`Spam`)*100/SUM(`Emails Sent`) AS `Spam Rate`, SUM(`Transactions`)*100/SUM(`Sessions`) AS `ECR`, SUM(`Transactions`) AS `Transactions`,
                SUM(`Revenue ($)`) AS `Revenue`, SUM(`Revenue ($)`)/SUM(`Emails Sent`) AS `RPE`
                FROM mc_ga
                WHERE `Years` = ?
                GROUP BY `Years`";
            $statement = $connection->prepare($sql);
            $statement->execute([$year]);
        }
        $result = $statement->fetchAll();

    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php
if (isset($_POST['action'])) {
    if ($result && $statement->rowCount() > 0) {
        if (isset($_POST['compMonth'])) { ?>
            <h3 style="text-align: center;">Report <?php echo $month ?> <?php echo $year ?></h3>
        <?php } else { ?>
            <h3 style="text-align: center;">Yearly <?php echo $year ?></h3>
        <?php } ?>

        <div style="text-align: center;">
            <table id="comp_month_table">
                <?php foreach ($result as $row) { ?>
                <tr>
                    <th style="text-align: left">Emails Sent</th>
                    <td><?php echo escape(number_format($row["Emails Sent"],0, '.',',')); ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Unique Open Rate</th>
                    <td class="long_name"><?php echo escape(number_format($row["Unique Open Rate"],2,'.',','))."%"; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Unique Click Rate</th>
                    <td class="long_name"><?php echo escape(number_format($row["Unique Click Rate"], 2, '.', ','))."%"; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Deliverability Rate</th>
                    <td class="long_name"><?php echo escape(number_format($row["Deliverability Rate"], 2, '.', ','))."%"; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Unsub Rate</th>
                    <td><?php echo escape(number_format($row["Unsub Rate"], 2, '.', ','))."%"; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Spam Rate</th>
                    <td><?php echo escape(number_format($row["Spam Rate"], 2, '.', ','))."%"; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">ECR</th>
                    <td><?php echo escape(number_format($row["ECR"], 2, '.', ','))."%"; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Transactions</th>
                    <td><?php echo escape(number_format($row["Transactions"],0, '.',',')); ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Revenue</th>
                    <td><?php echo "$".escape(number_format($row["Revenue"], 2, '.', ',')); ?></td>
                <tr>
                    <th style="text-align: left">RPE</th>
                    <td><?php echo "$".escape(number_format($row["RPE"], 2, '.', ',')); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    <?php } else { ?>
        <p>No Result found!</p>
    <?php } ?>
<?php }
include "../../templates/footer.php"; ?>


