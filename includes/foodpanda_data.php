<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$function = new TheFunctions;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$branch_shift = $functions->GetSession('shift');

$branch_name = $store_branch;
$branch_date = $trans_date;

$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

if(isset($_POST['search']))
{
	$item_name = $_POST['search'];
	$shift = $_SESSION['session_shift'];
	$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch' AND item_name LIKE '%$item_name%'";
} 
else
{
	if(isset($_SESSION['session_shift'])) 
	{
		$shift = $_SESSION['session_shift'];
		$q = "WHERE report_date='$trans_date' AND shift='$shift' AND branch='$store_branch'";
	} else {
		$shift = '';
		$q = "WHERE report_date='$trans_date' AND branch='$store_branch'";
	}
}
?>
<div class="tableFixHead">
<table style="width: 3000px" class="table table-hover table-striped table-bordered">
	<thead>
		<tr>
			<th style="text-align:center">#</th>
			<th>ITEM NAME</th>
			<th>QUANTITY</th>
			<th>UNIT PRICE</th>
			<th>AMOUNT</th>
			<th>STATUS</th>
			<th>ACTIONS</th>
		</tr>
	</thead>
<?php
$query ="SELECT * FROM store_foodpanda_data $q ORDER BY status,id DESC";  
$result = mysqli_query($db, $query);  
if($result->num_rows > 0)
{
	$x=0;
	while($ROWS = mysqli_fetch_array($result))  
	{
		$x++;
		$rowid = $ROWS['id'];
		$item_name = $ROWS['item_name'];

		if($ROWS['item_id'] == '' || $ROWS['item_id'] == NULL || $ROWS['item_id'] == Null || $ROWS['item_id'] == 0)
		{
			$noid_class = 'class="noid"';
			$noid_text = '<i class="fa-solid fa-triangle-exclamation icon-color-orange pull-right" onclick="showError()"></i>';
		} else {
			$noid_class = '';
			$noid_text = '';
		}
		
		if($ROWS['posted'] == 'Posted')
		{
			$status = '<strong>Posted <i class="fa-solid fa-check text-success"></i></strong>';
			$tr_command = '';
		} else {
			$status = 'Open';
			$tr_command = 'ondblclick=editItem("edit","'.$file_name.'","'.$title.'","'.$rowid.'")';
		}
				
?>	
	<tbody>	
		<tr <?php echo $tr_command; ?> <?php echo $noid_class; ?>>
			<td style="text-align:center"><?php echo $x; ?></td>
			<td><?php echo $ROWS['item_name']." ".$noid_text;; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['quantity']; ?></td> <!-- ITEM NAME -->
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['unit_price']; ?></td> <!-- ACTUAL YIELD -->
			<td class="al-right" style=" padding-right:30px !important"><?php echo $ROWS['total']; ?></td> <!-- SHIFT -->
			<td style="text-align:center"><?php echo $status; ?></td>
			<td style="text-align:center; padding:1px !important" class="actions">
				<?php if($status == 'Open') { ?>
				<div>
					<button class="btn btn-success btn-sm" style="width:49%;font-size:11px" onclick="EditUserChecking('edit','<?php echo $file_name; ?>','<?php echo $title; ?>','<?php echo $rowid; ?>')"><i class="fa-duotone fa-pencil"></i></button>
					<button class="btn btn-danger btn-sm" style="width:49%;font-size:11px" onclick="DeleteUserChecking('<?php echo $rowid; ?>','<?php echo $file_name; ?>','<?php echo $item_name; ?>')"><i class="fa-solid fa-trash"></i></button>
				</div>
				<?php }  else {?>
				<div>
					<button class="btn btn-warning btn-sm btn-block" style="font-size:11px;"><i class="fa-solid fa-lock-keyhole pull-left"></i> Locked</button>
				</div>
				<?php } ?>
			</td>
		</tr>
	</tbody>
<?php } } else {?>
	<tbody>
		<tr>
			<td colspan="7" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;Records</td>
		</tr>
	</tbody>
<?php } 
		
	$grrchb = $function->getFoodPandaBreakdown('ROSE CLASSIC HOT BREAD',$branch_name,$branch_date,$branch_shift,$db);
	$grrheb = $function->getFoodPandaBreakdown('ROSE HIGH-END BREADS',$branch_name,$branch_date,$branch_shift,$db);
	$grrbp = $function->getFoodPandaBreakdown('ROSE BINALOT & PASALUBONG',$branch_name,$branch_date,$branch_shift,$db);
	$grrnd = $function->getFoodPandaBreakdown('ROSE NUTRI-DENSE',$branch_name,$branch_date,$branch_shift,$db);
	$grrtl = $function->getFoodPandaBreakdown('ROSE TASTY LOAF',$branch_name,$branch_date,$branch_shift,$db);
	$grrcs = $function->getFoodPandaBreakdown('ROSE CLASSIC SPECIAL',$branch_name,$branch_date,$branch_shift,$db);
	$gratcc = $function->getFoodPandaBreakdown('ALL TIME CAKE (CLASSIC)',$branch_name,$branch_date,$branch_shift,$db);
	$grccf = $function->getFoodPandaBreakdown('CELEBRATION CAKES (FLAGSLIP)',$branch_name,$branch_date,$branch_shift,$db);
	$grpche = $function->getFoodPandaBreakdown('PREMIUM CAKES (HIGH-END)',$branch_name,$branch_date,$branch_shift,$db);
	$grhec = $function->getFoodPandaBreakdown('HIGH-END COFFEE',$branch_name,$branch_date,$branch_shift,$db);
	$grfgfrap = $function->getFoodPandaBreakdown('FGFRAP',$branch_name,$branch_date,$branch_shift,$db);


	$grbreads = $function->getFoodPandaBreakdown('BREADS',$branch_name,$branch_date,$branch_shift,$db);
	$grcakes = $function->getFoodPandaBreakdown('CAKES',$branch_name,$branch_date,$branch_shift,$db);
	$grspecials = $function->getFoodPandaBreakdown('SPECIALS',$branch_name,$branch_date,$branch_shift,$db);
	$grbeverages = $function->getFoodPandaBreakdown('BEVERAGES',$branch_name,$branch_date,$branch_shift,$db);	
	$grbottledwater = $function->getFoodPandaBreakdown('BOTTLED WATER',$branch_name,$branch_date,$branch_shift,$db);
	$gricecream = $function->getFoodPandaBreakdown('ICE CREAM',$branch_name,$branch_date,$branch_shift,$db);
	$grmerchandiseothers = $function->getFoodPandaBreakdown('MERCHANDISE OTHERS',$branch_name,$branch_date,$branch_shift,$db);
	$grcoffee = $function->getFoodPandaBreakdown('COFFEE',$branch_name,$branch_date,$branch_shift,$db);
	$grmilktea = $function->getFoodPandaBreakdown('MILK TEA',$branch_name,$branch_date,$branch_shift,$db);
	$grbreakdowntotal = ($grrchb + $grrheb + $grrbp + $grrnd + $grrtl + $grrcs + $gratcc + $grccf + $grpche + $grhec + $grfgfrap + $grbreads + $grcakes + $grspecials + $grbeverages + $grbottledwater + $gricecream + $grmerchandiseothers + $grcoffee + $grmilktea);

