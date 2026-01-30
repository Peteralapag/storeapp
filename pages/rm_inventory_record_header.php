<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$table = "store_".$file_name."_data";

$dateLockChecker = $functions->dateLockChecker($branch,$transdate,$db);
$lock_by = $functions->analystVal($branch,$transdate,$db);




function checkRmPcountPosted($branch,$transdate,$shift,$db)
{
	$sql = "SELECT * FROM store_rm_pcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND posted='Posted' AND status='Closed'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
		return 1;
	}
	else{
		return 0;
	}
}
function checkPcountPosted($branch,$transdate,$shift,$db)
{
	$sql = "SELECT * FROM store_pcount_data WHERE branch='$branch' AND report_date='$transdate' AND shift='$shift' AND posted='Posted' AND status='Closed'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
		return 1;
	}
	else{
		return 0;
	}
}
$checkingforsummaryfgts = checkPcountPosted($branch,$transdate,$shift,$db);

?>
<style>
.pagemenu {
	border: 1px solid var(--text-grey);
	background: #cecece;
	padding:5px 15px 5px 15px;
	border-radius:7px;
	cursor: pointer;
	color: var(--text-grey);
}
.pagemenu:hover {
	background: #f1f1f1;
	border: 1px solid #f1f1f1;
}
</style>
<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		
		<td style="position:relative;">
		  <input id="itemsearch" type="text" class="form-control form-control-sm" placeholder="Search Item" autocomplete="off" style="width:250px;padding-left : 30px;">     
		  <i class="fa fa-search" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: gray;"></i>
		</td>
		

		<td style="width:0.5em" class="branch-info"></td>
		<td style="text-align:right">
			<?php if(checkRmPcountPosted($branch,$transdate,$shift,$db) == 0){ ?>
				<button id="posttosummarybtn" class="btn btn-success btn-sm" onclick="addItems()"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;ADD ITEM</button>&nbsp;
				<button id="posttosummarybtn" class="btn btn-info btn-sm" onclick="postItemModule('<?php echo $file_name; ?>')"><i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;POST TO SUMMARY</button>&nbsp;

			<?php } ?>
		</td>
	</tr>
</table>
<div class="Results"></div>

<script>

document.getElementById("itemsearch").addEventListener("keyup", function () {
	const searchValue = this.value.toLowerCase();
	const rows = document.querySelectorAll("#upper tbody tr");

	rows.forEach(function (row) {
		const rowText = row.textContent.toLowerCase();
		row.style.display = rowText.includes(searchValue) ? "" : "none";
	});
});

function addItems(){

	
	$.post("./apps/rm_inventory_record_form.php", { },
	function(data) {
		$('#additem_title').html('ADD INVENTORY ITEMS');
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();
}

function addItem(transmode,file_name,title)
{
	console.log(file_name);
	$('#additem_title').html("ADD NEW " + title + " DATA");
	$.post("./apps/" + file_name + "_form.php", { file_name: file_name, transmode: transmode },
	function(data) {
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();
}

function postItemModule(filename)
{
	var userAnalyst ='<?php echo $lock_by;?>';
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	var branch = '<?php echo $branch; ?>';
	var transdate = '<?php echo $transdate; ?>';

	var checkpcount = '<?php echo $checkingforsummaryfgts ?>';
	
	/*
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact "+userAnalyst+" the Data Analysts","warning","Ok","","");
		return false();
	}
	*/

	if(checkpcount != 1){
		app_alert("System Message","You can’t post the inventory if the FGTS inventory is not yet posted.","warning","Ok","","");
		return false();
	}

	app_confirm("Post to Summary","Once you've post this, it's final; it can't be altered again.","warning",'postItemModule',filename,'');
	return false;
}

function postToSummary(params)
{
	var userAnalyst ='<?php echo $lock_by;?>';
	var dateLockChecker = '<?php echo $dateLockChecker; ?>';
	
	/*
	if(dateLockChecker == 1){
		app_alert("System Message","The date is already locked, if there are any changes, please contact "+userAnalyst+" the Data Analysts","warning","Ok","","");
		return false();
	}
	*/
	
	if(checkPcountPosted($branch,$transdate,$shift,$db) != 1){
		app_alert("System Message","The Pcount is not yet posted, please post the Pcount first.","warning","Ok","","");
		return false();
	}

	psaSpinnerOn();

	var mode = params;
	
	setTimeout(function()
	{
		$.post("./actions/post_summary_process.php", { mode: mode },
		function(data) {
			$(".Results").html(data);
			psaSpinnerOff();
			set_function('Inventory Record','rm_inventory_record');
		});
	},1000);
}


</script>