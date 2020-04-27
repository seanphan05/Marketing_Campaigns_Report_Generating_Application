<?php
include('../config.php');
if($_POST['year'] || $_POST['compYear'])
{
    $year = $_POST['year'];
    $action = $_POST['action'];
    $con = new PDO($dsn, $username, $password, $options);
    if ($action == 'add') {
        $sql = "SELECT mc.`Months`
        FROM (SELECT `Months`, `Years`, `Promo Name` FROM mc_ga WHERE `Promo Name` NOT LIKE '%Reminder%' GROUP BY `Months`, `Years`, `Promo Name`) mc 
        LEFT JOIN campaign_details cd ON cd.`Years` = mc.`Years` AND cd.`Months` = mc.`Months` AND cd.`Promo Name` = mc.`Promo Name`
        WHERE cd.`Years` IS NULL
        AND cd.`Months` IS NULL
        AND cd.`Promo Name` IS NULL
        AND mc.`Years` = ?
        GROUP BY mc.`Months`
        ORDER BY FIELD(mc.`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');";
    } elseif ($action == 'change') {
        $sql = "SELECT mc.`Months`
        FROM (SELECT `Months`, `Years`, `Promo Name` FROM mc_ga WHERE `Promo Name` NOT LIKE '%Reminder%' GROUP BY `Months`, `Years`, `Promo Name`) mc 
        LEFT JOIN campaign_details cd ON cd.`Years` = mc.`Years` AND cd.`Months` = mc.`Months` AND cd.`Promo Name` = mc.`Promo Name`
        WHERE cd.`Campaign Details` IS NOT NULL
        AND mc.`Years` = ?
        GROUP BY mc.`Months`
        ORDER BY FIELD(mc.`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');";
    } elseif ($action = 'compare') {
        $year = $_POST["compYear"];
        $sql = "SELECT `Months` FROM mc_ga WHERE `Years` = ?
        GROUP BY `Months`
        ORDER BY FIELD(`Months`,'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');";
    } else {
        echo 'There is no action found!';
    }
    $stmt = $con->prepare($sql);
    $stmt->execute([$year]);
    ?><option selected="selected">--Select Month--</option><?php
    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        ?>
        <option value="<?php echo $row['Months']; ?>"><?php echo $row['Months']; ?></option>
        <?php
    }
}
?>