?>
</table>
<div id="bottom" class="sales-breakdown" style="width:100%">	
	<label>SALES BREAKDOWN</label>
	<table style="width: 100%" class="table table-hover table-striped table-bordered">
		<tr>
			<th title="ROSE CLASSIC HOT BREAD">RCHB</th>
			<th title="ROSE HIGH-END BREADS">RHEB</th>
			<th title="ROSE BINALOT & PASALUBONG">RBP</th>
			<th title="ROSE NUTRI-DENSE">RND</th>
			<th title="ROSE TASTY LOAF">RTL</th>
			<th title="ROSE CLASSIC SPECIAL">RCS</th>
			<th title="ALL TIME CAKE (CLASSIC)">ATCC</th>
			<th title="CELEBRATION CAKES (FLAGSLIP)">CCF</th>
			<th title="PREMIUM CAKES (HIGH-END)">PCHE</th>
			<th title="HIGH-END COFFEE">HEC</th>
			<th title="FGFRAP">FGFRAP</th>
			<th title="CAKES">CAK.</th>
			<th title="BEVERAGES">BEV.</th>
			<th title="BOTTLED WATER">BW</th>
			<th title="ICE CREAM">IC</th>
			<th title="BREADS">B</th>
			<th title="COFFEE">C</th>
			<th title="MILK TEA">MT</th>
			<th title="MERCHANDISE OTHERS">MO</th>
			<th title="TOTAL BREAKDOWN">TOTAL</th>
		</tr>
		<tr>
			<td class="al-right pad-right"><?php echo number_format($grrchb,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grrheb,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grrbp,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grrnd,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grrtl,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grrcs,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gratcc,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grccf,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grpche,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grhec,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grfgfrap,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grcakes,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbeverages,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbottledwater,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gricecream,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbreads,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grcoffee,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grmilktea,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grmerchandiseothers,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbreakdowntotal,2); ?></td>
		</tr>
	</table>	
</div>
<div class="results"></div>
</div>
<script>
function deleteItem(rowid,filename,itemname)
{
	app_confirm("Delete Item","Are you sure to delete " + itemname + "?","warning",filename,rowid);
	return false;
}
function deleteItemYes(rowid,filename)
{
	rms_reloaderOn('Deleting Data....');
	var mode = 'deleteitem';
	$.post("./actions/actions.php", { mode: mode, rowid: rowid, filename },
	function(data) {
		$('.results').html(data);
		rms_reloaderOff();
	});
}
</script>
