<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
$functions = new TheFunctions;
//$date = new DatenTime;
$date_executed = date('Y-m-d H:i:s');
if(isset($_SESSION['session_date'])) { $trans_date = $_SESSION['session_date']; } else { $trans_date = $date->get_date(); }
if(isset($_SESSION['session_shift'])) { $store_shift = " - ".$_SESSION['session_shift']; } else { $store_shift = ''; }
if(isset($_SESSION['appstore_month'])) { $months = $_SESSION['appstore_month']; } else { $months = date("F"); }
// $dropdown = new Dropdown;
$monthnum = $functions->GetMonthNumber($months);
if(isset($_POST['year']) || $_POST['year'] != '')
{
	$year = $_POST['year'];
} else { 
	$year = date("Y");
}
$month = date("m", strtotime($_POST['monthname']));
$monthname = $_POST['monthname'];
$daysCount = $functions->GetDaysNum($month,$year);
$store_branch = $_SESSION['appstore_branch'];
if(isset($_POST['mode']))
{
	$mode = $_POST['mode'];
} else {
	print_r('
		<script>
			app_alert("Warning"," The Mode you are trying to pass does not exist","warning","Ok","","no");
		</script>
	');
	exit();
}
if($mode=='calculateproduction')
{
	 calculateProduction($store_branch,$monthnum,$db);
}
if($mode=='fetchproddata')
{	
	$queryItem ="SELECT * FROM store_summary_data WHERE branch='$store_branch' AND MONTH(report_date)='$month' AND YEAR(report_date)='$year' AND category='BREADS'";
	$itemResult = mysqli_query($db, $queryItem);
	$rowcount=mysqli_num_rows($itemResult);  
	if($itemResult->num_rows > 0)
	{
		$j=0;
		while($ROW = mysqli_fetch_array($itemResult ))  
		{
			$j++;
			$item_name = $ROW['item_name'];
			$actual_count = $ROW['kilo_used'];
			
			$columnd = date("d", strtotime($ROW['report_date']));	
				
		
			$day_total = $functions->ProductionDailyTotalAmount($store_branch,$ROW['report_date'],$item_name,$db);

			$update = "`$columnd`='$day_total'";	
			
			$queryDataUpdate = "UPDATE store_production_data SET $update WHERE item_name='$item_name' AND month='$monthnum' AND branch='$store_branch'";
						
			if ($db->query($queryDataUpdate) === TRUE)
			{} else { echo "ERROR::: ".$db->error."<br>"; }

			if($j == $rowcount)
			{
				calculateProduction($store_branch,$monthnum,$db);
			}
		}
	} else {			
		echo "wala<br>";
	}
}
function calculateProduction($store_branch,$monthnum,$db)
{
	$sql = "SELECT * FROM store_production_data WHERE branch='$store_branch' AND month='$monthnum'";
	$result = mysqli_query($db, $sql);
	$rowcnt=mysqli_num_rows($result);
	$x=0;
	while($ROW = mysqli_fetch_array($result))  
	{
		$rowid = $ROW['id'];
		$a=$ROW['01'];$b=$ROW['02'];$c=$ROW['03'];$d=$ROW['04'];$e=$ROW['05'];$f=$ROW['06'];$g=$ROW['07'];$h=$ROW['08'];$i=$ROW['09'];$j=$ROW['10'];$k=$ROW['11'];
		$l=$ROW['12'];$m=$ROW['13'];$n=$ROW['14'];$o=$ROW['15'];$p=$ROW['16'];$q=$ROW['17'];$r=$ROW['18'];$s=$ROW['19'];$t=$ROW['20'];$u=$ROW['21'];$v=$ROW['22'];
		$w=$ROW['23'];$x=$ROW['24'];$y=$ROW['25'];$z=$ROW['26'];$aa=$ROW['27'];$bb=$ROW['28'];$cc=$ROW['29'];$dd=$ROW['30'];$ee=$ROW['31'];
		
		$total = ($a+$b+$c+$d+$e+$f+$g+$h+$i+$j+$k+$l+$m+$n+$o+$p+$q+$r+$s+$t+$u+$v+$w+$x+$y+$z+$aa+$bb+$cc+$dd+$ee);

		proddataUpdate($rowid,$total,$db);
	}	
}
function proddataUpdate($rowid,$total,$db)
{
	$queryDataUpdate = "UPDATE store_production_data SET total_count='$total' WHERE id='$rowid'";
	if ($db->query($queryDataUpdate) === TRUE)
	{} else { echo "UPDATE ERROR::: ".$db->error; }
}
if($mode=='fetchproditems')
{
	$queryItem ="SELECT * FROM store_summary_data WHERE stock_in > 0 AND branch='$store_branch' AND MONTH(report_date)='$month' AND YEAR(report_date)='$year' AND category='BREADS' GROUP BY item_name";
	$itemResult = mysqli_query($db, $queryItem);  
	if($itemResult->num_rows > 0)
	{
		while($ROW = mysqli_fetch_array($itemResult ))  
		{
			$item_id = $ROW['item_id'];
			$item_name = $ROW['item_name'];
			$checkItem ="SELECT * FROM store_production_data WHERE item_name='$item_name' AND branch='$store_branch' AND month='$monthnum' AND year='$year'";
			$checkResult = mysqli_query($db, $checkItem);  
			if($checkResult->num_rows === 0)
			{
			//	$months = date("F", strtotime($monthname));
				proddataInsert($item_name,$item_id,$year,$month,$store_branch,$db);
			//	print_r('<script>app_alert("System Message","All items for this '.$months.' is successfully fetch","success","Ok","","");');
			}
		}
	} else {			
		$months = date("F", strtotime($monthname));
		print_r('<script>app_alert("System Message","No Records for this month of '.$months.'","warning","Ok","","");');
	}
}
function proddataInsert($item_name,$item_id,$year,$month,$store_branch,$db)
{
	$query = "INSERT INTO store_production_data (`item_name`,`item_id`,`month`,`year`,`branch`)";
	$query .= "VALUES ('$item_name',$item_id,'$month','$year','$store_branch')";
	if ($db->query($query) === TRUE) { } else {
		echo $db->error;
	}
}
