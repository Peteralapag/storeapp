<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$branch = $functions->AppBranch();
$user = $_SESSION['appstore_appnameuser'] ?? 'unknown_user';
$basedate = $_POST['basedate'] ?? date('Y-m-d');

$totalWeeks = 4;
$totaldays = $totalWeeks * 7;

$basedateending = date('Y-m-d', strtotime($basedate.' +7 days'));
$basedateendingto = date('Y-m-d', strtotime($basedateending.' +'.($totaldays-1).' days'));

$categories = [
    'ROSE CLASSIC HOT BREAD',
    'ROSE BINALOT & PASALUBONG',
    'ROSE NUTRI-DENSE',
    'ROSE TASTY LOAF',
    'ROSE CLASSIC SPECIAL',
    'ALL TIME CAKE (CLASSIC)',
    'CELEBRATION CAKES (FLAGSLIP)',
    'PREMIUM CAKES (HIGH-END)'
];

$placeholders = implode(',', array_fill(0, count($categories), '?'));
$types = str_repeat('s', count($categories));

$stmt = $db->prepare("SELECT id, product_name, unit_price, yield_per_kilo FROM store_items WHERE category_name IN ($placeholders)");
$stmt->bind_param($types, ...$categories);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}



$forecastData = [];
$startDate = $basedate;
$endDate = date('Y-m-d', strtotime($basedate . " +".($totaldays-1)." days"));

$stmt = $db->prepare("
    SELECT item_id, forecast_date, forecast_percent
    FROM store_forecasting
    WHERE branch=? AND forecast_date BETWEEN ? AND ?
");
$stmt->bind_param("sss", $branch, $basedateending, $endDate);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()){
    // key by item_id and forecast_date for easy lookup
    $forecastData[$row['item_id']][$row['forecast_date']] = $row['forecast_percent'];
}
$stmt->close();


?>

<style>

.table-wrapper {
    max-height: 76vh;
    overflow: auto; /* allow horizontal & vertical scroll */
    position: relative;
}

#BreadsForecastingTable {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

/* ===== Sticky Header Rows (vertical) ===== */
#BreadsForecastingTable thead th {
    position: sticky;
    z-index: 5;
    box-shadow: inset 0 -1px 0 #ccc;
}

#BreadsForecastingTable thead tr:nth-child(1) th { top: 0; z-index: 10; }
#BreadsForecastingTable thead tr:nth-child(2) th { top: 35px; z-index: 9; }
#BreadsForecastingTable thead tr:nth-child(3) th { top: 71px; z-index: 8; }

/* ===== Sticky First 3 Columns (horizontal + vertical) ===== */

#BreadsForecastingTable td:nth-child(1) {
    position: sticky;
    left: 0;
    background: #464545;
    color: #fff;
    z-index: 20; /* above normal cells */
}


#BreadsForecastingTable td:nth-child(2) {
    position: sticky;
    left: 35px; /* width of first column, adjust if needed */
    background: #464545;
    color: #fff;
    z-index: 19;
}


#BreadsForecastingTable td:nth-child(3) {
    position: sticky;
    left: 97px; /* cumulative width of first + second column */
    background: #464545;
    color: #fff;
    z-index: 18;
}

/* Make SRP column sticky horizontally too (optional) */







</style>

<div class="table-wrapper">
<table class="table table-bordered table-striped table-hover table-sm" id="BreadsForecastingTable">
<thead>
<tr>
    <th rowspan="3" style="background:#464545;color:#fff; vertical-align:middle;">#</th>
    <th rowspan="3" style="background:#464545;color:#fff; vertical-align:middle;">ITEM ID</th>
    <th rowspan="3" style="background:#464545;color:#fff; vertical-align:middle;">PRODUCT NAME</th>
    <th rowspan="3" style="background:#fad59b; color:#000; vertical-align:middle;">SRP</th>

    <?php for($i=0;$i<7;$i++): ?>
        <th style="background:#464545;color:#fff;"><?= strtoupper(date('D', strtotime("+$i day", strtotime($basedate)))) ?></th>
    <?php endfor; ?>

    <th rowspan="3" style="background:#fad59b; color:#000; vertical-align:middle;">
        END WEEK<br>
        <?= date('M d', strtotime($basedate)) ?> -
        <?= date('M d', strtotime($basedate.' +6 days')) ?><br>
        <?= date('Y', strtotime($basedate)) ?>
    </th>

    <?php for($d=0;$d<$totaldays;$d++): ?>
        <th rowspan="3" style="background:#faf79b; color:#000; vertical-align:middle;">% INC<br>(FORECAST)</th>
        <th colspan="3" style="background:#464545;color:#fff;"><?= strtoupper(date('D', strtotime("+$d day", strtotime($basedate)))) ?></th>
    <?php endfor; ?>
</tr>

