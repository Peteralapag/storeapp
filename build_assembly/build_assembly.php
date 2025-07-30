<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$classba = new BuildAssembly;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

if(isset($_POST['search'])) {
    $item_name = $_POST['search'];
    $shift = $_SESSION['session_shift'];
    $q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch' AND item_name LIKE '%$item_name%'";
} else {
    if(isset($_SESSION['session_shift'])) {
        $shift = $_SESSION['session_shift'];
        $q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch'";
    } else {
        $shift = '';
        $q = "WHERE report_date='$trans_date' AND branch='$store_branch'";
    }
}

$queryItem = "SELECT * FROM store_ba_rawmats ORDER by id ASC";
$itemResults = mysqli_query($db, $queryItem);
$td_data = array();
while ($iROWS = mysqli_fetch_array($itemResults)) {
    $td_data[] = $iROWS;
}
?>
<style>
.table-ba th, .table-ba td {padding: 5px; border: 1px solid #cecece;}
.table-ba th {background: dodgerblue; color: #fff; text-align: center;white-space:nowrap; font-weight:normal; padding: 5px 10px 5px 10px}
.selected {color: #fff; background: #7ed1fc; font-weight: bold;}
.table-data th {background: #d1d1d1; color: #232323;}
.table-data td {background: #f1f1f1;}
.table-data {margin-bottom: 0 !important;}
.gran-total td{
	background: #f1f1f1;
}
.highlight {
  background-color: #fbfbd6;
}
</style>
<table class="table-ba" style="width: auto">
    <tr>
	    <th class="bg-warning" style="width: 50px !important; text-align: center;">#</th>
	    <th style="white-space: nowrap !important;">BREADS</th>
        <th style="width: 100px !important;">PRODUCTION IN</th>
        <th style="width: 100px !important;">YIELD</th>
        <th style="width: 100px !important;">BATCHES</th>
        <?php foreach ($td_data as $jROWS) { ?>
            <th style="width: 200px !important; background: green;">CF - <?php echo $jROWS['rawmats_name']; ?></th>
            <th style="width: 200px !important;"><?php echo $jROWS['rawmats_name']; ?></th>
        <?php } ?>
        <th style="width: 200px !important; background: orange;">&nbsp;&nbsp;<strong>Total</strong>&nbsp;&nbsp;</th>
    </tr>
    <?php
    $query = "SELECT * FROM store_fgts_data $q ORDER BY id DESC";
    $result = mysqli_query($db, $query);
    if ($result->num_rows > 0)
    {
    	$x=0;
        $total_raw_materials = array_fill(0, count($td_data), 0); // Initialize array to store total raw materials
        while ($ROWS = mysqli_fetch_array($result))
        {
        	$x++;
            $rowid = $ROWS['id'];
            $item_name = $ROWS['item_name'];
            $item_id = $ROWS['item_id'];
            $unit_price = $ROWS['unit_price'];
            $kilo_used = $ROWS['kilo_used'];
            $actual_yield = $ROWS['actual_yield'];
            $item_code = $classba->GetItemCode($item_id, $db);
            $yielding = $classba->GetYielding($item_id, $db);
            $row_totals = array(); // Initialize array to store row totals            
?>
            <tr>
                <td style="text-align: center;"><?php echo $x; ?></td>
                <td style="white-space: nowrap;"><?php echo $item_name; ?></td>
                <td style="width: 100px !important;text-align:right;padding-right:10px"><?php echo $actual_yield; ?></td>
                <td style="width: 100px !important;text-align:right;padding-right:10px"><?php echo $yielding; ?></td>
                <td style="width: 100px !important;text-align:right;padding-right:10px"><?php echo $kilo_used; ?></td>
                <?php
                foreach ($td_data as $j => $jROWS)
                {
                    $mn = $jROWS['rawmats_name'];
                    $cf = $classba->GetMatsTotal($mn, $item_code, $db);
                    $e_five = ($kilo_used * $classba->GetMatsTotal($mn, $item_code, $db));
                    $total_raw_materials[$j] += $e_five; // Increment total raw materials array
                    $row_totals[] = $e_five; // Store row total for display
                    if($e_five > 0)
                    {
                    	$poss_num = 'background: #fbf4e8; border: 1px solid orange; font-weight: 600';
                    } else {
                    	$poss_num = '';
                    }
                    ?>
                    <td style="text-align:right;padding-right:10px;"><?php echo $classba->GetMatsTotal($mn, $item_code, $db); ?></td>
                    <td style="text-align:right;padding-right:10px;<?php echo $poss_num?>"><?php echo $e_five; ?></td>
<?php 			}  ?>
                <td style="width:200px !important;text-align:right"><?php echo number_format(array_sum($row_totals),2); ?></td>
            </tr>
        <?php } ?>
        <tr class="gran-total">
            <td colspan="5" style="text-align: right;font-weight:600;padding-right:10px">Grand Total:</td>
            <?php foreach ($total_raw_materials as $total) { ?>
            	<td style="text-align: right;padding-right:10px">--</td>
                <td style="padding-right:10px;text-align:right;border-top: 3px solid #232323"><?php echo number_format($total,2); ?></td>
            <?php } ?>
            <td><?php /* echo array_sum($total_raw_materials); */ ?></td>
        </tr>
    <?php } else { ?>
        <tr>
            <td colspan="<?php echo count($td_data) * 2 + 6; ?>">No Data</td>
        </tr>
    <?php } ?>
</table>
<script>
$(document).ready(function()
{
	$('.table-ba tr').click(function() {
        // Remove highlight class from all rows
        $('.table-ba tr').removeClass("highlight");
        
        // Add highlight class to the clicked row
        $(this).addClass("highlight");
    });
});
</script>

