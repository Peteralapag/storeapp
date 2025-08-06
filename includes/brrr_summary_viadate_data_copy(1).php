<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../init.php';
require '../class/brrr.class.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$brrr = new brrr;


$datefrom = $_POST['from'] ?? '';
$dateto = $_POST['to'] ?? '';
$typereport = $_POST['type'] ?? '';

$formattedDateFrom = date("F j, Y", strtotime($datefrom));
$formattedDateTo = date("F j, Y", strtotime($dateto));


$branch = $brrr->GetSession('branch');
$reportdate = $brrr->GetSession('branchdate');


$totalsales = $brrr->salessummaryviadate($branch, $datefrom, $dateto, $db);
$totalsales_bakersonly = $brrr->salessummary_bakeronlyviadate($branch, $datefrom, $dateto, $db);


$bakercost = $brrr->bakercostviadate($branch, $datefrom, $dateto, $db);
$bakerratio = ($totalsales_bakersonly != 0) ? ($bakercost / $totalsales_bakersonly) * 100: 0;
 
$sellingcost = $brrr->sellingcostviadate($branch, $datefrom, $dateto, $db);
$sellingratio = ($totalsales != 0) ? ($sellingcost / $totalsales) * 100: 0;

$month13cost = $brrr->month13viadate($branch, $datefrom, $dateto, $db);
$month13ratio = ($totalsales != 0) ? ($month13cost / $totalsales) * 100: 0;


$totalheadcount = $brrr->summarygetdatacolumnviadate('total_headcount', $branch, $datefrom, $dateto, $db);
$salary = $brrr->minimumwage('monthly_wage', $db);
$mandatories = $brrr->mandatories($salary, $db);


$mandatories_cost = $totalheadcount * $mandatories;
$mandatories_ratio = ($totalsales != 0) ? ($mandatories_cost / $totalsales) * 100 : 0;

$agencyfee = 30;
$agencyfeecost = $agencyfee * $totalheadcount;
$agencyfee_ratio = ($totalsales != 0) ? ($agencyfeecost / $totalsales) * 100 : 0;

$cogs = $brrr->getDailyCogsTotalviadate($datefrom, $dateto, $db);
$budgetperformance = ($totalsales != 0) ? ($cogs / $totalsales) * 100: 0;


$query = "SELECT * FROM store_brrr_summary_data WHERE branch = ? AND report_date BETWEEN ? AND ?";
$stmt = $db->prepare($query);
$stmt->bind_param("sss", $branch, $datefrom, $dateto);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}





$cogsdetectionexist = $brrr->cogsdetectionexist($reportdate, $reportdate, $db);



?>

<div style="margin-bottom: 20px;">
	<div class="alert alert-warning">
		<strong>Note:</strong> Some data are missing. Please download them from Head Office:
	</div>

	<div style="display: flex; justify-content: flex-end; gap: 10px;">
		<?php if ($cogsdetectionexist != 1): ?>
			<button class="btn btn-primary" onclick="downloadHODataset('cogs')"><i class="fa fa-download" aria-hidden="true"></i> Download COGS</button>
		<?php endif; ?>

		<button class="btn btn-warning" onclick="downloadHODataset('expense')"><i class="fa fa-download" aria-hidden="true"></i> Download Other Expense</button>
	</div>
</div>






<div style="margin-bottom: 20px;">
    <p><strong>Branch:</strong> <?= htmlspecialchars($branch) ?></p>
    <p><strong>As of : <?= $formattedDateFrom?> - <?= $formattedDateTo?></strong></p>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
    <h4 style="margin: 0;"><strong>Budget Ratios vs Revenue Report</strong></h4>
    <div style="text-align: left; width: 250px;">
        <p style="margin: 0;"><strong><?= ucfirst($typereport)?> Sales:</strong> <?= number_format($totalsales, 2) ?></p>
    </div>
</div>

<div style="float: right; text-align: left; width: 250px;">
    <p style="margin: 0;"><strong><?= ucfirst($typereport)?> COGS: </strong> <?= number_format($cogs,2) ?></p>
    <p style="margin: 0;"><strong>Budget Performance:</strong> <?= number_format($budgetperformance, 2)?>%</p>
</div>

<div style="clear: both;"></div>

<table class="table" style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #f0f0f0;">
        <tr>
            <td colspan="2">Salaries & Benefits</td>
            <td colspan="2"></td>
            <td>Ratio</td>
            <td>Budget</td>
            <td>Actual Amount</td>
            <td>Actual Ratio</td>
            <td>Variance (-/+)</td>
        </tr>
        <tr>
            <td style="text-align:center">Salaries</td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Baker</td>
            <td><?= number_format($bakerratio, 3) ?>%</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Selling</td>
            <td><?= number_format($sellingratio, 3) ?>%</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td style="text-align:center">Benefits</td>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">13<sup>th</sup> Month</td>
            <td><?= number_format($month13ratio,3)?>%</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Mandatories</td>
            <td><?= number_format($mandatories_ratio,2)?>%</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Agency Fee</td>
            <td><?= number_format($agencyfee_ratio,2)?>%</td>
            <td colspan="7"></td>
        </tr>
    </thead>

    <tbody style="background-color: #f0f0f0;">
    <?php
    if (!empty($rows)) {
        $row = $rows[0];

        $categories = json_decode($row['category_json'], true) ?? [];
        $amounts = json_decode($row['amount_json'], true) ?? [];

        if (is_array($categories) && is_array($amounts)) {
            for ($i = 0; $i < count($categories); $i++):
                $category = $categories[$i];
                $amount = isset($amounts[$i]) ? floatval($amounts[$i]) : 0;
                
                $actualratio = (($amount / $totalsales) * 100);
    ?>
        <tr>
            <td colspan="4"><?= htmlspecialchars($category) ?></td>
            <td></td>
            <td></td>
            <td><?= number_format($amount, 2) ?></td>
            <td><?= number_format($actualratio,2)?>%</td>
            <td></td>
        </tr>
    <?php
            endfor;
        }
    }
    ?>
    </tbody>
</table>




<script>

function cogssyncin(){
    var reportDate = '<?= $reportdate ?>';

    $.post('../includes/sync_cogs.php', { reportdate: reportDate }, function (response) {
        try {
            var res = JSON.parse(response);
            $('#sync-status').text(res.message);
        } catch (e) {
            $('#sync-status').text('Unexpected error occurred.');
        }
    });
}


</script>
