<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);    
$functions = new TheFunctions;
$branch = $functions->AppBranch();

// Check branch
if (empty($branch)) {   
    echo "<div class='alert alert-danger text-center fw-bold' role='alert'>
            No branch selected. Please select a branch first.
          </div>";
    exit;
}

// Date range
$datefrom = $_POST['datefrom'] ?? null;
$dateto   = $_POST['dateto'] ?? null;

if (empty($datefrom) || empty($dateto)) {
    echo "<div class='alert alert-danger text-center fw-bold' role='alert'>
            Please select Date From and Date To.
          </div>";  
    exit;
}

$dateHeader = date("F d, Y", strtotime($datefrom)) . " to " . date("F d, Y", strtotime($dateto));

// Check if branch produces wheat loaf
$produceWheat = false;
$q = $db->prepare("SELECT is_produce_wheat_loaf FROM store_branchlist_wheatloaf WHERE branch = ?");
$q->bind_param("s", $branch);
$q->execute();
$result = $q->get_result()->fetch_assoc();
$q->close();

if (($result['is_produce_wheat_loaf'] ?? 'NO') === 'YES') {
    $produceWheat = true;
}

// Excluded categories
$excluded = [
    'BEVERAGES','BOTTLED WATER','COFFEE','ROSE HIGH-END BREADS','ROSE BINALOT & PASALUBONG',
    'ROSE NUTRI-DENSE','ALL TIME CAKE (CLASSIC)','PREMIUM CAKES (HIGH-END)','HIGH-END COFFEE',
    'FGFRAP','MILK TEA','ROSE CLASSIC SPECIAL','MERCHANDISE OTHERS','ICE CREAM','CAKES',
    'RAWMATS','BREADS','CELEBRATION CAKES (FLAGSLIP)','SUPPLIES','SCRAP ITEMS'
];

$placeholders = implode(',', array_fill(0, count($excluded), '?'));
$specialItemId = 11570; // Wheat Loaf ID

// Fetch items with yield_per_kilo
if ($produceWheat) {
    $sql = "
        SELECT id, product_name, yield_per_kilo
        FROM store_items 
        WHERE (category_name NOT IN ($placeholders) OR id = ?)
        AND status='ACTIVE'
        ORDER BY product_name ASC
    ";
    $params = array_merge($excluded, [$specialItemId]);
    $typeString = str_repeat('s', count($excluded)) . 'i';
} else {
    $sql = "
        SELECT id, product_name, yield_per_kilo
        FROM store_items 
        WHERE category_name NOT IN ($placeholders)
        AND status='ACTIVE'
        ORDER BY product_name ASC
    ";
    $params = $excluded;
    $typeString = str_repeat('s', count($excluded));
}

$stmt = $db->prepare($sql);
$stmt->bind_param($typeString, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = [
        'id'    => $row['id'],
        'name'  => $row['product_name'],
        'yield' => floatval($row['yield_per_kilo'] ?? 0)
    ];
}
$stmt->close();

