<?php
require '../init.php';

require '../class/functions_forms.class.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$function = new TheFunctions;
$FunctionForms = new FunctionForms;

$branch_name = $functions->AppBranch();
$branch_date = $functions->GetSession('branchdate');
$branch_shift = $functions->GetSession('shift');
$ulevel = $functions->getSession('userlevel');
$myShifting = $_SESSION['appstore_shifting'];
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
th {
    position: sticky;
    top: 0;
    background-color: #f2f2f2;
  }
#note-container {
    display: none;
    position: absolute;
    background-color: white;
    border: 1px solid black;
    padding: 10px;
}

</style>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    We have a new update on the summary; we have removed the F9 key to delete the summary and the summary button delete.
</div>

<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<tr>
		<th style="width:50px;text-align:center">#</th>
		
		<th>STATUS</th>
		<th>ITEMS</th>
		<th>BEG</th>
		<th>STK. IN</th>
		<th>KL.USED</th>
		<th>F.DOUGH</th>
		<th>TX-IN</th>
		<th>TOTAL</th>
		<th>TX-OUT</th>
		<th>CHARGES</th>
		<th>SNACKS</th>
		<th>B.O.</th>
		<th>DAMAGED</th>
		<th>COMPLI.</th>
		<th>T.G.A.S.</th>
		<th>ACTL. COUNT</th>
		<th>SOLD</th>
		<th>UNIT PRICE</th>
		<th>AMOUNT</th>
	</tr>
