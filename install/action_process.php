<?PHP
include '../init.php';
include 'table_classes.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);	
include '../db_config_main.php';
$actions = new TheTables;
$var_month = '02';
$tables = $_POST['tables'];
$tbl = "store_".$tables."_data";
$tbl_module = $actions->GetTables($tables,$db);
$tbl_count = count($tbl_module);
echo '<div style="text-align:center;font-weight:bold;font-size:16px;border-bottom:5px solid orange">'.strtoupper($tables).'</div><br>';
$x = 0;
foreach($tbl_module as $value)
{
	$x++;
	addColumns($tbl,$value,$db);
	if($x == $tbl_count)
	{
		if($tables == 'fgts')
		{
			echo $actions->updateFGTS($tbl,$var_month,$db);
		}
		else if($tables == 'transfer')
		{
			echo $actions->updateTRANSFER($tbl,$var_month,$db);
		}
		else if($tables == 'charges')
		{
			echo $actions->updateCHARGES($tbl,$var_month,$db);
		}
		else if($tables == 'snacks')
		{
			echo $actions->updateSNACKS($tbl,$var_month,$db);
		}
		else if($tables == 'badorder')
		{
			echo $actions->updateBADORDER($tbl,$var_month,$db);
		}
		else if($tables == 'damage')
		{
			echo $actions->updateDAMAGE($tbl,$var_month,$db);
		}
		else if($tables == 'complimentary')
		{
			echo $actions->updateCOMPLIMENTARY($tbl,$var_month,$db);
		}
		else if($tables == 'receiving')
		{
			echo $actions->updateRECEIVING($tbl,$var_month,$db);
		}
		else if($tables == 'cashcount')
		{
			echo "<br><br> -- NO ITEM DATA INVOLVED SKIPPED UPDATING -- ";
		}
		else if($tables == 'frozendough')
		{
			echo $actions->updateFROZENDOUGH($tbl,$var_month,$db);
		}
		else if($tables == 'pcount')
		{
			echo $actions->updatePCOUNT($tbl,$var_month,$db);
		}
		else if($tables == 'discount')
		{
			addTables($tbl,$value,$db);
			echo "<br><br> -- NO ITEM DATA INVOLVED SKIPPED UPDATING -- ";
		}
		else if($tables == 'summary')
		{
			echo $actions->updateSUMMARY($tbl,$var_month,$db);
		}
		/* ------------ RAWMATS ------------------- */
		else if($tables == 'rm_receiving')
		{
			echo $actions->updateRMRECEIVING($tbl,$var_month,$db);
		}
		else if($tables == 'rm_transfer')
		{
			echo $actions->updateRMTRANSFER($tbl,$var_month,$db);
		}
		else if($tables == 'rm_badorder')
		{
			echo $actions->updateRMBADORDER($tbl,$var_month,$db);
		}
		else if($tables == 'rm_pcount')
		{
			echo $actions->updateRMPCOUNT($tbl,$var_month,$db);
		}
		else if($tables == 'rm_summary')
		{
			echo $actions->updateRMSUMMARY($tbl,$var_month,$db);
		}
	}
}
function addTables($tbl,$value,$db)
{
	$sql = "CREATE TABLE IF NOT EXISTS $tbl (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(id))";
	$result = $db->query($sql);
	if($result === TRUE) { 
		echo "<br>DATABASE ".$tbl." -- SUCCESSFULY ADDED ---<br>"; 
	}
	else { echo $db->error." -- SKIPPED. <br>"; }
}

function addColumns($tbl,$value,$db)
{
	$sql = "ALTER TABLE $tbl ADD $value";
	$result = $db->query($sql);
	if($result === TRUE) { echo $value." -- SUCCESSFULY ADDED<br>"; }
	else { echo $db->error." -- SKIPPED. <br>"; }

}
