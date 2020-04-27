<?php
include('../config.php');
if(isset($_POST['year'] , $_POST['month'], $_POST['promo'])) {
    try {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $promo = $_POST['promo'];
    $con = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT `Months`, `Promo Name`, `Campaign Details` 
            FROM campaign_details 
            WHERE `Years`= ? 
            AND `Months`= ? 
            AND `Promo Name` = ? ";
    $stmt = $con->prepare($sql);
    $stmt->execute([$year, $month, $promo]);

    $result = $stmt->fetchAll();
    } catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
    }
}

if (isset($_POST['year'] , $_POST['month'], $_POST['promo'])) {
    if ($result && $stmt->rowCount() > 0) { ?>
        <table id="current_camp_details_table">
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
                    <td style="text-align: left"><?php echo $row["Months"]; ?></td>
                    <td style="text-align: left"><?php echo $row["Promo Name"]; ?></td>
                    <td style="text-align: left"><?php echo $row["Campaign Details"]; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No Result found!</p>
    <?php } ?>
<?php } ?>