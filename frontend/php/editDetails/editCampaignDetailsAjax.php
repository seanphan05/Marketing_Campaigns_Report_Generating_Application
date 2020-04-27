<?php
include "../../templates/header.php";
$year = "2019";
if (isset($_POST['action']) || isset($year)) {
    try {
        require "../config.php";
        require "../common.php";
        $connection = new PDO($dsn, $username, $password, $options);

        if (isset($_POST['action'])) {
            $month = $_POST['editMonth'];
            $year = $_POST['editYear'];
            $promoName = $_POST['editPromoName'];
            $campDetails = $_POST['editCampaignDetails'];
            $action = $_POST['action'];

            if ($action === 'add') {
                $sql = "INSERT INTO campaign_details(`Months`,`Years`,`Promo Name`,`Campaign Details`)
                    SELECT * FROM (SELECT ? AS m, ? AS y, ? AS p, ? AS c) AS temp
                    WHERE NOT EXISTS (
                    SELECT `Months`,`Years`,`Promo Name` FROM campaign_details WHERE `Months` = ? AND `Years` = ? AND `Promo Name` = ?)";
                $statement = $connection->prepare($sql);
                $statement->execute([$month, $year, $promoName, $campDetails, $month, $year, $promoName]);
            }
            if ($action === 'change') {
                $sql = "UPDATE campaign_details
                    SET `Campaign Details` = ?
                    WHERE `Months` = ? AND `Years` = ? AND `Promo Name` = ?";
                $statement = $connection->prepare($sql);
                $statement->execute([$campDetails, $month, $year, $promoName]);
            }
            if ($action === 'delete') {
                $sql = "DELETE FROM campaign_details
                    WHERE `Months` = ? AND `Years` = ? AND `Promo Name` = ?";
                $statement = $connection->prepare($sql);
                $statement->execute([$month, $year, $promoName]);
            }
            $sql1 = "SELECT * FROM campaign_details
                     ORDER BY `Years`, FIELD(`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'), `Promo Name`;";
            $statement = $connection->query($sql1);

        }
        else {
            $sql = "SELECT * FROM campaign_details
                    ORDER BY `Years`, FIELD(`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'), `Promo Name`;";
            $statement = $connection->prepare($sql);
            $statement->execute();
        }

        $result = $statement->fetchAll();

    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php
if (isset($_POST['addCampaignDetails']) || isset($year)) {
    if ($result && $statement->rowCount() > 0) { ?>
        <h3 style="text-align: center;">List All Of Current Campaign Details</h3>
        <table id="camp_details_table">
            <thead class="table_head">
            <tr>
                <th style="text-align: left">Months</th>
                <th style="text-align: left">Promo Name</th>
                <th style="text-align: left">Campaign Details</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td style="text-align: left"><?php echo escape($row["Months"]); ?></td>
                    <td style="text-align: left"><?php echo escape($row["Promo Name"]); ?></td>
                    <td style="text-align: left"><?php echo escape($row["Campaign Details"]); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No Result found!</p>
    <?php } ?>
<?php } ?>


