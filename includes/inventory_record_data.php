<?php
require '../init.php';
require '../class/functions.class.preview.php';
require '../class/functions_forms.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$function = new TheFunctions;
$FunctionForms = new FunctionForms;
$preview = new preview;

$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

$q = "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";

if(isset($_POST['search']))
{
	$item_name = $_POST['search'];
	$q = "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND item_name LIKE '%$item_name%'";
} 
else
{
	$q = "WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift'";
}


if($functions->checkSummaryPosted($branch,$transdate,$shift,$db) == '0'){
	$styleStatus = 'background-color:#f7e9d5';
	$contenteditableStatus = 'contenteditable="true"';
	$chargesfunctionname = 'addChargesEmployee';
	
	$styleStatusCharges = 'background-color:#f7e9d5';
	$contenteditableStatusCharges = 'contenteditable="true"';
} else {
	$styleStatus = '';
	$contenteditableStatus = '';
	$chargesfunctionname = 'postedna';
	
	$styleStatusCharges = '';
	$contenteditableStatusCharges = '';
}
$myShifting = $_SESSION['appstore_shifting'];
$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGet($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGet($shift, $transdate, $branch, $db);

$shiftback = $myShifting == 2? $FunctionForms->twoShiftingShiftGetback($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingShiftGetback($shift, $transdate, $branch, $db);
$transdateback = $myShifting == 2? $FunctionForms->twoShiftingTransDateGetback($shift, $transdate, $branch, $db): $FunctionForms->threeShiftingTransDateGetback($shift, $transdate, $branch, $db);
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
			<th>ITEMS</th>
			<th>BEG</th>
			<th>STK IN</th>
			<th>F.DOUGH</th>
			<th>TX-IN</th>
			<th>TOTAL</th>
			<th>TX-OUT</th>
			<th>CHARGES</th>
			<th>EMPLOYEE NAME</th>
			<th>B.O</th>
			<th >DAMAGED</th>
			<th>T.G.A.S</th>
			<th>P. COUNT</th>
			<th>SOLD</th>
		</tr>
	</thead>
	<tbody>	
	<?php
		
		$tfgts = "SELECT item_name, item_id FROM store_fgts_data $q";
		$ttransfer = "SELECT item_name, item_id FROM store_transfer_data $q";
		$tcharges = "SELECT item_name, item_id FROM store_charges_data $q";
		$tsnacks = "SELECT item_name, item_id FROM store_snacks_data $q";
		$tbo = "SELECT item_name, item_id FROM store_badorder_data $q";
		$tdamage = "SELECT item_name, item_id FROM store_damage_data $q";
		$tcomplimentary = "SELECT item_name, item_id FROM store_complimentary_data $q";
		$treceiving = "SELECT item_name, item_id FROM store_receiving_data $q";
		$tfrozendough = "SELECT item_name, item_id FROM store_frozendough_data $q";
		
		if ($prevCutPcountStatus == '0') {
		    $tpcount = "SELECT item_name, item_id FROM store_pcount_data WHERE branch='$branch' AND report_date='$transdateback' AND shift='$shiftback'";
		} else {
		    $tpcount = "SELECT item_name, item_id FROM store_pcount_data $q";
		}
		
		$tsummary = "SELECT item_name, item_id FROM store_summary_data $q";
		$tinventoryrecord = "SELECT item_name, item_id FROM store_inventory_record_data $q";
		
		$query = "
		    SELECT DISTINCT item_name, item_id FROM (
		        ($tfgts)
		        UNION ALL
		        ($ttransfer)
		        UNION ALL
		        ($tcharges)
		        UNION ALL
		        ($tsnacks)
		        UNION ALL
		        ($tbo)
		        UNION ALL
		        ($tdamage)
		        UNION ALL
		        ($tcomplimentary)
		        UNION ALL
		        ($treceiving)
		        UNION ALL
		        ($tfrozendough)
		        UNION ALL
		        ($tpcount)
		        UNION ALL
		        ($tsummary)
		        UNION ALL
		        ($tinventoryrecord)
		    ) AS combined_tables
		";


	    $result = mysqli_query($db, $query);
	
	    if (!$result) {
	        die("Query failed: " . mysqli_error($db));
	    }
	
	    if($result->num_rows > 0)
	    {
	        $i = 0;
	        while($ROW = mysqli_fetch_array($result))
	        { 
	        	$i++;
            	$itemname = $ROW['item_name'];
            	$item_id = $ROW['item_id'];;
            	
            	$beginningval = $preview->getTableValue('summary','beginning',$itemname,$branch,$transdate,$shift,$db);
            	
            	if($beginningval == ''){
					$prevCutPcountStatus = $myShifting == 2? $FunctionForms->twoShiftingPostingStatusGetValue($item_id, $shift, $transdate, $branch, $db): $FunctionForms->threeShiftingPostingStatusGetValue($item_id, $shift, $transdate, $branch, $db);   		
            		$beginningval = $prevCutPcountStatus;
            	}
            	
				$fgtsval = $preview->getTableValue('fgts','actual_yield',$itemname,$branch,$transdate,$shift,$db);
				$receivingval = $preview->getTableValue('receiving','quantity',$itemname,$branch,$transdate,$shift,$db);
				$stockinvalue = $fgtsval + $receivingval;
				$frozendoughval = $preview->getTableValue('frozendough','actual_yield',$itemname,$branch,$transdate,$shift,$db);
				
				$transferinval = $preview->getTableValuetransferIn('transfer','quantity',$itemname,$branch,$transdate,$shift,$db);
				
				
				$chargesempname = $preview->getTableValueOnly('charges','employee_name',$itemname,$branch,$transdate,$shift,$db);
				$chargesempidcode = $preview->getTableValueOnly('charges','idcode',$itemname,$branch,$transdate,$shift,$db);
				
				if($functions->checkSummaryPosted($branch,$transdate,$shift,$db) == '1'){
					
					$styleStatusCharges = '';
					$contenteditableStatusCharges = '';
					$styleStatusCharges = '';

				} else if($chargesempname == ''){
					
					$styleStatusCharges = '';
					$contenteditableStatusCharges = '';
					$chargesempname = '<i class="fa fa-pencil" aria-hidden="true"></i>';
					
					$styleStatusCharges = '';
				} else {
					
					$styleStatusCharges = 'background-color:#f7e9d5';
					$contenteditableStatusCharges = 'contenteditable="true"';
					$chargesempname = $chargesempname;
				}
				
				
				$chargesval = $preview->getTableValue('charges','quantity',$itemname,$branch,$transdate,$shift,$db);
				
				
				$transferoutval = $preview->getTableValue('transfer','quantity',$itemname,$branch,$transdate,$shift,$db);
				$boval = $preview->getTableValue('badorder','quantity',$itemname,$branch,$transdate,$shift,$db);
				$damageval = $preview->getTableValue('damage','quantity',$itemname,$branch,$transdate,$shift,$db);
				
				$actualCount = $preview->getTableValue('pcount','actual_count',$itemname,$branch,$transdate,$shift,$db);
		
				$totalval = $beginningval+ $stockinvalue + $transferinval + $frozendoughval;
				
				$tgas = $totalval - $transferoutval - $chargesval - $snacksval - $boval - $damageval - $complimentaryval;
				
				$sold = $tgas == 0 ? 0: $tgas - $actualCount;
				
				if($tgas < 0){ echo "<script>$('#tgas_' + '".$i."').css('color', 'red');</script>"; } else { echo "<script>$('#tgas_' + '".$i."').css('color', '');</script>"; }
				if($sold < 0){ echo "<script>$('#sold_' + '".$i."').css('color', 'red');</script>"; } else { echo "<script>$('#sold_' + '".$i."').css('color', '');</script>"; }		
				
				if($actualCount == '' || $actualCount == 0){ echo "<script>$('#rowstyle' + '".$i."').css('background-color', '#d7e7f7');</script>"; } else { echo "<script>$('#rowstyle' + '".$i."').css('background-color', '');</script>"; }	

	        ?>
	        	<tr id="rowstyle<?php echo $i?>" style="background-color:#d7e7f7">
	        		<td style="height: 23px"><?php echo $i?></td>
	        		<td style="height: 23px"><?php echo $itemname?></td>
	        		<td id="beginning_<?php echo $i ?>" style="text-align:center; height: 23px;"><?php echo $beginningval?></td>
	        		<td id="stockin_<?php echo $i ?>" style="text-align:center; height: 23px;"><?php echo $stockinvalue?></td>
	        		<td id="frozendough_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>; height: 23px;" <?php echo $contenteditableStatus?> onkeyup="frozendough('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $frozendoughval?></td>
	        		
	        		<td id="transferinqty_<?php echo $i ?>" style="text-align:center; height: 23px;" onkeyup="transferinqty('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $transferinval?></td>
	        		
	        		<td id="total_<?php echo $i?>" style="text-align:center; height: 23px;"><?php echo $totalval?></td>
	        		<td id="transferoutqty_<?php echo $i?>" style="text-align:center; height: 23px;" ondblclick="viewtransfer('transferout','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $transferoutval?></td>



	        		<td id="charges_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatusCharges?>; height: 23px;" <?php echo $contenteditableStatusCharges?> onkeyup="charges('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $chargesval?></td>
					<td id="chargesemployeename_<?php echo $i ?>" style="text-align:center; height: 23px;" <?php echo $contenteditableStatusCharges?> onClick="<?php echo $chargesfunctionname?>('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $chargesempname?></td>



	        		<td id="badorder_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>; height: 23px;" <?php echo $contenteditableStatus?> onkeyup="badorder('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $boval?></td>

	        		<td id="damage_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>; height: 23px;" <?php echo $contenteditableStatus?> onkeyup="damage('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $damageval?></td>
	        		<td id="tgas_<?php echo $i?>" style="text-align:right; height: 23px;"><?php echo number_format($tgas,2)?></td>
	        		<td id="actualcount_<?php echo $i ?>" style="text-align:center;<?php echo $styleStatus?>; height: 23px;" <?php echo $contenteditableStatus?> onkeyup="actualcount('<?php echo $i?>','<?php echo $itemname?>','<?php echo $item_id?>')"><?php echo $actualCount?></td>
	        		<td id="sold_<?php echo $i?>" style="text-align:right; height: 23px;"><?php echo number_format($sold,2)?></td>
	        	</tr>
				
			<?php
			}
		}
	?>
	</tbody>
</table>	

<script>
function viewtransfer(params,itemname,itemid){

	var title = params.toUpperCase();
	$.post("./apps/view_"+params+".php", { params: params, itemname: itemname, itemid: itemid },
	function(data) {
		$('#additem_title').html(title);
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();
	
}







function addChargesEmployee(params,itemname,itemid){
	
	$.post("./apps/charges_employee_form.php", { params: params, itemname: itemname, itemid: itemid },
	function(data) {
		$('#additem_title').html('ADD CHARGES EMPLOYEE');
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();

}

function actualcount(params,itemname,itemid){
	
	var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#actualcount_'+params).text();
    var mode = 'saveactualcount_new';
    
    rms_reloaderOn('Loading...');
    $.post("./actions/actions.php", {
    	mode: mode,
        itemname: itemname,
        qty: qty,
        itemid: itemid,
        branch: branch,
        transdate: transdate,
        shift: shift
    }, function(data) {
        console.log(data);
        calculatePage(params);
        rms_reloaderOff();
    }).fail(function(error) {
        console.log(error);
        rms_reloaderOff();
    });	
}

function damage(params,itemname,itemid){
	
	var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#damage_'+params).text();
    var mode = 'savedamage_new';
           
    $.post("./actions/actions.php", {
    	mode: mode,
        itemname: itemname,
        qty: qty,
        itemid: itemid,
        branch: branch,
        transdate: transdate,
        shift: shift
    }, function(data) {
        console.log(data);
        calculatePage(params);
    }).fail(function(error) {
        console.log(error);
    });	
}

function badorder(params,itemname,itemid){
	
	var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#badorder_'+params).text();
    var mode = 'savebadorder_new';
           
    $.post("./actions/actions.php", {
    	mode: mode,
        itemname: itemname,
        qty: qty,
        itemid: itemid,
        branch: branch,
        transdate: transdate,
        shift: shift
    }, function(data) {
        console.log(data);
        calculatePage(params);
    }).fail(function(error) {
        console.log(error);
    });	
}

function charges(params,itemname,itemid){
	
	var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#charges_'+params).text();
    var mode = 'savecharges_new';
           
    $.post("./actions/actions.php", {
    	mode: mode,
        itemname: itemname,
        qty: qty,
        itemid: itemid,
        branch: branch,
        transdate: transdate,
        shift: shift
    }, function(data) {
        console.log(data);
        calculatePage(params);
    }).fail(function(error) {
        console.log(error);
    });	
}

function transferinqty(params,itemname,itemid){
	
	var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#transferinqty_'+params).text();
	var tobranch = $('#transferinqty_'+params).text();
    var mode = 'savetransferin_new';
        
    $.post("./actions/actions.php", {
    	mode: mode,
        itemname: itemname,
        qty: qty,
        itemid: itemid,
        branch: branch,
        transdate: transdate,
        shift: shift
    },
    function(data) {
		console.log(data);
		calculatePage(params);
    }).fail(function(error) {
        console.log(error);
    });	
}
function frozendough(params,itemname,itemid){
	
	var branch = '<?php echo $branch; ?>';
    var transdate = '<?php echo $transdate; ?>';
    var shift = '<?php echo $shift; ?>';
    var qty = $('#frozendough_'+params).text();
    var mode = 'savefrozendough_new';
     
    $.post("./actions/actions.php", {
    	mode: mode,
        itemname: itemname,
        qty: qty,
        itemid: itemid,
        branch: branch,
        transdate: transdate,
        shift: shift
    },
    function(data) {
        console.log(data);
        calculatePage(params);
    }).fail(function(error) {
        console.log(error);
    });	
}
function calculatePage(params){
	
	var beginning = parseFloat($('#beginning_'+params).text());
	var stockin = parseFloat($('#stockin_'+params).text());
	var frozendough = parseFloat($('#frozendough_'+params).text());
	var transferinqty = parseFloat($('#transferinqty_'+params).text());
	var transferoutqty = parseFloat($('#transferoutqty_'+params).text());
	var charges = parseFloat($('#charges_'+params).text());
	var badorder = parseFloat($('#badorder_'+params).text());
	var damage = parseFloat($('#damage_'+params).text());
	var actualcount = parseFloat($('#actualcount_'+params).text());
	var rowstyle = 1;

	if(isNaN(beginning)) { beginning = 0; }
	if(isNaN(stockin)) { stockin = 0; }
	if(isNaN(frozendough)) { frozendough = 0; }
	if(isNaN(transferinqty)) { transferinqty = 0; }
	if(isNaN(transferoutqty)) { transferoutqty = 0; }
	if(isNaN(charges)) { charges = 0; }
	if(isNaN(badorder)) { badorder = 0; }
	if(isNaN(damage)) { damage = 0; }
	if(isNaN(actualcount)) { actualcount = 0; rowstyle = 0; }
	
	var total = beginning + stockin + frozendough + transferinqty;
	var deduction = transferoutqty + charges + badorder + damage;
	var tgas = total - deduction;
	var sold = tgas - actualcount;
	
	$('#total_' + params).text(total.toFixed(2));
	$('#tgas_' + params).text(tgas.toFixed(2));
	$('#sold_' + params).text(sold.toFixed(2));    
	
	if(tgas < 0){ $('#tgas_' + params).css('color', 'red'); } else { $('#tgas_' + params).css('color', ''); }
	if(sold < 0){ $('#sold_' + params).css('color', 'red'); } else { $('#sold_' + params).css('color', ''); }

	if(rowstyle == 0 || actualcount == 0){ $('#rowstyle' + params).css('background-color', '#d7e7f7'); } else { $('#rowstyle' + params).css('background-color', ''); }

}
</script>
