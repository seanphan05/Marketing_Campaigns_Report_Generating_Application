<?php
include('../config.php');
if(isset($_POST['year'] , $_POST['month'])) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $action = $_POST['action'];
    $con = new PDO($dsn, $username, $password, $options);
    if ($action == 'add') {
        $sql = "SELECT mc.`Promo Name` FROM (SELECT `Promo Name` FROM mc_ga WHERE `Years`=? AND `Months`=? AND `Promo Name` NOT LIKE '%Reminder%') mc 
                                    LEFT JOIN (SELECT `Promo Name` FROM campaign_details WHERE `Years`=? AND `Months`=?) cd  ON cd.`Promo Name` = mc.`Promo Name`
                                    WHERE cd.`Promo Name` IS NULL
                                    GROUP BY mc.`Promo Name`";
    } elseif ( $action == 'change') {
        $sql = "SELECT mc.`Promo Name` FROM (SELECT `Promo Name` FROM mc_ga WHERE `Years`=? AND `Months`=? AND `Promo Name` NOT LIKE '%Reminder%') mc 
                                    LEFT JOIN (SELECT `Promo Name`, `Campaign Details` FROM campaign_details WHERE `Years`=? AND `Months`=?) cd  ON cd.`Promo Name` = mc.`Promo Name`
                                    WHERE cd.`Campaign Details` IS NOT NULL
                                    GROUP BY mc.`Promo Name`";
    } else {
        echo 'There is no action found!';
    }
    $stmt = $con->prepare($sql);
    $stmt->execute([$year, $month, $year, $month]);
    ?><option selected="selected">--Select Promo Name--</option><?php
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
        ?> <option value="<?php echo $row['Promo Name']; ?>"><?php echo $row['Promo Name']; ?></option> <?php
    }
}
?>