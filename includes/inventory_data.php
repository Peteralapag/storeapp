<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$selectedbranch = $_SESSION['appstore_branch'];
$defaultDate = $_SESSION['session_date'];
$TheFunctions = new TheFunctions;

if(isset($_POST['dateselectfrom']) && isset($_POST['dateselectto']))
{
	$dateselectfrom = $_POST['dateselectfrom'];
	$dateselectto = $_POST['dateselectto'];
	$q = "WHERE branch='$selectedbranch' AND report_date BETWEEN '$dateselectfrom' AND '$dateselectto'";
	if(isset($_POST['search'])){
		$search = $_POST['search'];
		$q .= " AND item_name LIKE '%$search%'";
	}
}
else
{
	$dateselectfrom = $defaultDate;
	$dateselectto = $defaultDate;
	$q = "WHERE branch='$selectedbranch' AND report_date='$defaultDate'";
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
<?php 
?>
<table id="upper" style="width: 100%" class="table table-hover table-striped table-bordered">
	<tr>
		<th style="width:50px;text-align:center">#</th>
		<th>ITEMS</th>
		<th>BEG</th>
		<th>STK.IN</th>
		<th>F.DOUGH</th>
		<th>TX-IN</th>
		<th>TX-OUT</th>
		<th>CHARGES</th>
		<th>SNACKS</th>
		<th>B.O</th>
		<th>DAMAGE</th>
		<th>COMPLI.</th>
		<th>ACTL. COUNT</th>
		<th>SOLD</th>
		<th>SRP</th>
		<th>AMOUNT</th>
		
	</tr>
<?php
	$breadsAmount =0; 
	$query ="SELECT * FROM store_summary_data $q GROUP BY item_id"; 	
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
		$item_id = $ROW['item_id'];	
		$item_name = $ROW['item_name'];	
		$category = $ROW['category'];
		
		$beg = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'beginning',$db);
		$stock = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'stock_in',$db);
		$frozendough = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'frozendough',$db);
		$transfer_in = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'t_in',$db);
		$transfer_out = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'t_out',$db);
		
		$charges = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'charges',$db);
		$snacks = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'snacks',$db);
		$bad_order = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'bo',$db);
		$damaged = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'damaged',$db);
		$complimentary = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'complimentary',$db);
		
		$actual_count = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'actual_count',$db);
		$sold = $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'sold',$db);
		$unit_price = $TheFunctions->getItemPriceDateSelectTo($dateselectto,$item_id,$db);
					
		$amount =  $TheFunctions->inventoryValue($selectedbranch,$item_id,$dateselectfrom,$dateselectto,'amount',$db);
		$amounttotal += $amount;
?>
	<tr>		
		<td style="text-align:center;"><?php echo $i; ?></td>	
		<td style="text-align:left;white-space:nowrap"><?php echo $item_name?></td>	
		<td><?php echo $beg?></td>
		<td><?php echo $stock?></td>
		<td><?php echo $frozendough?></td>
		<td><?php echo $transfer_in?></td>
		<td><?php echo $transfer_out?></td>
		<td><?php echo $charges?></td>
		<td><?php echo $snacks?></td>
		<td><?php echo $bad_order?></td>
		<td><?php echo $damaged?></td>
		<td><?php echo $complimentary?></td>
		<td><?php echo $actual_count?></td>
		<td><?php echo $sold?></td>
		<td><?php echo $unit_price?></td>
		<td style="text-align:right"><?php echo number_format($amount,2)?></td>
		
	</tr>