// Fetch RM headers
$rmStmt = $db->prepare("
    SELECT item_id, item_name 
    FROM store_ba_rm_header 
    WHERE is_rm_header='YES'
    ORDER BY item_name ASC
");
$rmStmt->execute();
$rmRes = $rmStmt->get_result();

$rmHeaders = [];
while ($rm = $rmRes->fetch_assoc()) {
    $rmHeaders[] = [
        'id'   => $rm['item_id'],
        'name' => $rm['item_name']
    ];
}
$rmStmt->close();

// Fetch production data per item
$prodSql = "
    SELECT s.item_id, SUM(s.stock_in / NULLIF(i.yield_per_kilo,0)) AS production
    FROM store_summary_data s
    LEFT JOIN store_items i ON s.item_id = i.id
    WHERE s.branch = ?
      AND s.report_date BETWEEN ? AND ?
      AND s.stock_in > 0
    GROUP BY s.item_id
";
$prodStmt = $db->prepare($prodSql);
$prodStmt->bind_param("sss", $branch, $datefrom, $dateto);
$prodStmt->execute();
$prodRes = $prodStmt->get_result();

$productionData = [];
while ($row = $prodRes->fetch_assoc()) {
    $productionData[$row['item_id']] = floatval($row['production']);
}
$prodStmt->close();
?>

<style>

/* Main scroll container */
.freeze-container {
    overflow-x: auto;
    width: 100%;
}

/* No wrapping text */
.freeze-table th, .freeze-table td {
    white-space: nowrap;
}

/* FIXED WIDTHS for first 5 columns (IMPORTANT!) */
.freeze-table th:nth-child(1), .freeze-table td:nth-child(1){width:80px;min-width:80px;}
.freeze-table th:nth-child(2), .freeze-table td:nth-child(2){width:200px;min-width:200px;}
.freeze-table th:nth-child(3), .freeze-table td:nth-child(3){width:100px;min-width:100px;}
.freeze-table th:nth-child(4), .freeze-table td:nth-child(4){width:100px;min-width:100px;}
.freeze-table th:nth-child(5), .freeze-table td:nth-child(5){width:100px;min-width:100px;}

/* Sticky Column 1 */
.freeze-table th:nth-child(1),
.freeze-table td:nth-child(1){
    position: sticky;
    left: 0;
    background: white;
    z-index: 1;
}

/* Sticky Column 2 */
.freeze-table th:nth-child(2),
.freeze-table td:nth-child(2){
    position: sticky;
    left: 80px;
    background: white;
    z-index: 1;
}

/* Sticky Column 3 */
.freeze-table th:nth-child(3),
.freeze-table td:nth-child(3){
    position: sticky;
    left: 280px;
    background: white;
    z-index: 1;
}

/* Sticky Column 4 */
.freeze-table th:nth-child(4),
.freeze-table td:nth-child(4){
    position: sticky;
    left: 380px;
    background: white;
    z-index: 1;
}

/* Sticky Column 5 */
.freeze-table th:nth-child(5),
.freeze-table td:nth-child(5){
    position: sticky;
    left: 480px;
    background: white;
    z-index: 1;
}

</style>


<div class="freeze-container">
<table class="freeze-table table table-bordered">
    <thead>
        <tr>
            <th colspan="<?= 5 + count($rmHeaders) ?>" style="text-align:center; background-color:#dc7d4c">
                BUILD ASSEMBLY<br>
                <small><?= htmlspecialchars($branch); ?></small><br>
                <small><?= htmlspecialchars($dateHeader); ?></small>
            </th>
        </tr>

        <tr>
            <th style="background-color:#dc7d4c">IDCODE</th>
            <th style="background-color:#dc7d4c">BREADS</th>
            <th style="background-color:#dc7d4c">PROD.IN</th>
            <th style="background-color:#dc7d4c">YIELD</th>
            <th style="background-color:#dc7d4c">BATCHES</th>

            <?php foreach ($rmHeaders as $rm): ?>
                <th><?= htmlspecialchars($rm['name']); ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
    <?php
    $displayed = false;
    $totals = [
        'prodin' => 0,
        'batches' => 0,
        'rm' => array_fill(0, count($rmHeaders), 0)
    ];

    foreach ($items as $r) {
        $itemId = $r['id'];
        $yield = $r['yield'];
        $production = $productionData[$itemId] ?? 0;

        if ($production <= 0) continue;

        $prodin = $production * $yield;
        $batches = ($yield > 0 ? $prodin / $yield : 0);

        $totals['prodin'] += $prodin;
        $totals['batches'] += $batches;

        echo "<tr>";
        echo "<td>{$itemId}</td>";
        echo "<td>" . htmlspecialchars($r['name']) . "</td>";
        echo "<td>" . number_format($prodin,2) . "</td>";
        echo "<td>" . number_format($yield,2) . "</td>";
        echo "<td>" . number_format($batches,2) . "</td>";

        foreach ($rmHeaders as $index => $rm) {
            $rmStmt = $db->prepare("
                SELECT unit_in_grams 
                FROM store_bakers_guide 
                WHERE itemcode = ? AND rawmats_name = ?
                LIMIT 1
            ");
            $rmName = $rm['name'];
            $rmStmt->bind_param("is", $itemId, $rmName);
            $rmStmt->execute();
            $rmRes = $rmStmt->get_result()->fetch_assoc();
            $unitInGrams = $rmRes['unit_in_grams'] ?? 0;
            $rmStmt->close();

            $rmTotal = $unitInGrams * $batches;
            $totals['rm'][$index] += $rmTotal;

            echo "<td style='text-align:center'>" . ($rmTotal > 0 ? number_format($rmTotal,3) : "") . "</td>";
        }

        echo "</tr>";
        $displayed = true;
    }

    if (!$displayed) {
        echo "<tr><td colspan='".(5+count($rmHeaders))."' style='text-align:center;'>No records found.</td></tr>";
    }
    ?>
    </tbody>

    <!-- TOTAL ROW -->
    <tfoot>
        <tr style="background:#ffe3d5; font-weight:bold" class="sticky-total">
        	<td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>TOTAL:</td>
            <?php foreach ($totals['rm'] as $rmTotal): ?>
                <td style="text-align:center"><?= ($rmTotal>0 ? number_format($rmTotal,3) : '') ?></td>
            <?php endforeach; ?>
        </tr>
    </tfoot>
</table>
</div>