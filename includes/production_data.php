<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);    
$functions = new TheFunctions;
$branch = $functions->AppBranch();

if (empty($branch)) {   
    echo "<div class='alert alert-danger text-center fw-bold' role='alert'>No branch selected. Please select a branch first.</div>";
    exit;
}

$datefrom = $_POST['datefrom'] ?? null;
$dateto   = $_POST['dateto'] ?? null;

if (empty($datefrom) || empty($dateto)) {
    echo "<div class='alert alert-danger text-center fw-bold' role='alert'>Please select Date From and Date To.</div>";  
    exit;
}

$dateHeader = date("F d, Y", strtotime($datefrom)) . " to " . date("F d, Y", strtotime($dateto));
$start = new DateTime($datefrom);
$end   = new DateTime($dateto);
$end->modify('+1 day'); // for looping in table

// ------------------
// Check special produces
// ------------------
$produceWheat = false;
$produceBurger = false;

// Wheat Loaf check
$q = $db->prepare("SELECT is_produce_wheat_loaf FROM store_branchlist_wheatloaf WHERE branch = ?");
$q->bind_param("s", $branch);
$q->execute();
$result = $q->get_result()->fetch_assoc();
if (($result['is_produce_wheat_loaf'] ?? 'NO') === 'YES') {
    $produceWheat = true;
}
$q->close();

// Burger Buns check
$q = $db->prepare("SELECT is_produce_burger_buns FROM store_branchlist_burgerbuns WHERE branch = ?");
$q->bind_param("s", $branch);
$q->execute();
$result = $q->get_result()->fetch_assoc();
if (($result['is_produce_burger_buns'] ?? 'NO') === 'YES') {
    $produceBurger = true;
}
$q->close();

// ------------------
// Prepare query
// ------------------
$excluded = [
    'BEVERAGES','BOTTLED WATER','COFFEE','ROSE HIGH-END BREADS','ROSE BINALOT & PASALUBONG',
    'ROSE NUTRI-DENSE','ALL TIME CAKE (CLASSIC)','PREMIUM CAKES (HIGH-END)','HIGH-END COFFEE',
    'FGFRAP','MILK TEA','ROSE CLASSIC SPECIAL','MERCHANDISE OTHERS','ICE CREAM','CAKES',
    'RAWMATS','BREADS','CELEBRATION CAKES (FLAGSLIP)','SUPPLIES','SCRAP ITEMS'
];
$placeholders = implode(',', array_fill(0, count($excluded), '?'));

// Special items
$specialItems = [];
if ($produceWheat) $specialItems[] = 11570;
if ($produceBurger) $specialItems[] = 12818;

if (!empty($specialItems)) {
    $placeholdersSpecial = implode(',', array_fill(0, count($specialItems), '?'));
    $extendquery = "AND (s.category NOT IN ($placeholders) OR s.item_id IN ($placeholdersSpecial))"; // <-- i-specify s.item_id
    $params = array_merge([$branch], $excluded, $specialItems, [$datefrom, $dateto]);
    $typeString = str_repeat('s', 1 + count($excluded)) . str_repeat('i', count($specialItems)) . 'ss';
} else {
    $extendquery = "AND s.category NOT IN ($placeholders)"; // <-- i-specify s.category
    $params = array_merge([$branch], $excluded, [$datefrom, $dateto]);
    $typeString = str_repeat('s', 1 + count($excluded)) . 'ss';
}

// ------------------
// Main query with join
// ------------------
$sql = "
    SELECT s.item_id, s.category, s.item_name, s.report_date, s.stock_in, i.yield_per_kilo
    FROM store_summary_data s
    LEFT JOIN store_items i ON s.item_id = i.id

    LEFT JOIN store_branchlist_production_exclude_items e 
        ON e.item_id = s.item_id 
       AND e.branch = s.branch
       AND e.exclude_this_item = 'YES'

    WHERE s.branch = ?
      $extendquery
      AND e.item_id IS NULL
      AND s.stock_in > 0
      AND s.report_date BETWEEN ? AND ?
    ORDER BY s.item_name, s.report_date
";

$stmt = $db->prepare($sql);

// Bind parameters by reference
$bindParams = [$typeString];
foreach ($params as $key => $value) {
    $bindParams[] = &$params[$key];
}
call_user_func_array([$stmt, 'bind_param'], $bindParams);

$stmt->execute();
$res = $stmt->get_result();

// ------------------
// Collect data
// ------------------
$data = [];
while ($row = $res->fetch_assoc()) {
    $code = $row['item_id'];
    $item = $row['item_name'];
    $date = $row['report_date'];
    $stock_in = floatval($row['stock_in']);
    $yield = floatval($row['yield_per_kilo'] ?? 0);

    $output = ($yield > 0) ? $stock_in / $yield : 0;

    $data[$item]['itemcode'] = $code;
    $data[$item]['dates'][$date] = ($data[$item]['dates'][$date] ?? 0) + $output;
}
$stmt->close();
?>

<style>
.tbody tr:nth-child(odd) td { background:#fef1eb; text-align:center; font-size:12px; }
.tbody tr:nth-child(even) td { background:#ffe3d5; text-align:center; font-size:12px; }
</style>

<table style="width: 100%" class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th colspan="34" style="text-align:center;">
                PRODUCTION REPORT<br>
                <small><?= htmlspecialchars($branch); ?></small><br>
                <small><?= htmlspecialchars($dateHeader); ?></small>
            </th>
        </tr>
        <tr>
            <th style="width:50px;text-align:center">#</th>
            <th style="width:250px;">ITEM NAME</th>
            <?php
            for ($date = clone $start; $date < $end; $date->modify('+1 day')) {
                echo '<th>' . $date->format('d') . '</th>';
            }
            ?>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody class="tbody">
        <?php
        if (empty($data)) {
            echo "<tr><td colspan='34' style='text-align:center;'>No records found.</td></tr>";
        } else {
            $ctr = 1;
            foreach ($data as $item => $details) {
                echo "<tr>";
                echo "<td>{$ctr}</td>";
                echo "<td style='text-align:left;'>{$item}</td>";

                $total = 0;
                for ($d = clone $start; $d < $end; $d->modify('+1 day')) {
                    $dateKey = $d->format('Y-m-d');
                    $val = $details['dates'][$dateKey] ?? 0;
                    $total += $val;
                    echo "<td>" . ($val > 0 ? number_format($val, 2) : '') . "</td>";
                }

                echo "<td><b>" . number_format($total, 2) . "</b></td>";
                echo "</tr>";
                $ctr++;
            }
        }
        ?>
    </tbody>
</table>
