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

.table-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .table-wrapper {
    width: 30%; /* Adjust the width of each table wrapper as needed */
    margin-bottom: 5px; /* Add some space between rows, adjust as needed */
  }

  .inline-table {
    border-collapse: collapse;
    width: 100%; /* Table takes 100% width of its container */
  }

  /* Style the table, rows, and cells as desired */
  .inline-table, .inline-table th, .inline-table td {
    border: 1px solid black;
    padding: 5px;
    text-align: center;
  }
</style>

<?php 
?>
<div id="upper">
	<div id="totalresult" style="text-align:center">TOTAL:&nbsp;<span style="color:red"><?php echo number_format($TheFunctions->totalSalesBreakdown($selectedbranch,$dateselectfrom,$dateselectto,$db),2);?></span></div>
	<hr>
	<div class="container-fluid">
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('ROSE CLASSIC HOT BREAD',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('ROSE HIGH-END BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('ROSE BINALOT & PASALUBONG',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		</div>
		
		<hr>
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('ROSE NUTRI-DENSE',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('ROSE TASTY LOAF',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('ROSE CLASSIC SPECIAL',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		</div>
				
		<hr>
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('ALL TIME CAKE (CLASSIC)',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('CELEBRATION CAKES (FLAGSLIP)',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('PREMIUM CAKES (HIGH-END)',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		</div>
		
		<hr>
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('HIGH-END COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('FGFRAP',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('BREADS',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		</div>

		<hr>
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('SPECIALS',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('CAKES',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('BEVERAGES',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		</div>
		
		<hr>
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('BOTTLED WATER',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('ICE CREAM',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('MERCHANDISE OTHERS',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		</div>
		<hr>
		<div class="row">
		    <div class="col-lg-4">
				<?php echo tables('COFFEE',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				<?php echo tables('MILK TEA',$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db); ?>
		    </div>
		    <div class="col-lg-4">
				
		    </div>
		</div>	
	</div>
</div>
<?php
function tables($category,$selectedbranch,$dateselectfrom,$dateselectto,$TheFunctions,$q,$db){
$total = 0;
?>	
	<table id="table4" style="width: 100%" class="table table-hover table-striped table-bordered inline-table">
		<tr>
			<th colspan="3" style="width:50px;text-align:center"><?php echo $category?></th>
		</tr>
		<tr>
			<th style="width:50px;text-align:center">#</th>
			<th>ITEMS</th>
			<th>AMOUNT</th>
		</tr>
	<?php
		$query ="SELECT * FROM store_summary_data $q AND category='$category' GROUP BY item_id"; 	
		$result = mysqli_query($db, $query);  
		if($result->num_rows > 0)
		{
			$cakesAmount =0;
			$i=0;
			while($ROW = mysqli_fetch_array($result))  
			{
				$i++;
				$item_id = $ROW['item_id'];	
				$item_name = $ROW['item_name'];	
				$amount =  $TheFunctions->getSalesBreakdown($category,$item_id,$selectedbranch,$dateselectfrom,$dateselectto,$db);
				$total += $amount;
				?>
				<tr>
					<td><?php echo $i?></td>
					<td style="text-align:left"><?php echo $item_name?></td>
					<td style="text-align:right"><?php echo number_format($amount,2)?></td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td colspan="2">TOTAL:</td>
				<td style="text-align:right; font-weight:bold"><?php echo number_format($total,2) ?></td>
			</tr>
			<?php
		}
	?>
	</table>
<?php
}
?>