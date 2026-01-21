<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../init.php';
require '../class/functions.class.preview.php';
require '../class/functions_forms.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$functions = new TheFunctions;
$FunctionForms = new FunctionForms;
$preview = new preview;

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$q = "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
if(isset($_POST['search'])) {
    $item_name = $_POST['search'];
    $q .= " AND item_name LIKE '%$item_name%'";
}

function checkRmPcountPosted($branch,$transdate,$shift,$db)
{
    $sql = "SELECT * FROM store_rm_pcount_data 
            WHERE branch='$branch'
              AND report_date='$transdate'
              AND shift='$shift'
              AND posted='Posted'
              AND status='Closed'";
    $result = $db->query($sql);
    return ($result->num_rows > 0) ? 1 : 0;
}

// Check posting status
if(checkRmPcountPosted($branch,$transdate,$shift,$db) == '0'){
    $styleStatus = 'background-color:#f7e9d5';
    $contenteditableStatus = 'contenteditable="true"';
} else {
    $styleStatus = '';
    $contenteditableStatus = '';
}

// Shifting info
$myShifting = $_SESSION['appstore_shifting'];
$prevCutPcountStatus = $myShifting == 2
    ? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db)
    : $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

$shiftback = $myShifting == 2
    ? $FunctionForms->twoShiftingShiftGetback($shift, $transdate, $branch, $db)
    : $FunctionForms->threeShiftingShiftGetback($shift, $transdate, $branch, $db);

$transdateback = $myShifting == 2
    ? $FunctionForms->twoShiftingTransDateGetback($shift, $transdate, $branch, $db)
    : $FunctionForms->threeShiftingTransDateGetback($shift, $transdate, $branch, $db);

/* -------------------------------------------------------
   FIXED preloadData() — now supports SUM for duplicates
----------------------------------------------------------*/
function preloadData($db, $table, $keyField, $valueField, $where) {

    $arr = [];
    $sql = "SELECT $keyField, $valueField FROM $table $where";
    $res = mysqli_query($db, $sql);

    while ($r = mysqli_fetch_assoc($res)) {
        $key = $r[$keyField];
        $val = floatval($r[$valueField]); // ensure numeric

        if (!isset($arr[$key])) {
            $arr[$key] = 0;
        }

        $arr[$key] += $val; // SUM all values for same item_name
    }

    return $arr;
}

/* -------------------------------------------------------
   Load all data (auto SUM if duplicates exist)
----------------------------------------------------------*/
$receivingData   = preloadData($db, 'store_rm_receiving_data', 'item_name', 'quantity', $q);

$transferInData  = preloadData(
    $db,
    'store_rm_transfer_data',
    'item_name',
    'weight',
    "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND transfer_to='$branch'"
);

$transferOutData = preloadData(
    $db,
    'store_rm_transfer_data',
    'item_name',
    'weight',
    "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND transfer_from='$branch'"
);

$coutData     	 = preloadData($db, 'store_rm_summary_data', 'item_name', 'counter_out', $q);

$badorderData    = preloadData($db, 'store_rm_badorder_data', 'item_name', 'actual_count', $q);
$pcountData      = preloadData($db, 'store_rm_pcount_data', 'item_name', 'actual_count', $q);
$summaryData     = preloadData($db, 'store_rm_summary_data', 'item_name', 'beginning', $q);

/* -------------------------------------------------------
   Get ALL DISTINCT ITEMS
----------------------------------------------------------*/
$combinedQuery = "
    SELECT DISTINCT item_name, item_id FROM (
        SELECT item_name, item_id FROM store_rm_receiving_data $q
        UNION ALL
        SELECT item_name, item_id FROM store_rm_transfer_data $q
        UNION ALL
        SELECT item_name, item_id FROM store_rm_badorder_data $q
        UNION ALL
        SELECT item_name, item_id FROM store_rm_pcount_data $q
        UNION ALL
        SELECT item_name, item_id FROM store_rm_summary_data $q
        UNION ALL
        SELECT item_name, item_id FROM store_rm_inventory_record_data $q
    ) AS combined_tables
";

