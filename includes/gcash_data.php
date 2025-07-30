<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$function = new TheFunctions;
$trans_date = $functions->GetSession('branchdate');
$store_branch = $functions->AppBranch();
$branch_shift = $functions->GetSession('shift');

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
$query ="SELECT * FROM store_gcash_data $q ORDER BY status,id DESC";  
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
			<td><?php echo $ROWS['item_name']." ".$noid_text;; ?></td>
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['quantity']; ?></td>
			<td style="text-align:right; padding-right:30px !important"><?php echo $ROWS['unit_price']; ?></td>
			<td class="al-right" style=" padding-right:30px !important"><?php echo $ROWS['total']; ?></td>
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

<?php } 
		
	$grchb = $function->getGcashBreakdown('ROSE CLASSIC HOT BREAD',$store_branch,$trans_date,$branch_shift,$db);
	$grheb = $function->getGcashBreakdown('ROSE HIGH-END BREADS',$store_branch,$trans_date,$branch_shift,$db);
	$grbp = $function->getGcashBreakdown('ROSE BINALOT & PASALUBONG',$store_branch,$trans_date,$branch_shift,$db);
	$grnd = $function->getGcashBreakdown('ROSE NUTRI-DENSE',$store_branch,$trans_date,$branch_shift,$db);
	$grtl = $function->getGcashBreakdown('ROSE TASTY LOAF',$store_branch,$trans_date,$branch_shift,$db);
	$grcs = $function->getGcashBreakdown('ROSE CLASSIC SPECIAL',$store_branch,$trans_date,$branch_shift,$db);
	$gatcc = $function->getGcashBreakdown('ALL TIME CAKE (CLASSIC)',$store_branch,$trans_date,$branch_shift,$db);
	$gccf = $function->getGcashBreakdown('CELEBRATION CAKES (FLAGSLIP)',$store_branch,$trans_date,$branch_shift,$db);
	$gpche = $function->getGcashBreakdown('PREMIUM CAKES (HIGH-END)',$store_branch,$trans_date,$branch_shift,$db);
	$ghec = $function->getGcashBreakdown('HIGH-END COFFEE',$store_branch,$trans_date,$branch_shift,$db);
	$gfgfrap = $function->getGcashBreakdown('FGFRAP',$store_branch,$trans_date,$branch_shift,$db);



	$gbreads = $function->getGcashBreakdown('BREADS',$store_branch,$trans_date,$branch_shift,$db);
	$gcakes = $function->getGcashBreakdown('CAKES',$store_branch,$trans_date,$branch_shift,$db);
	$gspecials = $function->getGcashBreakdown('SPECIALS',$store_branch,$trans_date,$branch_shift,$db);
	$gbeverages = $function->getGcashBreakdown('BEVERAGES',$store_branch,$trans_date,$branch_shift,$db);	
	$gbottledwater = $function->getGcashBreakdown('BOTTLED WATER',$store_branch,$trans_date,$branch_shift,$db);
	$gicecream = $function->getGcashBreakdown('ICE CREAM',$store_branch,$trans_date,$branch_shift,$db);
	$gmerchandiseothers = $function->getGcashBreakdown('MERCHANDISE OTHERS',$store_branch,$trans_date,$branch_shift,$db);
	$gcoffee = $function->getGcashBreakdown('COFFEE',$store_branch,$trans_date,$branch_shift,$db);
	$gmilktea = $function->getGcashBreakdown('MILK TEA',$store_branch,$trans_date,$branch_shift,$db);
	$gbreakdowntotal = ($grchb + $grheb + $grbp + $grnd + $grtl + $grcs + $gatcc + $gccf + $gpche +	$ghec +	$gfgfrap + $gbreads + $gcakes + $gspecials + $gbeverages + $gbottledwater + $gicecream + $gmerchandiseothers + $gcoffee + $gmilktea);

?>
</table>
<div id="bottom" class="sales-breakdown" style="width:100%">	
	<label>SALES BREAKDOWN</label>
	<table style="width: 100%" class="table table-hover table-striped table-bordered">
		<!--tr>
			<th>BREADS</th>
			<th>CAKES</th>
			<th>SPECIALS</th>
			<th>BEVERAGES</th>
			<th>BOTTLED WATER</th>
			<th>ICE CREAM</th>
			<th>MERCHANDISE OTHERS</th>
			<th>COFFEE</th>
			<th>MILK TEA</th>			
			<th>TOTAL BREAKDOWN</th>
		</tr-->
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
			<td class="al-right pad-right"><?php echo number_format($grchb,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grheb,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbp,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grnd,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grtl,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grcs,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gatcc,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gccf,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gpche,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($ghec,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gfgfrap,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gcakes,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbeverages,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbottledwater,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gicecream,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbreads,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gcoffee,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gmilktea,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gmerchandiseothers,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbreakdowntotal,2); ?></td>
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
	//	$('#' + sessionStorage.navcount).trigger('click');
		rms_reloaderOff();
	});
}
</script>
