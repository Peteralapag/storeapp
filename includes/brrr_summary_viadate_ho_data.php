<?php

require '../init.php';
include '../db_config_main.php';
require '../class/brrr.class.php';


function connectSafe($host, $user, $pass, $dbname, $timeout = 2) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = new mysqli();
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, $timeout);
    $conn->real_connect($host, $user, $pass, $dbname);
    return ($conn->connect_errno === 0) ? $conn : false;
}

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$conn = connectSafe(CON_HOST, CON_USER, CON_PASSWORD, CON_NAME);
$connwms = connectSafe(CON_HOST, CON_USER, CON_PASSWORD, 'application_data');


$brrr = new brrr;


$datefrom = $_POST['from'] ?? '';
$dateto = $_POST['to'] ?? '';
$typereport = $_POST['type'] ?? '';

$formattedDateFrom = date("F j, Y", strtotime($datefrom));
$formattedDateTo = date("F j, Y", strtotime($dateto));
$branch = $brrr->GetSession('branch');

$IS_ONLINE = ($_SESSION['IS_ONLINE'] === 1 && $conn && $connwms);
if ($_SESSION['IS_ONLINE'] === 1 && $conn && $connwms)
{

	$totalsales = $brrr->salessummaryviadate($branch, $datefrom, $dateto, $conn);
	$totalsales_bakersonly = $brrr->salessummary_bakeronlyviadate($branch, $datefrom, $dateto, $conn);
	
	$bakercost = $brrr->bakercostviadateconn($branch, $datefrom, $dateto, $conn);
	$bakerratio = ($totalsales_bakersonly != 0) ? ($bakercost / $totalsales_bakersonly) * 100: 0;
	
	$sellingcost = $brrr->sellingcostconn($branch, $datefrom, $dateto, $conn);
	$sellingratio = ($totalsales != 0) ? ($sellingcost / $totalsales) * 100: 0;
	
	$month13costbaker = $bakercost / 12;
	$month13ratiobaker = ($totalsales_bakersonly != 0) ? ($month13costbaker / $totalsales_bakersonly) * 100: 0;
	
	$month13costselling = $sellingcost / 12;
	$month13ratioselling = ($totalsales != 0) ? ($month13costselling / $totalsales) * 100: 0;
	
	$totalheadcount = $brrr->summarygetdatacolumnviadate('total_headcount', $branch, $datefrom, $dateto, $conn);
	
	$salary_multipliers = [
	    'weekly' => 6,
	    'semi-monthly' => 13,
	    'monthly' => 26
	];
	
	$salarymultiplier = $salary_multipliers[$typereport] ?? 0;
	$idcodes = $brrr->getAllUniqueIdcodes($datefrom, $dateto, $conn);
	
	$totalsalary = 0;
	foreach ($idcodes as $idcode) {
	    $daily_salary = $brrr->salaryemployeemonthlyconn('salary_daily', $idcode, $conn);
	    
	    if (is_numeric($daily_salary)) {
	        $totalsalary += $daily_salary * $salarymultiplier;
	    }
	}
	
	$salary = $totalsalary;
	
	$mandatories = $brrr->mandatories($salary, $conn);
	
	$mandatories_cost = $totalheadcount * $mandatories;
	$mandatories_ratio = ($totalsales != 0) ? ($mandatories_cost / $totalsales) * 100 : 0;
	
	$agencyfee = 30 * $salarymultiplier;
	$agencyfeecost = $agencyfee * $totalheadcount;
	$agencyfee_ratio = ($totalsales != 0) ? ($agencyfeecost / $totalsales) * 100 : 0;
	
	$budgetperformance = ($totalsales != 0) ? ($cogs / $totalsales) * 100: 0;

	$totalsalariesandbenefitsbudget = $brrr->categorySelectValue('default_ratio', 'Salaries and Benefits', $conn);
	
	$totalsalariesandbenefitscost = $bakercost + $sellingcost + $month13costbaker + $month13costselling + $mandatories_cost + $agencyfeecost;
	$totalsalariesandbenefitsratio = $bakerratio + $sellingratio + $month13ratiobaker + $month13ratioselling + $mandatories_ratio + $agencyfee_ratio;
	$totalsalariesandbenefitsvariance = $totalsalariesandbenefitsbudget - $totalsalariesandbenefitsratio;
	$totalsalariesandbenefitsvariancecolor = ($totalsalariesandbenefitsvariance < 0) ? 'color:red;' : '';
	
	$query = "SELECT * FROM store_summary_data WHERE branch = ? AND report_date BETWEEN ? AND ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("sss", $branch, $datefrom, $dateto);
	$stmt->execute();
	$result = $stmt->get_result();
	
	$rows = [];
	while ($row = $result->fetch_assoc()) {
	    $rows[] = $row;
	}
	
	$budget_ratios = [];
	$cat_query = "SELECT category_name, default_ratio FROM store_brrr_category WHERE is_active = 1";
	$cat_result = $conn->query($cat_query);
	while ($cat = $cat_result->fetch_assoc()) {
	    $budget_ratios[$cat['category_name']] = (float)$cat['default_ratio'];
	}

	$cogs_data = $brrr->summaryCogsconn($branch, $datefrom, $dateto, $conn);
	$totalcogs = $cogs_data['total_cogs'];
	$breakdown = $cogs_data['breakdown'];
	
	$cogs = $totalcogs;

	$wms_expense_data = [];
	$sqlwms = "
	    SELECT 
	        CASE
	            WHEN item_description LIKE 'JS -%' THEN 'Janitorial Supplies'
	            WHEN item_description LIKE 'PM -%' THEN 'Packaging'
	            WHEN item_description LIKE 'OS -%' THEN 'Office Supplies'
	            WHEN item_description LIKE 'BM -%' THEN 'Branch Materials'
	        END AS prefix_group,
	        SUM(actual_quantity) AS total
	    FROM wms_branch_order
	    WHERE branch = ?
	      AND delivery_date BETWEEN ? AND ?
	      AND (
	          item_description LIKE 'JS -%' OR 
	          item_description LIKE 'PM -%' OR 
	          item_description LIKE 'OS -%' OR
	          item_description LIKE 'BM -%'
	      )
	    GROUP BY prefix_group
	";
	
	$wms_stmt = $connwms->prepare($sqlwms);
	$wms_stmt->bind_param("sss", $branch, $datefrom, $dateto);
	$wms_stmt->execute();
	$wms_result = $wms_stmt->get_result();
	
	while ($row = $wms_result->fetch_assoc()) {
	    if (!empty($row['prefix_group'])) {
	        $wms_expense_data[] = [
	            'prefix' => $row['prefix_group'],
	            'total' => (float)$row['total']
	        ];
	    }
	}

	
	$ho_expense_data = [];
	$ho_stmt = $conn->prepare("SELECT category, actual_amount FROM store_brrr_expense_ho_data WHERE branch = ? AND report_date BETWEEN ? AND ?");
	$ho_stmt->bind_param("sss", $branch, $datefrom,$dateto);
	$ho_stmt->execute();
	$ho_result = $ho_stmt->get_result();
	
	while ($row = $ho_result->fetch_assoc()) {
	    $ho_expense_data[] = [
	        'category' => $row['category'],
	        'amount' => (float) $row['actual_amount']
	    ];
	}
	
	
	
	$bakersZero = $brrr->getZeroSalaryBakersconn($branch, $datefrom, $dateto, $conn);
	$sellersZero = $brrr->getZeroSalarySellingconn($branch, $datefrom, $dateto, $conn);
	
	if (!empty($bakersZero)) {
	    $bakerstyle = 'color:red';
	    $bakertitle = implode(', ', $bakersZero);
	} else {
	    $bakerstyle = '';
	    $bakertitle = '';
	}
	
	
	if (!empty($sellersZero)) {
		$sellingstyle = 'color:red';
	    $sellingtitle = implode(', ', $sellersZero);
	    
	    } else {
	    $sellingstyle = '';
	    $sellingtitle = '';
	
	}



} else {

	$dailytotalsales = 0;
	$dailytotalsales_bakersonly = 0;
	
	
	$bakercost = 0;
	$bakerratio = 0;
	 
	$sellingcost = 0;
	$sellingratio = 0;
	
	$month13costbaker = 0;
	$month13ratiobaker = 0;
	
	$month13costselling = 0;
	$month13ratioselling = 0;
	
	$totalheadcount = 0;

	$totalsalary = 0;
	$salary = 0;
	
	
	$mandatories = 0;

	$mandatories_cost = 0;
	$mandatories_ratio = 0;
	
	$agencyfee = 0;
	$agencyfeecost = 0;
	$agencyfee_ratio = 0;
	
	
	$budgetperformance = 0;

	$totalsalariesandbenefitsbudget = 0;
	
	$totalsalariesandbenefitscost = 0;
	$totalsalariesandbenefitsratio = 0;
	$totalsalariesandbenefitsvariance = 0;
	$totalsalariesandbenefitsvariancecolor = 0;

	$rows = [];
	$budget_ratios = [];
	
	$dailycogs = 0;
    $ho_expense_data = [];
    $wms_expense_data = [];
    
    $bakerstyle = '';
	$bakertitle = '';
	$sellingstyle = '';
    $sellingtitle = '';

}
