$result = mysqli_query($db, $combinedQuery);
if (!$result) die("Query failed: " . mysqli_error($db));

?>
<style>
thead {
    position: sticky;
    top: 0;
    background-color: #f2f2f2;
}
</style>

<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
<thead style="background-color:#fcf3e6">
<tr>
    <th style="width:50px;text-align:center">#</th>
    <th>ITEM ID</th>
    <th>ITEM NAME</th>
    <th>BEG</th>
    <th>DLVRY</th>
    <th>TX-IN</th>
    <th>TX-OUT</th>
    <th>C-IN</th>
    <th>B.O</th>
    <th>P. COUNT</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;
while($ROW = mysqli_fetch_assoc($result)) {
    $i++;
    $itemname = $ROW['item_name'];
    $item_id  = $ROW['item_id'];

    $beginningval  = $summaryData[$itemname] ?? 0;
    $receivingval  = $receivingData[$itemname] ?? 0;
    $transferinval = $transferInData[$itemname] ?? 0;
    $transferoutval= $transferOutData[$itemname] ?? 0;
    $coutval       = $coutData[$itemname] ?? 0;
    $boval         = $badorderData[$itemname] ?? 0;
    $actualCount   = $pcountData[$itemname] ?? 0;

    $rowColor = ($actualCount == '' || $actualCount == 0) ? '#d7e7f7' : '';
?>
    <tr id="rowstyle<?php echo $i?>" style="background-color:<?php echo $rowColor?>">

        <td><?php echo $i?></td>
        <td><?php echo $item_id?></td>
        <td><?php echo $itemname?></td>

        <td id="beginning_<?php echo $i ?>" style="text-align:center;">
            <?php echo $beginningval?>
        </td>

        <td id="receiving_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>" 
            <?php echo $contenteditableStatus?>
            onkeyup="receiving('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')">
            <?php echo $receivingval?>
        </td>

        <td id="transferinqty_<?php echo $i ?>" style="text-align:center;">
            <?php echo $transferinval?>
        </td>

        <td id="transferoutqty_<?php echo $i?>" style="text-align:center;"
            ondblclick="viewtransfer('transferout','<?php echo $itemname?>','<?php echo $item_id?>')">
            <?php echo $transferoutval?>
        </td>
		
		<td id="cout_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>" 
            <?php echo $contenteditableStatus?>
            onkeyup="cout('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')">
            <?php echo $coutval?>
        </td>

		
		
        <td id="badorder_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>" 
            <?php echo $contenteditableStatus?>
            onkeyup="badorder('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')">
            <?php echo $boval?>
        </td>

        <td id="actualcount_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>" 
            <?php echo $contenteditableStatus?>
            onkeyup="actualcount('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')">
            <?php echo $actualCount?>
        </td>

    </tr>
<?php
}
?>
</tbody>
</table>

<script>
function receiving(params,itemname,itemid){
    var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#receiving_'+params).text();
    var mode = 'savearmreceiving_new';


    $.post("./actions/actions.php", {mode, itemname, qty, itemid, branch, transdate, shift}, 
        function(data){
            console.log(data);

        }
    ).fail(function(error){
        console.log(error);

    });
}

function actualcount(params,itemname,itemid){
    var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#actualcount_'+params).text();
    var mode = 'savearmactualcount_new';


    $.post("./actions/actions.php", {mode, itemname, qty, itemid, branch, transdate, shift}, 
        function(data){
            console.log(data);

        }
    ).fail(function(error){
        console.log(error);

    });
}

function badorder(params,itemname,itemid){
    var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#badorder_'+params).text();
    var mode = 'savermbadorder_new';

    $.post("./actions/actions.php", {mode, itemname, qty, itemid, branch, transdate, shift}, 
        function(data){
            console.log(data);

        }
    ).fail(function(error){
        console.log(error);

    });
}

function cout(params,itemname,itemid){
    var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#cout_'+params).text();
    var mode = 'savermcout_new';

    $.post("./actions/actions.php", {mode, itemname, qty, itemid, branch, transdate, shift}, 
        function(data){
            console.log(data);

        }
    ).fail(function(error){
        console.log(error);

    });
}

</script>
