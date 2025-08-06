<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$functions = new TheFunctions();

$monthname = isset($_POST['monthname']) ? $_POST['monthname'] : '';
$yearname = isset($_POST['yearname']) ? $_POST['yearname'] : '';
$branch = isset($_POST['branch']) ? $_POST['branch'] : '';

$monthNumber = '';
$monthyear = '';

if (!empty($monthname) && !empty($yearname)) {
    $monthNumber = $functions->GetMonthNumber($monthname);
    $monthyear = $yearname . '-' . $monthNumber;
}

?>
<style>
.total-wrapper td {
    border-top: 3px solid #aeaeae;
    font-weight: bold;
    text-align: center;
}
.no-data {
    text-align: center;
    font-weight: bold;
}
.tbody tr:nth-child(odd) td {
    background: #fef1eb;
    text-align: center;
    font-size: 12px;
}
.tbody tr:nth-child(even) td {
    background: #ffe3d5;
    text-align: center;
    font-size: 12px;
}
</style>

<div class="alert alert-success">
    Please verify the accuracy of all data in your Summary Report KL.USED for the selected month before presenting the Production Data Report.
</div>

<table style="width: 100%" class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th colspan="34">PRODUCTION REPORT - <?php echo htmlspecialchars($monthname) . ' - ' . htmlspecialchars($yearname); ?></th>
        </tr>
        <tr>
            <th style="width:50px;text-align:center">#</th>
            <th style="width:250px;">ITEM NAME</th>
            <?php
            for ($i = 1; $i <= 31; $i++) {
                echo '<th>' . sprintf('%02d', $i) . '</th>';
            }
            ?>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody class="tbody">
        <?php
        if (!empty($branch) && !empty($monthNumber) && !empty($yearname)) {
            $queryItem = "
                SELECT item_id, item_name FROM store_summary_data 
                WHERE kilo_used > 0 
                AND branch = ? 
                AND MONTH(report_date) = ? 
                AND YEAR(report_date) = ? 
                AND 
                	(
                		category = 'ROSE CLASSIC HOT BREAD' OR
                		category = 'ROSE TASTY LOAF' OR
                		category = 'ROSE HIGH-END BREADS' OR
                		category = 'ROSE NUTRI-DENSE' OR
                		category = 'ROSE BINALOT & PASALUBONG'
                	)
                	 
                GROUP BY item_name
            ";

            $stmt = $db->prepare($queryItem);
            $stmt->bind_param("sss", $branch, $monthNumber, $yearname);
            $stmt->execute();
            $itemResult = $stmt->get_result();

            if ($itemResult->num_rows > 0) {
                $numbering = 0;
                $dailyTotals = array_fill(1, 31, 0);

                while ($ROW = $itemResult->fetch_assoc()) {
                    $numbering++;
                    $item_id = $ROW['item_id'];
                    $item_name = $ROW['item_name'];

                    echo '<tr>';
                    echo '<td>' . $numbering . '</td>';
                    echo '<td>' . htmlspecialchars($item_name) . '</td>';

                    $monthlyTotal = 0;

                    for ($i = 1; $i <= 31; $i++) {
                        $daysNumber = sprintf('%02d', $i);
                        $reportDate = $monthyear . '-' . $daysNumber;
                        $totalValue = $functions->productionDataTotalPerDay($item_id, $branch, $reportDate, $db);

                        if (is_numeric($totalValue) && $totalValue > 0) {
                            $monthlyTotal += $totalValue;
                            $dailyTotals[$i] += $totalValue;
                        } else {
                            $totalValue = '';
                        }

                        echo '<td style="text-align:right">' . htmlspecialchars($totalValue) . '</td>';
                    }

                    echo '<td style="text-align:right">' . htmlspecialchars($monthlyTotal) . '</td>';
                    echo '</tr>';
                }

                echo '<tr>';
                echo '<td colspan="2">Total</td>';

                for ($i = 1; $i <= 31; $i++) {
                    $totalDaysItem = $dailyTotals[$i] <= 0 ? '' : $dailyTotals[$i];
                    echo '<td style="text-align:right">' . htmlspecialchars($totalDaysItem) . '</td>';
                }

                $totalMonthly = array_sum($dailyTotals);
                echo '<td style="text-align:right">' . htmlspecialchars($totalMonthly) . '</td>';
                echo '</tr>';
            } else {
                echo '<tr><td colspan="34">No Results Found</td></tr>';
            }

            $stmt->close();
        } else {
            echo '<tr><td colspan="34">No Results Found</td></tr>';
        }

        $db->close();
        ?>
    </tbody>
</table>
