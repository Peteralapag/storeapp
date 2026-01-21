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
	$q = "WHERE report_date='$branch_date' AND branch='$branch_name' AND item_name LIKE '%$item_name%'";
} 
else
{
	if(isset($_SESSION['session_shift'])) 
	{
		$shift = $_SESSION['session_shift'];
		$q = "WHERE report_date='$branch_date' AND branch='$branch_name'";
	} else {
		$shift = '';
		$q = "WHERE report_date='$branch_date' AND branch='$branch_name'";
	}
}
?>

<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<tr>
		<th colspan="20">Whole Day Transaction</th>
	</tr>
	<tr>
		<th style="width:50px;text-align:center">#</th>
		<th>ITEM ID</th>
		<th>ITEMS</th>
		<th id="beg">BEG
			<div class="title-box2">
				Stock Begining
			</div>
		</th>
		<th id="fgtsin">DLVRY</th>
		<th id="tin">T-IN</th>
		<th>C-IN</th>
		<th>TOTAL</th>
		<th id="tout">T-OUT</th>
		
		<th>B.O.</th>
		<th>ACTL USAGE</th>
		<th>TOTAL</th>
		<th>ACTL COUNT</th>
		<th>DIFFERENCE</th>
		<th>PRICE / KILO</th>
		<th>AMOUNT</th>
	</tr>
<?php
$amounttotal=0;
$query = "
	SELECT 
	    MIN(id) AS id, -- representative ID
	    MAX(branch) AS branch,
	    MAX(report_date) AS report_date,
	    item_id,
	    MAX(item_name) AS item_name,
	    MAX(category) AS category,
	    
	    MAX(CASE WHEN shift = 'FIRST SHIFT' THEN beginning END) AS beginning,
	    
	    SUM(stock_in) AS stock_in,
	    SUM(transfer_in) AS transfer_in,
	    SUM(counter_out) AS counter_out,
	    SUM(sub_total) AS sub_total,
	    SUM(transfer_out) AS transfer_out,
	    SUM(bo) AS bo,
	    SUM(total) AS grand_total,
	    SUM(actual_usage) AS actual_usage,
	    
	    MAX(CASE WHEN shift = 'SECOND SHIFT' THEN actual_count END) AS actual_count,
	    
	    SUM(difference) AS difference,
	    MAX(price_kg) AS price_kg, 
	    SUM(amount) AS total_amount
	
	FROM store_rm_summary_data
	$q
	GROUP BY item_id
	ORDER BY MIN(id) DESC;  
";
  
		
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
		
		$store_branch = $ROW['branch'];
		$summary_date = $ROW['report_date'];
			
		$beginning = $ROW['beginning'];
		$category = $ROW['category'];
		$item_id = $ROW['item_id'];
		$item_name = $ROW['item_name'];
		$stock = $ROW['stock_in'];
		$sub_total = $ROW['sub_total'];
		$transfer_in = $ROW['transfer_in'];
		$transfer_out = $ROW['transfer_out'];
		$cout = $ROW['counter_out'];
		$bad_order = $ROW['bo'];
		
		
		$price_kg = $ROW['price_kg'];
		$actual_count = $ROW['actual_count'];
		$total_amount = $ROW['total_amount'];
		
		$actualusage = $ROW['actual_usage'];
		
		
		
		$sub_total = $beginning + $stock + $transfer_in + $cout;
		
		$grand_total = $sub_total - $transfer_out - $bad_order - $actualusage;
		
		
		
		
		$difference = $actual_count - $grand_total;
		
		$dateback = date( "Y-m-d", strtotime( $summary_date . "-1 day"));

?>
	<tr>		
		<td style="text-align:center;"><?php echo $i; ?></td>
						
		<td class="al-right" style="text-align:center"><?php echo $item_id; ?></td>
		<td style="text-align:left;white-space:nowrap"><?php echo $item_name; ?></td>
		<td class="al-right"><?php echo $beginning; ?></td>						
		<td class="al-right"><?php echo $stock; ?></td>
		<td class="al-right"><?php echo $transfer_in; ?></td>
		<td class="al-right"><?php echo $cout; ?></td>
		<td class="al-right"><?php echo $sub_total; ?></td>
		<td class="al-right"><?php echo $transfer_out; ?></td>
		<td class="al-right"><?php echo $bad_order; ?></td>
		<td class="al-right"><?php echo $actualusage; ?></td>
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
		<td colspan="14" style="text-align:center;padding-right:30px;" ><strong>TOTAL</strong></td>
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
	var mode = 'deletermsumitem';
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