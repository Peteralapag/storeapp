
<?php
require '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$selectedbranch = $_SESSION['appstore_branch'];
$defaultDate = $_SESSION['session_date'];

$TheFunctions = new TheFunctions;

if(isset($_POST['monthname']) && isset($_POST['yearname']))
{
	$monthname = $_POST['monthname'];
	$monthNumbers = date("n", strtotime($monthname));
	$monthNumber= sprintf("%02d", $monthNumbers);
	
	$yearname = $_POST['yearname'];
	$monthyear = $yearname.'-'.$monthNumber; 
	$branch = $_POST['branch'];

}
else{
	$monthname = '';
	$monthNumbers = '';
	$monthNumber= '';
	
	$yearname = '';
	$monthyear = ''; 
	$branch = '';

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
<div class="container-fluid">
	<table id="upper" class="table table-striped table-bordered">
		<tr>
			<th colspan="22">SALES BREAKDOWN - <?php echo $monthname.' - '.$yearname;?></th>
		</tr>
		<tr>
			<th style="width:50px">DATE</th>
			
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
		
			<th>TOTAL</th>
		</tr> 
<?php
	for ($day = 1; $day <= 31; $day++) {
	    $timestamp = mktime(0, 0, 0, date('m'), $day, date('Y'));
//	    $formattedDate = date('Y-m-d', $timestamp);

		$daysNumber = sprintf('%02d', $day);
		$reportdate = $monthyear.'-'.$daysNumber;
		
		echo '<tr>';
		
	    echo '<td style="text-align: center; font-weight: bold">'.$daysNumber.'</td>';
	    
	    echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ROSE CLASSIC HOT BREAD',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ROSE HIGH-END BREADS',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ROSE BINALOT & PASALUBONG',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ROSE NUTRI-DENSE',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ROSE TASTY LOAF',$selectedbranch,$reportdate,$db),2).'</td>';
		
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ROSE CLASSIC SPECIAL',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ALL TIME CAKE (CLASSIC)',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('CELEBRATION CAKES (FLAGSLIP)',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('PREMIUM CAKES (HIGH-END)',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('HIGH-END COFFEE',$selectedbranch,$reportdate,$db),2).'</td>';
		
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('FGFRAP',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('CAKES',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('BEVERAGES',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('BOTTLED WATER',$selectedbranch,$reportdate,$db),2).'</td>';
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('ICE CREAM',$selectedbranch,$reportdate,$db),2).'</td>';
	    
	    echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('BREADS',$selectedbranch,$reportdate,$db),2).'</td>';
	    echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('COFFEE',$selectedbranch,$reportdate,$db),2).'</td>';
	    echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('MILK TEA',$selectedbranch,$reportdate,$db),2).'</td>';
	    echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2('MERCHANDISE OTHERS',$selectedbranch,$reportdate,$db),2).'</td>';
	    
		
		
		echo '<td style="text-align: right">'.number_format($TheFunctions->salesBreakdown2TotalPerDay($selectedbranch,$reportdate,$db),2).'</td>';

	    
	    echo '</tr>';	    
	}
?>		
	<tr>
		<td style="font-weight:bold; text-align:center">*</td>
		
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ROSE CLASSIC HOT BREAD',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ROSE HIGH-END BREADS',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ROSE BINALOT & PASALUBONG',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ROSE NUTRI-DENSE',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ROSE TASTY LOAF',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ROSE CLASSIC SPECIAL',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ALL TIME CAKE (CLASSIC)',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('CELEBRATION CAKES (FLAGSLIP)',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('PREMIUM CAKES (HIGH-END)',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('HIGH-END COFFEE',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('FGFRAP',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('CAKES',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('BEVERAGES',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('BOTTLED WATER',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('ICE CREAM',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('BREADS',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('COFFEE',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('MILK TEA',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonthandDate('MERCHANDISE OTHERS',$selectedbranch,$monthNumbers,$yearname,$db),2);?></td>		
		
		<td style="text-align: right; font-weight:bold"><?php echo number_format($TheFunctions->salesBreakdown2TotalPerMonth($selectedbranch,$monthNumbers,$yearname,$db),2);?></td>
	</tr>
	</table>
</div>
<div id="salesbreakdownData"></div>

<?php
function getBreakdownViaDate($month,$year,$q,$db){
	$sql = "SELECT * FROM store_summary_data $q";
	$result = mysqli_query($db, $sql);
	
	if ($result) {
		if (mysqli_num_rows($result) > 0) {
	        while ($row = mysqli_fetch_assoc($result)) {
	            // Process each row of data
	            echo "Column1: " . $row["column1"] . " - Column2: " . $row["column2"] . " - Column3: " . $row["column3"] . "<br>";
	        }
	    } else {
	        echo "No results found.";
	    }
	
	    mysqli_free_result($result);
	} else {
	    echo "Error executing the query: " . mysqli_error($db);
	}
	
	mysqli_close($db);
}
?>