<?php
//  branch='$branch_name' AND report_date='$branch_date' AND shift='$branch_shift'
$breadsAmount =0;
$query ="SELECT * FROM store_summary_data $q ORDER BY status,category ASC";  
$result = mysqli_query($db, $query);  
if($result->num_rows > 0)
{
	$i=0;
	$total=0;
	$amounttotal=0;
	$breadsAmount =0;
	$total=0;$transfer_out=0;$should_be=0;$sold=0;$discnt=0;
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
		$transfer_in = $ROW['t_in'];
		$transfer_out = $ROW['t_out'];
		$charges = $ROW['charges'];
		$snacks = $ROW['snacks'];
		$bad_order = $ROW['bo'];
		$damaged = $ROW['damaged'];
		$unit_price = $ROW['unit_price'];
		$complimentary = $ROW['complimentary'];
		$actual_count = $ROW['actual_count'];
		$frozendough = $ROW['frozendough'];	
		$kilo_used = $ROW['kilo_used'];	
		$posted = $ROW['posted'];		
		
		$beginningInitial = $myShifting == 2? $FunctionForms->twoShiftingBegGet($item_id, $branch_shift, $branch_date, $branch_name, $db): $FunctionForms->threeShiftingBegGet($item_id, $branch_shift, $branch_date, $branch_name, $db);
		

		if($ROW['item_id'] == '' || $ROW['item_id'] == NULL || $ROW['item_id'] == Null)
		{
			$noid_class = 'class="noid"';
			$noid_text = '<i class="fa-solid fa-triangle-exclamation icon-color-orange pull-right" onclick="showError()"></i>';
		} else {
			$noid_class = '';
			$noid_text = '';
		}

		$dateback = date( "Y-m-d", strtotime( $summary_date . "-1 day"));
		$total = $ROW['total'];
		$should_be = $ROW['should_be'];
		$sold = $ROW['sold'];
		$amount =  $ROW['amount'];
		$amounttotal += $amount;
		$discnt = $function->GetDiscount($branch_name,$branch_date,$shift,$db);				

		$colorData = '';
		$note = '';
		if($amount < 0){
			$colorData = '#d67673';
			$note = 'The amount is negative';
		}
		if($sold > 0 && $amount == 0){
			$colorData = '#FFA500';
			$note = 'The inventory is depleted, and the amount is zero';
		}
		if($actual_count > $total){
			$colorData = '#FFA500';
			$note = 'The actual count exceeds the total amount';
		}
		if($beginning != $beginningInitial){
			$colorData = '#FFA500';
			$note = "The previous period's ending inventory doesn't match the current period's beginning inventory";		
		}


?>
	<tr <?php echo $noid_class; ?> style="color:<?php echo $colorData?>" class="element" data-note="<?php echo $note?>">		
		<td style="text-align:center;"><?php echo $i; ?></td>
		<!--td class="al-right" style="padding:1px !important"><button class="btn btn-danger btn-sm" onclick="deleteSumItem('<?php echo $rowid; ?>')"><i class="fa-solid fa-trash"></i></button></td-->						
		<td class="al-right" style="text-align:center"><?php echo $posted; ?></td>						
		<td style="text-align:left;white-space:nowrap"><?php echo $item_name." ".$noid_text; ?></td>
		<td class="al-right"><?php echo $beginning; ?></td>						
		<td class="al-right"><?php echo $stock; ?></td>
		<td class="al-right"><?php echo $kilo_used; ?></td>
		<td class="al-right"><?php echo $frozendough; ?></td>
		<td class="al-right"><?php echo $transfer_in; ?></td>
		<td class="al-right"><?php echo $total; ?></td>
		<td class="al-right"><?php echo $transfer_out; ?></td>
		<td class="al-right"><?php echo $charges; ?></td>
		<td class="al-right"><?php echo $snacks; ?></td>
		<td class="al-right"><?php echo $bad_order; ?></td>
		<td class="al-right"><?php echo $damaged; ?></td>
		<td class="al-right"><?php echo $complimentary; ?></td>
		<td class="al-right"><?php echo $should_be; ?></td>
		<td class="al-right"><?php echo $actual_count; ?></td>
		<td class="al-right"><?php echo $sold; ?></td>
		<td class="al-right"><?php echo $unit_price; ?></td>
		<td style="text-align:right"><?php echo number_format($amount,2)?></td>
	</tr>
<?php } 
	$breads = $function->getSumsummaryBreakdown('BREADS',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('BREADS',$branch_name,$branch_date,$shift,$db);
	$cakes = $function->getSumsummaryBreakdown('CAKES',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('CAKES',$branch_name,$branch_date,$shift,$db);
	$specials = $function->getSumsummaryBreakdown('SPECIALS',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('SPECIALS',$branch_name,$branch_date,$shift,$db);
	$beverages = $function->getSumsummaryBreakdown('BEVERAGES',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('BEVERAGES',$branch_name,$branch_date,$shift,$db);	
	$bottledwater = $function->getSumsummaryBreakdown('BOTTLED WATER',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('BOTTLED WATER',$branch_name,$branch_date,$shift,$db);
	$icecream = $function->getSumsummaryBreakdown('ICE CREAM',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('ICE CREAM',$branch_name,$branch_date,$shift,$db);
	$merchandiseothers = $function->getSumsummaryBreakdown('MERCHANDISE OTHERS',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('MERCHANDISE OTHERS',$branch_name,$branch_date,$shift,$db);
	$coffee = $function->getSumsummaryBreakdown('COFFEE',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('COFFEE',$branch_name,$branch_date,$shift,$db);
	$milktea = $function->getSumsummaryBreakdown('MILK TEA',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('MILK TEA',$branch_name,$branch_date,$shift,$db);
	$breakdowntotal = ($breads + $cakes + $specials + $beverages + $bottledwater + $icecream + $merchandiseothers + $coffee + $milktea);
	
	$gbreads = $function->getGcashBreakdown('BREADS',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('BREADS',$branch_name,$branch_date,$shift,$db);
	$gcakes = $function->getGcashBreakdown('CAKES',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('CAKES',$branch_name,$branch_date,$shift,$db);
	$gspecials = $function->getGcashBreakdown('SPECIALS',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('SPECIALS',$branch_name,$branch_date,$shift,$db);
	$gbeverages = $function->getGcashBreakdown('BEVERAGES',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('BEVERAGES',$branch_name,$branch_date,$shift,$db);	
	$gbottledwater = $function->getGcashBreakdown('BOTTLED WATER',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('BOTTLED WATER',$branch_name,$branch_date,$shift,$db);
	$gicecream = $function->getGcashBreakdown('ICE CREAM',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('ICE CREAM',$branch_name,$branch_date,$shift,$db);
	$gmerchandiseothers = $function->getGcashBreakdown('MERCHANDISE OTHERS',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('MERCHANDISE OTHERS',$branch_name,$branch_date,$shift,$db);
	$gcoffee = $function->getGcashBreakdown('COFFEE',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('COFFEE',$branch_name,$branch_date,$shift,$db);
	$gmilktea = $function->getGcashBreakdown('MILK TEA',$branch_name,$branch_date,$branch_shift,$db) - $function->GetDiscountTypeCategorBadge('MILK TEA',$branch_name,$branch_date,$shift,$db);
	$gbreakdowntotal = ($breads + $cakes + $specials + $beverages + $bottledwater + $icecream + $merchandiseothers + $coffee + $milktea);
?>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">SALES SUMMARY</td>
		<td style="text-align:right; color:<?php echo ($amounttotal<0)?'#d67673':''?>"><?php echo number_format($amounttotal,2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">
			TOTAL DISCOUNT <?php if($function->GetDiscount($branch_name,$branch_date,$shift,$db) == 0) { echo '<span class="dc-notifs">Discount not posted</span>'; } ?>
		</td>
		<td style="text-align:right"><?php echo number_format($function->GetDiscount($branch_name,$branch_date,$shift,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">GRAND TOTAL</td>
		<?php $grand_total = ($amounttotal - $function->GetDiscount($branch_name,$branch_date,$shift,$db));?>
		<td style="text-align:right; color:<?php echo ($grand_total<0)?'#d67673':''?>"><?php echo number_format($grand_total,2); ?></td>
	</tr>	
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">TOTAL CASH COUNT</td>
		<td style="text-align:right"><?php echo number_format($function->getCashCountTotal($branch_name,$branch_date,$branch_shift,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">TOTAL GCASH SALES</td>
		<td style="text-align:right"><?php echo number_format($function->getGcashTotal($branch_name,$branch_date,$branch_shift,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">TOTAL GRAB SALES</td>
		<td style="text-align:right"><?php echo number_format($function->getGrabTotal($branch_name,$branch_date,$branch_shift,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">TOTAL CASH/GCASH/GRAB SALES</td>
		<td style="text-align:right"><?php echo number_format($function->getCashCountTotal($branch_name,$branch_date,$branch_shift,$db)+$function->getGcashTotal($branch_name,$branch_date,$branch_shift,$db)+$function->getGrabTotal($branch_name,$branch_date,$branch_shift,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">CASH VARIANCE</td>
		<?php $variance = ($function->getCashCountTotal($branch_name,$branch_date,$branch_shift,$db) + $function->getGcashTotal($branch_name,$branch_date,$branch_shift,$db) + $function->getGrabTotal($branch_name,$branch_date,$branch_shift,$db)) - $grand_total; ?>
		<td style="text-align:right; color:<?php echo ($variance<0)?'#d67673':''?>"><?php echo number_format($variance,2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="19" style="text-align:left;font-weight:bold">PAKATI</td>
		<?php $pakati = $function->getPakatiTotal($branch_name,$branch_date,$branch_shift,$db); ?>
		<td style="text-align:right; color:<?php echo ($pakati<0)?'#d67673':''?>"><?php echo number_format($pakati,2)?></td>
	</tr>

<?php } else { 
	$breads = 0;
	$cakes = 0;
	$specials = 0;
	$beverages = 0;	
	$bottledwater = 0;
	$icecream = 0;
	$merchandiseothers = 0;
	$coffee = 0;
	$milktea = 0;
	$breakdowntotal = 0;
?>	
	<tr>
		<td colspan="20" style="text-align:center;font-size:16px;"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp;Records</td>
	</tr>
<?php } ?>	
</table>
<div id="note-container"></div>
<div id="bottom" class="sales-breakdown" style="width:100%">	
	<label>SALES BREAKDOWN</label>
	<table style="width: 100%" class="table table-hover table-striped table-bordered">
		<tr>
			<td colspan="10">LESS:</td>
		</tr>
		<tr>
			<td colspan="9">SENIOR DISCOUNT -<i style="opacity: 0.5; position:"> Breads, Specials & Cakes</i></td>
			<td style="text-align:right"><?php echo $function->GetDiscountType('SENIOR DISCOUNT',$branch_name,$branch_date,$branch_shift,$db)?></td>
		</tr>
		<tr>
			<td colspan="9">ICE CREAM DISCOUNT -<i style="opacity: 0.5; position:"> Ice Cream</i></td>
			<td style="text-align:right"><?php echo $function->GetDiscountType('ICE CREAM DISCOUNT',$branch_name,$branch_date,$branch_shift,$db)?></td>
		</tr>
		<tr>
			<td colspan="9">CAKE DISCOUNT -<i style="opacity: 0.5; position:"> Cake</i></td>
			<td style="text-align:right"><?php echo $function->GetDiscountType('CAKE DISCOUNT',$branch_name,$branch_date,$branch_shift,$db)?></td>
		</tr>
		<tr>
			<th>BREADS<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('BREADS',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>CAKES<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('CAKES',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>SPECIALS<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('SPECIALS',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>BEVERAGES<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('BEVERAGES',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>BOTTLED WATER<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('BOTTLED WATER',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>ICE CREAM<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('ICE CREAM',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>MERCHANDISE OTHERS<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('MERCHANDISE OTHERS',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>COFFEE<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('COFFEE',$branch_name,$branch_date,$shift,$db)?></span></th>
			<th>MILK TEA<span class="label label-danger"><?php echo $function->GetDiscountTypeCategorBadge('MILK TEA',$branch_name,$branch_date,$shift,$db)?></span></th>			
			<th>TOTAL BREAKDOWN</th>
		</tr>
		<tr>
			<td class="al-right pad-right" style="color:<?php echo ($breads<0)?'#d67673':''?>"><?php echo number_format($breads,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($cakes<0)?'#d67673':''?>"><?php echo number_format($cakes,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($specials<0)?'#d67673':''?>"><?php echo number_format($specials,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($beverages<0)?'#d67673':''?>"><?php echo number_format($beverages,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($bottledwater<0)?'#d67673':''?>"><?php echo number_format($bottledwater,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($icecream<0)?'#d67673':''?>"><?php echo number_format($icecream,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($merchandiseothers<0)?'#d67673':''?>"><?php echo number_format($merchandiseothers,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($coffee<0)?'#d67673':''?>"><?php echo number_format($coffee,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($milktea<0)?'#d67673':''?>"><?php echo number_format($milktea,2); ?></td>
			<td class="al-right pad-right" style="color:<?php echo ($breakdowntotal<0)?'#d67673':''?>"><?php echo number_format($breakdowntotal,2); ?></td>
		</tr>
		<tr>
			<th colspan="10">GCASH SALES</th>
		</tr>
		<tr>
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
		</tr>
<?php
	$gbreads = $function->getGcashBreakdown('BREADS',$branch_name,$branch_date,$branch_shift,$db);
	$gcakes = $function->getGcashBreakdown('CAKES',$branch_name,$branch_date,$branch_shift,$db);
	$gspecials = $function->getGcashBreakdown('SPECIALS',$branch_name,$branch_date,$branch_shift,$db);
	$gbeverages = $function->getGcashBreakdown('BEVERAGES',$branch_name,$branch_date,$branch_shift,$db);	
	$gbottledwater = $function->getGcashBreakdown('BOTTLED WATER',$branch_name,$branch_date,$branch_shift,$db);
	$gicecream = $function->getGcashBreakdown('ICE CREAM',$branch_name,$branch_date,$branch_shift,$db);
	$gmerchandiseothers = $function->getGcashBreakdown('MERCHANDISE OTHERS',$branch_name,$branch_date,$branch_shift,$db);
	$gcoffee = $function->getGcashBreakdown('COFFEE',$branch_name,$branch_date,$branch_shift,$db);
	$gmilktea = $function->getGcashBreakdown('MILK TEA',$branch_name,$branch_date,$branch_shift,$db);
	$gbreakdowntotal = ($gbreads + $gcakes + $gspecials + $gbeverages + $gbottledwater + $gicecream + $gmerchandiseothers + $gcoffee + $gmilktea);
?>
		<tr>
			<td class="al-right pad-right"><?php echo number_format($gbreads,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gcakes,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gspecials,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbeverages,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbottledwater,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gicecream,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gmerchandiseothers,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gcoffee,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gmilktea,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gbreakdowntotal,2); ?></td>
		</tr>
		<tr>
			<th colspan="10"><i class="fa fa-motorcycle text-primary" aria-hidden="true"></i>&nbsp;&nbsp;GRAB SALES</th>
		</tr>
		<tr>
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
		</tr>
<?php
	$grbreads = $function->getGrabBreakdown('BREADS',$branch_name,$branch_date,$branch_shift,$db);
	$grcakes = $function->getGrabBreakdown('CAKES',$branch_name,$branch_date,$branch_shift,$db);
	$grspecials = $function->getGrabBreakdown('SPECIALS',$branch_name,$branch_date,$branch_shift,$db);
	$grbeverages = $function->getGrabBreakdown('BEVERAGES',$branch_name,$branch_date,$branch_shift,$db);	
	$grbottledwater = $function->getGrabBreakdown('BOTTLED WATER',$branch_name,$branch_date,$branch_shift,$db);
	$gricecream = $function->getGrabBreakdown('ICE CREAM',$branch_name,$branch_date,$branch_shift,$db);
	$grmerchandiseothers = $function->getGrabBreakdown('MERCHANDISE OTHERS',$branch_name,$branch_date,$branch_shift,$db);
	$grcoffee = $function->getGrabBreakdown('COFFEE',$branch_name,$branch_date,$branch_shift,$db);
	$grmilktea = $function->getGrabBreakdown('MILK TEA',$branch_name,$branch_date,$branch_shift,$db);
	$grbreakdowntotal = ($grbreads + $grcakes + $grspecials + $grbeverages + $grbottledwater + $gricecream + $grmerchandiseothers + $grcoffee + $grmilktea);
?>
		<tr>
			<td class="al-right pad-right"><?php echo number_format($grbreads,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grcakes,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grspecials,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbeverages,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbottledwater,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($gricecream,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grmerchandiseothers,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grcoffee,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grmilktea,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($grbreakdowntotal,2); ?></td>
		</tr>
		<tr>
			<td colspan="9" style="border-top:5px; font-weight:bold">TOTAL</td>
			<td class="al-right pad-right"><?php echo  number_format(($breakdowntotal+$gbreakdowntotal+$grbreakdowntotal),2)?></td>
			
		</tr>
	</table>	
	
</div>
<div id="sumdata"></div>
<script>
$(document).ready(function() {
    function showNote() {
        var note = $(this).data('note');
        if(note==''){
        
        }
        else
        {
	        $('#note-container').text(note).css({
	            top: event.clientY + 10,
	            left: event.clientX + 10
	        }).show();
		}
    }
    
    function hideNote() {
        $('#note-container').hide();
    }
    
    $('tr.element').hover(showNote, hideNote);
});

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
	var mode = 'deletesumitem';
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