<?php
	}
	
	// ############ BADGE ###############  //
	
	$rchbbadge = $TheFunctions->GetInventoryDiscountType('ROSE CLASSIC HOT BREAD',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$rhebbadge = $TheFunctions->GetInventoryDiscountType('ROSE HIGH-END BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$rbpbadge = $TheFunctions->GetInventoryDiscountType('ROSE BINALOT & PASALUBONG',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$rndbadge = $TheFunctions->GetInventoryDiscountType('ROSE NUTRI-DENSE',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	
	$rtlbadge = $TheFunctions->GetInventoryDiscountType('ROSE TASTY LOAF',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$rcsbadge = $TheFunctions->GetInventoryDiscountType('ROSE CLASSIC SPECIAL',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$atccbadge = $TheFunctions->GetInventoryDiscountType('ALL TIME CAKE (CLASSIC)',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$ccfbadge = $TheFunctions->GetInventoryDiscountType('CELEBRATION CAKES (FLAGSLIP)',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	
	$pchebadge = $TheFunctions->GetInventoryDiscountType('PREMIUM CAKES (HIGH-END)',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$hecbadge = $TheFunctions->GetInventoryDiscountType('HIGH-END COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$fgfrapbadge = $TheFunctions->GetInventoryDiscountType('FGFRAP',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$cakbadge = $TheFunctions->GetInventoryDiscountType('CAKES',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	
	$bevbadge = $TheFunctions->GetInventoryDiscountType('BEVERAGES',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$bottbadge = $TheFunctions->GetInventoryDiscountType('BOTTLED WATER',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$icecbadge = $TheFunctions->GetInventoryDiscountType('ICE CREAM',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$breabadge = $TheFunctions->GetInventoryDiscountType('BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	
	$coffbadge = $TheFunctions->GetInventoryDiscountType('COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$milkbadge = $TheFunctions->GetInventoryDiscountType('MILK TEA',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	$mercbadge = $TheFunctions->GetInventoryDiscountType('MERCHANDISE OTHERS',$selectedbranch,$dateselectfrom,$dateselectto,$db);
	
	
	
	// ############  ###############  //
	
	
	$rchb = $TheFunctions->getInventoryBreakdown('ROSE CLASSIC HOT BREAD',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $rchbbadge;
	$rheb = $TheFunctions->getInventoryBreakdown('ROSE HIGH-END BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $rhebbadge;
	$rbp = $TheFunctions->getInventoryBreakdown('ROSE BINALOT & PASALUBONG',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $rbpbadge;
	$rnd = $TheFunctions->getInventoryBreakdown('ROSE NUTRI-DENSE',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $rndbadge;
	
	$rtl = $TheFunctions->getInventoryBreakdown('ROSE TASTY LOAF',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $rtlbadge;
	$rcs = $TheFunctions->getInventoryBreakdown('ROSE CLASSIC SPECIAL',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $rcsbadge;
	$atcc = $TheFunctions->getInventoryBreakdown('ALL TIME CAKE (CLASSIC)',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $atccbadge;
	$ccf = $TheFunctions->getInventoryBreakdown('CELEBRATION CAKES (FLAGSLIP)',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $ccfbadge;
	
	$pche = $TheFunctions->getInventoryBreakdown('PREMIUM CAKES (HIGH-END)',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $pchebadge;
	$hec = $TheFunctions->getInventoryBreakdown('HIGH-END COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $hecbadge;
	$fgfrap = $TheFunctions->getInventoryBreakdown('FGFRAP',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $fgfrapbadge;
	$cak = $TheFunctions->getInventoryBreakdown('CAKES',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $cakbadge;
	
	$bev = $TheFunctions->getInventoryBreakdown('BEVERAGES',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $bevbadge;
	$bott = $TheFunctions->getInventoryBreakdown('BOTTLED WATER',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $bottbadge;
	$icec = $TheFunctions->getInventoryBreakdown('ICE CREAM',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $icecbadge;
	$brea = $TheFunctions->getInventoryBreakdown('BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $breabadge;
	
	$coff = $TheFunctions->getInventoryBreakdown('COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $coffbadge;
	$milk = $TheFunctions->getInventoryBreakdown('MILK TEA',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $milkbadge;
	$merc = $TheFunctions->getInventoryBreakdown('MERCHANDISE OTHERS',$selectedbranch,$dateselectfrom,$dateselectto,$db) - $mercbadge;
	
		
	$breakdowntotal = ($rchb + $rheb +	$rbp + $rnd + $rtl + $rcs + $atcc + $ccf + $pche + $hec + $fgfrap + $cak + $bev + $bott + $icec + $brea + $coff + $milk + $merc);
		
	
?>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">SALES SUMMARY :</td>
		<td style="text-align:right"><?php echo number_format($amounttotal,2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">
			TOTAL DISCOUNT <?php if($TheFunctions->GetInventoryDiscount($selectedbranch,$dateselectfrom,$dateselectto,$db) == 0) { echo '<span class="dc-notifs">Discount not posted</span>'; } ?>
		</td>
		<td style="text-align:right"><?php echo number_format($TheFunctions->GetInventoryDiscount($selectedbranch,$dateselectfrom,$dateselectto,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">GRAND TOTAL</td>
		<td style="text-align:right"><?php $grand_total = ($amounttotal - $TheFunctions->GetInventoryDiscount($selectedbranch,$dateselectfrom,$dateselectto,$db)); echo number_format($grand_total,2); ?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">TOTAL CASH COUNT</td>
		<td style="text-align:right"><?php echo number_format($TheFunctions->getInventoryCashCountTotal($selectedbranch,$dateselectfrom,$dateselectto,$db),2)?></td>
	</tr>
	<tr class="td-total">
		<td colspan="15" style="text-align:left;font-weight:bold">CASH VARIANCE</td>
		<td style="text-align:right"><?php $variance = $TheFunctions->getInventoryCashCountTotal($selectedbranch,$dateselectfrom,$dateselectto,$db) - $grand_total; echo number_format($variance,2)?></td>
	</tr>

	<tr>
		<td colspan="15">LESS:</td>
	</tr>
	<tr>
		<td colspan="15">SENIOR DISCOUNT</td>
		<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('SENIOR DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
	</tr>
	<tr>
		<td colspan="15">ICE CREAM DISCOUNT</td>
		<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('ICE CREAM DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
	</tr>
	<tr>
		<td colspan="15">CAKE DISCOUNT</td>
		<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('CAKE DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
	</tr>
	<tr>
		<td colspan="15">SPECIALS DISCOUNT</td>
		<td style="text-align:right"><?php echo $TheFunctions->GetIventoryDiscountType('SPECIALS DISCOUNT',$selectedbranch,$dateselectfrom,$dateselectto,$db)?></td>
	</tr>

	</table>
	<table style="width: 100%" class="table table-hover table-striped table-bordered">
		<tr>
			<th title="ROSE CLASSIC HOT BREAD">RCHB<span class="label label-danger"><?php echo $rchbbadge == 0? '': $rchbbadge?></span></th>
			<th title="ROSE HIGH-END BREADS">RHEB<span class="label label-danger"><?php echo $rhebbadge == 0? '': $rhebbadge?></span></th>
			<th title="ROSE BINALOT & PASALUBONG">RBP<span class="label label-danger"><?php echo $rbpbadge == 0? '': $rbpbadge?></span></th>
			<th title="ROSE NUTRI-DENSE">RND<span class="label label-danger"><?php echo $rndbadge == 0? '': $rndbadge?></span></th>
			
			<th title="ROSE TASTY LOAF">RTL<span class="label label-danger"><?php echo $rtlbadge == 0? '': $rtlbadge?></span></th>
			<th title="ROSE CLASSIC SPECIAL">RCS<span class="label label-danger"><?php echo $rcsbadge == 0? '': $rcsbadge?></span></th>
			<th title="ALL TIME CAKE (CLASSIC)">ATCC<span class="label label-danger"><?php echo $atccbadge == 0? '': $atccbadge?></span></th>
			<th title="CELEBRATION CAKES (FLAGSLIP)">CCF<span class="label label-danger"><?php echo $ccfbadge == 0? '': $ccfbadge?></span></th>
			
			<th title="PREMIUM CAKES (HIGH-END)">PCHE<span class="label label-danger"><?php echo $pchebadge == 0? '': $pchebadge?></span></th>
			<th title="HIGH-END COFFEE">HEC<span class="label label-danger"><?php echo $hecbadge == 0? '': $hecbadge?></span></th>
			<th title="FGFRAP">FGFRAP<span class="label label-danger"><?php echo $fgfrapbadge == 0? '': $fgfrapbadge?></span></th>
			<th title="CAKES">CAK.<span class="label label-danger"><?php echo $cakbadge == 0? '': $cakbadge?></span></th>
			
			<th title="BEVERAGES">BEV.<span class="label label-danger"><?php echo $bevbadge == 0? '': $bevbadge?></span></th>
			<th title="BOTTLED WATER">BW<span class="label label-danger"><?php echo $bottbadge == 0? '': $bottbadge?></span></th>
			<th title="ICE CREAM">IC<span class="label label-danger"><?php echo $icecbadge == 0? '': $icecbadge?></span></th>
			<th title="BREADS">B<span class="label label-danger"><?php echo $breabadge == 0? '': $breabadge?></span></th>
			
			<th title="COFFEE">C<span class="label label-danger"><?php echo $coffbadge == 0? '': $coffbadge?></span></th>
			<th title="MILK TEA">MT<span class="label label-danger"><?php echo $milkbadge == 0? '': $milkbadge?></span></th>
			<th title="MERCHANDISE OTHERS">MO<span class="label label-danger"><?php echo $mercbadge == 0? '': $mercbadge?></span></th>
		
			<th title="TOTAL BREAKDOWN">TOTAL</th>
		</tr>
		<tr>
			
			<td class="al-right pad-right"><?php echo number_format($rchb,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($rheb,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($rbp,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($rnd,2); ?></td>
			
			<td class="al-right pad-right"><?php echo number_format($rtl,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($rcs,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($atcc,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($ccf,2); ?></td>
			
			<td class="al-right pad-right"><?php echo number_format($pche,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($hec,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($fgfrap,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($cak,2); ?></td>
			
			<td class="al-right pad-right"><?php echo number_format($bev,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($bott,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($icec,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($brea,2); ?></td>
			
			<td class="al-right pad-right"><?php echo number_format($coff,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($milk,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($merc,2); ?></td>
			
			
			<td class="al-right pad-right"><?php echo number_format($breakdowntotal,2); ?></td>
			
			
			<!--td class="al-right pad-right"><?php echo number_format($breads,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($cakes,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($specials,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($beverages,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($bottledwater,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($icecream,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($merchandiseothers,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($coffee,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($milktea,2); ?></td>
			<td class="al-right pad-right"><?php echo number_format($breakdowntotal,2); ?></td-->
		</tr>

	
	
<?php 
}
else{
	?>
		<tr>
			<td colspan="13" style="text-align:center"><i class="fa fa-bell fa-danger"></i>&nbsp;No results Found</td>
		</tr>
	<?php
}
?>
</table>
<div id="inventoryData"></div>
