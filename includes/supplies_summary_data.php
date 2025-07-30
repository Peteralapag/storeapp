<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$function = new TheFunctions;

$branch_name = $functions->AppBranch();
$branch_date = $functions->GetSession('branchdate');
$branch_shift = $functions->GetSession('shift');
$ulevel = $functions->getSession('userlevel');
if(isset($_POST['search']))
{
	$item_name = $_POST['search'];
	$q = "WHERE report_date='$branch_date' AND shift='$branch_shift' AND branch='$branch_name' AND item_name LIKE '%$item_name%'";
} 
else
{
	if(isset($_SESSION['session_shift'])) 
	{
		$shift = $_SESSION['session_shift'];
		$q = "WHERE report_date='$branch_date' AND shift='$shift' AND branch='$branch_name'";
	} else {
		$shift = '';
		$q = "WHERE report_date='$branch_date' AND branch='$branch_name'";
	}
}
?>
<style>
#tgas,#beg,#fgtsin,#tin,#tout,#del { position:relative; }
.title-box1,.title-box2,.title-box3,.title-box4,.title-box5,.title-box6  {
	position:absolute;display:none;font-size:12px;padding:5px 10px 5px 10px;
	border:#aeaeae;background:#f9f5e8;color:#232323;border-radius:5px; 
	border:1px solid orange;z-index:999;
}
.dc-notifs {
	padding:2px 10px 2px 10px;
	border:1px solid orange;
	background:#f9f5e8;
	font-weight:normal;
	font-style:italic;
	font-size:12px;
	margin-left:100px;
	border-radius:5px
}
</style>
<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<tr>
		<th style="width:50px;text-align:center">#</th>
		<th id="del">DEL
			<div class="title-box6">
				Delete Summary Item
			</div>
		</th>
		<th>STATUS</th>
		<th>ITEMS</th>
		<th id="beg">BEG
			<div class="title-box2">
				Stock Begining
			</div>
		</th>
		<th id="fgtsin">IN
			<div class="title-box3">
				FGTS Stock In
			</div>
		</th>
		<th id="tin">T-IN
			<div class="title-box4">
				Transfer IN
			</div>
		</th>
		<th>TOTAL</th>
		<th id="tout">T-OUT
			<div class="title-box5">
				Transfer OUT
			</div>
		</th>
		<th>B.O.</th>
		<th>TOTAL</th>
		<th>ACTUAL COUNT</th>
		<th>DIFFERENCE</th>
		<th>PRICE / KILO</th>
		<th>AMOUNT</th>
	</tr>
<?php
$amounttotal=0;
$query ="SELECT * FROM store_supplies_summary_data $q ORDER BY status,id DESC";  
$result = mysqli_query($db, $query);  
if($result->num_rows > 0)
{
	$i=0;
	$total=0;
	$amounttotal=0;
	$breadsAmount =0;
	$total=0;$transfer_out=0;$should_be=0;$sold;	
	while($ROW = mysqli_fetch_array($result))  
	{
		$total=0;
		$breadsAmount =0;
		$total=0;$transfer_out=0;$should_be=0;$sold;
		$i++;
		$rowid = $ROW['id'];
		$shift = $ROW['shift'];
		$store_branch = $ROW['branch'];
		$summary_date = $ROW['report_date'];
		$time_covered =  $ROW['time_covered'];			
		$beginning = $ROW['beginning'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$stock = $ROW['stock_in'];
		$sub_total = $ROW['sub_total'];
		$transfer_in = $ROW['transfer_in'];
		$transfer_out = $ROW['transfer_out'];
		$bad_order = $ROW['bo'];
		$grand_total = $ROW['total'];
		$difference = $ROW['difference'];
		$price_kg = $ROW['price_kg'];
		$actual_count = $ROW['actual_count'];
		$status = $ROW['status'];
		$total_amount = $ROW['amount'];
		
		$dateback = date( "Y-m-d", strtotime( $summary_date . "-1 day"));

?>
	<tr>		
		<td style="text-align:center;"><?php echo $i; ?></td>
		<td style="padding:1px !important;text-align:center">
			<button class="btn btn-danger btn-sm" onclick="deleteSumItem('<?php echo $rowid; ?>')"><i class="fa-solid fa-trash"></i></button>
		</td>						
		<td class="al-right" style="text-align:center"><?php echo $status; ?></td>						
		<td style="text-align:left;white-space:nowrap"><?php echo $item_name; ?></td>
		<td class="al-right"><?php echo $beginning; ?></td>						
		<td class="al-right"><?php echo $stock; ?></td>
		<td class="al-right"><?php echo $transfer_in; ?></td>
		<td class="al-right"><?php echo $sub_total; ?></td>
		<td class="al-right"><?php echo $transfer_out; ?></td>
		<td class="al-right"><?php echo $bad_order; ?></td>
		<td class="al-right"><?php echo $grand_total; ?></td>
		<td class="al-right"><?php echo $actual_count; ?></td>
		<td class="al-right"><?php echo $difference; ?></td>
		<td class="al-right"><?php echo $price_kg; ?></td>
		<td style="text-align:right"><?php echo number_format($total_amount,2)?></td>
	</tr>
<?php 
	$amounttotal += $total_amount;
} } else { ?>	
<?php } ?>	
	<tr>		
		<td style="text-align:center;">&nbsp;</td>		
		<td colspan="13" style="text-align:center;padding-right:30px;" ><strong>TOTAL</strong></td>
		<td style="text-align:right;border-top:3px solid #232323"><?php echo number_format($amounttotal,2); ?></td>
	</tr>
</table>
<div id="sumdata">
<div id="bottom" class="sales-breakdown" style="width:100%">	
</div>
</div>
<script>

function deleteSumItem(rowid)
{
	var userlevel = '<?php echo $ulevel; ?>';
	if(userlevel == 50 || userlevel >= 80)
	{
		app_confirm("Delete Summary Item","Are you sure to delete this Item?","warning","deletesumitemyes",rowid,"true");
		return false;
	} else {
		swal("Action Denied", "Only supervisors can delete items in summary data", "warning");
		return false;
	}
}
function deleteSumItemYes(rowid)
{
	var mode = 'deletesuppliessumitem';
	$.post("../actions/actions.php", { mode: mode, rowid: rowid },
	function(data) {
		$('#sumdata').html(data);
	});
}
$(function()
{
	var uw = $('#upper').width();
	$('#bottom').width(uw);
	$('#tgas').hover(function() { $('.title-box1').show(); });
	$("#tgas" ).mouseout(function() { $('.title-box1').hide(); });
	$('#beg').hover(function() { $('.title-box2').show(); });	
	$("#beg" ).mouseout(function() { $('.title-box2').hide(); });
	$('#fgtsin').hover(function() { $('.title-box3').show(); });	
	$("#fgtsin" ).mouseout(function() { $('.title-box3').hide(); });
	$('#tin').hover(function() { $('.title-box4').show(); });	
	$("#tin" ).mouseout(function() { $('.title-box4').hide(); });
	$('#tout').hover(function() { $('.title-box5').show(); });	
	$("#tout" ).mouseout(function() { $('.title-box5').hide(); });
	$('#del').hover(function() { $('.title-box6').show(); });	
	$("#del" ).mouseout(function() { $('.title-box6').hide(); });	

	$('#contentdata').resize(function()
	{
		var uw = $('#contentdata').width();
		console.log(uw);
		$('#bottom').width(uw);
	});
});
</script>