<tr>
    <?php for($i=0;$i<7;$i++): ?>
        <th style="background:#464545;color:#fff;"><?= strtoupper(date('d-M', strtotime("+$i day", strtotime($basedate)))) ?></th>
    <?php endfor; ?>

    <?php for($d=0;$d<$totaldays;$d++): ?>
        <th colspan="3" style="background:#464545;color:#fff;"><?= strtoupper(date('d-M', strtotime("+$d day", strtotime($basedateending)))) ?></th>
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
<tr data-itemid="<?= $prod['id'] ?>">
    <td><?= $i+1 ?></td>
    <td><?= $prod['id'] ?></td>
    <td><?= $prod['product_name'] ?></td>
    <td><?= number_format($prod['unit_price'],2) ?></td>

    <?php
    // ===== Base Week QTY SOLD per day =====
    $week1Base = [];
    for($d=0;$d<7;$d++):
        $date = date('Y-m-d', strtotime("+$d day", strtotime($basedate)));
        $dayName = date('D', strtotime($date));

        $stmt = $db->prepare("
            SELECT SUM(sold) qty
            FROM store_summary_data
            WHERE item_id=? AND report_date=? AND branch=?
        ");
        $stmt->bind_param("iss",$prod['id'],$date,$branch);
        $stmt->execute();
        $qty = (int)($stmt->get_result()->fetch_assoc()['qty'] ?? 0);
        $stmt->close();

        $week1Base[$dayName] = $qty;
    ?>
        <td style="text-align:center"><?= $qty ?></td>
    <?php endfor; ?>

    <td style="font-weight:bold;text-align:center"><?= array_sum($week1Base) ?></td>

    <?php
    // ===== Forecast cells =====
    for ($w = 0; $w < $totaldays; $w++):
        $date = date('Y-m-d', strtotime("+$w day", strtotime($basedateending)));
        $forecastDay = date('D', strtotime($date));
        $reportdate = $date;

        $stmt = $db->prepare("
            SELECT SUM(sold) qty
            FROM store_summary_data
            WHERE item_id=? AND report_date=? AND branch=?
        ");
        $stmt->bind_param("iss", $prod['id'], $date, $branch);
        $stmt->execute();
        $forecastQty = (int)($stmt->get_result()->fetch_assoc()['qty'] ?? 0);
        $stmt->close();

    ?>
        <td class="forecast-inc"
            data-day="<?= $forecastDay ?>"
            data-reportdate="<?= $reportdate ?>"
            style="background:#faf79b;text-align:center"
            contenteditable="true">
            <?= isset($forecastData[$prod['id']][$reportdate]) 
                ? $forecastData[$prod['id']][$reportdate] 
                : '' ?>
        </td>

        <td class="forecast-result" style="text-align:center">
            <?php 
            $savedPercent = $forecastData[$prod['id']][$reportdate] ?? 0;
            $forecastVal = $week1Base[$forecastDay] + ($week1Base[$forecastDay] * ($savedPercent/100));
            echo $savedPercent ? round($forecastVal) : '';
            ?>
        </td>

        <td style="text-align:center" data-yield="<?= $prod['yield_per_kilo'] ?>">
            <?php
            if ($prod['yield_per_kilo'] > 0 && $savedPercent) {
                echo round($forecastVal / $prod['yield_per_kilo'], 2);
            } else {
                echo '';
            }
            ?>
        </td>


        <td style="text-align:center"><?= $forecastQty ?></td>
        
        <td class="base-day"
            data-day="<?= $forecastDay ?>"
            data-qty="<?= $week1Base[$forecastDay] ?>"
            style="text-align:center; display:none;">
            <?= $week1Base[$forecastDay] ?>
        </td>
    <?php endfor; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<script>
let typingTimer;

document.addEventListener('input', function(e) {
    if(!e.target.classList.contains('forecast-inc')) return;

    let inc = parseFloat(e.target.innerText) || 0;
    let day = e.target.dataset.day;
    let date = e.target.dataset.reportdate;
    let row = e.target.closest('tr');
    let item_id = row.dataset.itemid;
    let branch = '<?= $branch ?>';

    // compute base
    let base = 0;
    row.querySelectorAll('.base-day').forEach(cell => {
        if(cell.dataset.day === day){
            base = parseFloat(cell.dataset.qty) || 0;
        }
    });

    // ✅ declare forecast only once
    let forecast = base + (base * (inc / 100));
    e.target.nextElementSibling.innerText = Math.round(forecast);

    // update kilos dynamically
    let kilosCell = e.target.nextElementSibling.nextElementSibling;
    let yieldPerKilo = parseFloat(kilosCell.dataset.yield) || 0;
    if (yieldPerKilo > 0) {
        kilosCell.innerText = (forecast / yieldPerKilo).toFixed(2);
    }

    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        let params = 
            `branch=${encodeURIComponent(branch)}` +
            `&item_id=${encodeURIComponent(item_id)}` +
            `&forecast_date=${encodeURIComponent(date)}` +
            `&forecast_percent=${encodeURIComponent(inc)}`;

        fetch('./actions/save_forecast.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: params
        })
        .then(res => res.text())
        .then(txt => {
            console.log('RAW RESPONSE:', txt);
            try {
                let data = JSON.parse(txt);
                console.log('JSON OK', data);
            } catch(e){
                console.error('NOT JSON', txt);
            }
        });
    }, 600);
});