?>






<div style="margin-bottom: 20px;">
    <p><strong>Branch:</strong> <?= htmlspecialchars($branch) ?></p>
    <p><strong>As of:</strong> <?= $formattedDateFrom ?> - <?= $formattedDateTo ?></p>
</div>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
    <h4 style="margin: 0;"><strong>Budget Ratios vs Revenue Report</strong></h4>
    <div style="text-align: left; width: 250px;">
        <p style="margin: 0;"><strong>Daily Sales:</strong> <?= number_format($totalsales, 2) ?></p>
    </div>
</div>

<div style="float: right; text-align: left; width: 250px;  margin-bottom:12px;">
    <p style="margin: 0; margin-bottom:6px"><strong>Daily COGS: </strong> <?= number_format($cogs,2) ?></p>
    <p style="margin: 0;"><strong>Budget Performance:</strong> <?= number_format($budgetperformance,3)?>%</p>
</div>

<div style="clear: both;"></div>

<table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="font-weight:bold">
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
            <td colspan="3"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" title="<?= $bakertitle?>" style="<?= $bakerstyle?>">Baker</td>
            <td><?= number_format($bakerratio, 3) ?>%</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" title="<?= $sellingtitle?>" style="<?= $sellingstyle?>">Selling</td>
            <td><?= number_format($sellingratio, 3) ?>%</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
        <tr>
            <td style="text-align:center">Benefits</td>
            <td colspan="3"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>
        <tr>
            <td></td>
            <td colspan="3">13<sup>th</sup> Month Baker</td>
            <td><?= number_format($month13ratiobaker,3)?>%</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

		
		
		
		<tr>
            <td></td>
            <td colspan="3">13<sup>th</sup> Month Selling</td>
            <td><?= number_format($month13ratioselling,3)?>%</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

		
		

        <tr>
            <td></td>
            <td colspan="3">Mandatories</td>
            <td><?= number_format($mandatories_ratio,3)?>%</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Agency Fee</td>
            <td><?= number_format($agencyfee_ratio,3)?>%</td>
            <td><?= number_format($totalsalariesandbenefitsbudget,3)?>%</td>
            <td><?= number_format($totalsalariesandbenefitscost,2)?></td>
            <td><?= number_format($totalsalariesandbenefitsratio,3)?>%</td>
            <td style="<?= $totalsalariesandbenefitsvariancecolor?>"><?= number_format($totalsalariesandbenefitsvariance,3)?>%</td>
            
        </tr>
        
    </thead>

    <tbody>
    <?php
    
    
    	$totalexpense = 0;
		$totalactualratio = 0;
		$totalbudget = 0;
		$totalvariance = 0;
    
    
    	if (!empty($rows)) {
		    $row = $rows[0];
		    $categories = json_decode($row['category_json'], true) ?? [];
		    $amounts = json_decode($row['amount_json'], true) ?? [];
		
		    if (is_array($categories) && is_array($amounts)) {
		        for ($i = 0; $i < count($categories); $i++) {
		            $category = $categories[$i];
		            $amount = isset($amounts[$i]) ? floatval($amounts[$i]) : 0;
		            $actualratio = ($totalsales != 0) ? (($amount / $totalsales) * 100): 0;
		            
		            $budget = isset($budget_ratios[$category]) ? $budget_ratios[$category] : 0;
		            $variance = $budget - $actualratio;
		            
		            $varianceColor = ($variance < 0) ? 'color:red;' : '';
		            
		            $totalbudget += $budget;
		            $totalvariance += $variance;
		            
		            $totalexpense += $amount;
		            $totalactualratio += $actualratio;
		
		            ?>
		            
		            <tr>
		            	<td colspan='4'><?= htmlspecialchars($category)?></td>
		            	<td></td>
		            	<td><?= number_format($budget,3)?>%</td>
		            	<td><?= number_format($amount, 2)?></td>
		            	<td><?= number_format($actualratio, 3)?>%</td>
		            	<td style="<?= $varianceColor ?>"><?= number_format($variance,3)?>%</td>

		            </tr>
		            
		            <?php
		        }
		    }
		}
		
		
		// Display head office data
		

		
		
		if (!empty($ho_expense_data)) {
		    foreach ($ho_expense_data as $item) {
		        $category = $item['category'];
		        $amount = $item['amount'];
		        $actualratio = ($totalsales != 0) ? (($amount / $totalsales) * 100): 0;
		        
		        $budget = isset($budget_ratios[$category]) ? $budget_ratios[$category] : 0;
	            $variance = $budget - $actualratio;
	            
	            $varianceColor = ($variance < 0) ? 'color:red;' : '';
	            
	            $totalbudget += $budget;
	            $totalvariance += $variance;

		        $totalexpense += $amount;
		        $totalactualratio += $actualratio;
		
		        ?>
		        <tr>
		        	<td colspan='4'><?= htmlspecialchars($category)?><span style='color:gray'> (HEAD OFFICE DATA)</span></td>
		        	<td></td>
		        	<td><?= number_format($budget,3)?>%</td>
		        	<td><?= number_format($amount, 2)?></td>
		        	<td><?= number_format($actualratio, 3)?>%</td>
		        	<td style="<?= $varianceColor ?>"><?= number_format($variance,3)?>%</td>
		        </tr>
		        
		        <?php
		    }
		}
		
		// Display WMS data
		if (!empty($wms_expense_data)) {
		    foreach ($wms_expense_data as $row) {
		        $label = $row['prefix'];
		        $amount = $row['total'];
		        $actualratio = ($totalsales != 0) ? (($amount / $totalsales) * 100): 0;
		        
		        $budget = isset($budget_ratios[$category]) ? $budget_ratios[$category] : 0;
	            $variance = $budget - $actualratio;
	            
	            $varianceColor = ($variance < 0) ? 'color:red;' : '';

				$totalbudget += $budget;
	            $totalvariance += $variance;

		        $totalexpense += $amount;
		        $totalactualratio += $actualratio;
		
		        ?>
		        
		        <tr>
		        	<td colspan='4'><?= htmlspecialchars($label)?><span style='color:gray'> (WMS DATA)</span></td>
		        	<td></td>
		        	<td><?= number_format($budget,3)?>%</td>
		        	<td><?= number_format($amount, 2)?></td>
		        	<td><?= number_format($actualratio, 3)?>%</td>
		        	<td style="<?= $varianceColor ?>"><?= number_format($variance,3)?>%</td>

		        </tr>
		        
		        <?php
		    }
		}
		
			$overallvariance = $totalsalariesandbenefitsvariance + $totalvariance;
			$overallvarianceColor = ($overallvariance < 0) ? 'color:red;' : '';
		
		?>
		
		
		
		<tr style='border-top:2px solid gray'>
			<td colspan='4'></td>
			<td></td>
			<td><?= number_format($totalsalariesandbenefitsbudget + $totalbudget, 3)?></td>
			<td><?= number_format($totalsalariesandbenefitscost + $totalexpense, 2)?></td>
			<td><?= number_format($totalsalariesandbenefitsratio + $totalactualratio, 3)?>%</td>
			<td style="<?= $overallvarianceColor?>"><?= number_format($overallvariance, 3)?></td>
		</tr>
		    
		   
		


    </tbody>
</table>


<div id="summaryresult"></div>



