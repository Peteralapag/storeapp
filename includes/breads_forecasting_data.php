<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$branch = $functions->AppBranch();
$basedate = $_POST['basedate'] ?? date('Y-m-d');
$basedateending = date('Y-m-d', strtotime($basedate.' +7 days'));

$totalWeeks = 4;
$totaldays = $totalWeeks * 7;

$categories = [
    'ROSE CLASSIC HOT BREAD',
    'ROSE HIGH-END BREADS',
    'ROSE SPECIALTY BREADS',
    'ROSE PREMIUM BREADS'
];

$placeholders = implode(',', array_fill(0, count($categories), '?'));
$types = str_repeat('s', count($categories));

$stmt = $db->prepare("SELECT id, product_name, unit_price FROM store_items WHERE category_name IN ($placeholders)");
$stmt->bind_param($types, ...$categories);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>


<style>
.table-wrapper {
    max-height: 76vh;
    overflow-y: auto;
    position: relative;
}

#BreadsForecastingTable {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

#BreadsForecastingTable thead th {
    position: sticky;
    background: #fff;
    z-index: 5;
    box-shadow: inset 0 -1px 0 #ccc;
}

/* HEADER ROW 1 */
#BreadsForecastingTable thead tr:nth-child(1) th {
    top: 0;
    z-index: 10;
}

/* HEADER ROW 2 */
#BreadsForecastingTable thead tr:nth-child(2) th {
    top: 38px;
    z-index: 9;
}

/* HEADER ROW 3 */
#BreadsForecastingTable thead tr:nth-child(3) th {
    top: 76px;
    z-index: 8;
}
</style>


<div class="table-wrapper">
<table class="table table-bordered table-striped table-hover table-sm"
       id="BreadsForecastingTable">
<thead>

<tr>
    <th rowspan="3" style="background:#464545;color:#fff; vertical-align:middle;">#</th>
    <th rowspan="3" style="background:#464545;color:#fff; vertical-align:middle;">ITEM ID</th>
    <th rowspan="3" style="background:#464545;color:#fff; vertical-align:middle;">PRODUCT NAME</th>
    <th rowspan="3" style="background:#fad59b; color:#000; vertical-align:middle;">SRP</th>

    <?php for($i=0;$i<7;$i++): ?>
        <th style="background:#464545;color:#fff;">
            <?= strtoupper(date('D', strtotime("+$i day", strtotime($basedate)))) ?>
        </th>
    <?php endfor; ?>

    <th rowspan="3" style="background:#fad59b; color:#000; vertical-align:middle;">
        END WEEK<br>
        <?= date('M d', strtotime($basedate)) ?> -
        <?= date('M d', strtotime($basedate.' +6 days')) ?><br>
        <?= date('Y', strtotime($basedate)) ?>

    </th>

    <?php for($d=0;$d<$totaldays;$d++): ?>
        <th rowspan="3" style="background:#faf79b; color:#000; vertical-align:middle;">
            % INC<br>(FORECAST)
        </th>
        <th colspan="3" style="background:#464545;color:#fff;">
            <?= strtoupper(date('D', strtotime("+$d day", strtotime($basedate)))) ?>
        </th>
    <?php endfor; ?>
</tr>

<tr>
    <?php for($i=0;$i<7;$i++): ?>
        <th style="background:#464545;color:#fff;">
            <?= strtoupper(date('d-M', strtotime("+$i day", strtotime($basedate)))) ?>
        </th>
    <?php endfor; ?>

    <?php for($d=0;$d<$totaldays;$d++): ?>
        <th colspan="3" style="background:#464545;color:#fff;">
            <?= strtoupper(date('d-M', strtotime("+$d day", strtotime($basedate)))) ?>
        </th>
    <?php endfor; ?>
</tr>

<tr>
    <?php for($i=0;$i<7;$i++): ?>
        <th style="background:#464545;color:#fff;font-size:7px;">QTY SOLD</th>
    <?php endfor; ?>

    <?php for($d=0;$d<$totaldays;$d++): ?>
        <th style="background:#464545;color:#fff;font-size:7px;">FORECAST</th>
        <th style="background:#464545;color:#fff;font-size:7px;">KILOS</th>
        <th style="background:#464545;color:#fff;font-size:7px;">QTY SOLD</th>
    <?php endfor; ?>
</tr>

</thead>

<tbody>
<?php foreach ($products as $i => $prod): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td><?= $prod['id'] ?></td>
    <td><?= $prod['product_name'] ?></td>
    <td><?= number_format($prod['unit_price'],2) ?></td>

    <?php
    $week1Total = 0;
    for($d=0;$d<7;$d++):
        $date = date('Y-m-d', strtotime("+$d day", strtotime($basedate)));
        $stmt = $db->prepare("
            SELECT SUM(sold) qty
            FROM store_summary_data
            WHERE item_id=? AND report_date=? AND branch=?
        ");
        $stmt->bind_param("iss",$prod['id'],$date,$branch);
        $stmt->execute();
        $qty = (int)($stmt->get_result()->fetch_assoc()['qty'] ?? 0);
        $stmt->close();
        $week1Total += $qty;
    ?>
        <td style="text-align:center"><?= $qty ?></td>
    <?php endfor; ?>

    <td style="font-weight:bold;text-align:center"><?= $week1Total ?></td>

    <?php $prev = $week1Total; ?>


    <?php
    for ($w = 0; $w < $totaldays; $w++):


        $date = date('Y-m-d', strtotime("+$w day", strtotime($basedate)));

        // Calculate start and end date of this week
        $weekStartDate = date('Y-m-d', strtotime($basedate . " +".($w*7)." days"));
        $weekEndDate   = date('Y-m-d', strtotime($weekStartDate . " +6 days"));

        // Get total QTY SOLD for this week in one query
        $stmt = $db->prepare("
            SELECT SUM(sold) qty
            FROM store_summary_data
            WHERE item_id=? AND report_date=? AND branch=?
        ");
        $stmt->bind_param("iss",$prod['id'],$date,$branch);
        $stmt->execute();
        
        $qty = (int)($stmt->get_result()->fetch_assoc()['qty'] ?? 0);
        $stmt->close();
    ?>
        <td style="background:#faf79b;text-align:center"  contenteditable="true" maxlength="3"></td>
        <td style="text-align:center"></td>
        <td style="text-align:center"></td>
        <td style="text-align:center"><?= $qty ?></td>
    <?php

    endfor;
    ?>

</tr>
<?php endforeach; ?>
</tbody>

</table>
</div>
