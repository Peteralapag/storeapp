<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$file_name = $_POST['pagename'];
$title = strtoupper($file_name);

$table = "store_".$file_name."_data";
if($functions->CheckData($table,$functions->AppBranch(),$functions->GetSession('branchdate'),$functions->GetSession('shift'),$db) == 1)
{
	$summary_btn = '';
	if($functions->GetSession('userlevel') == 50 || $functions->GetSession('userlevel') >= 80)
	{
		$unlock_btn = '';
	} else {
		$unlock_btn = 'style="display: none"';
	}
} else {
	$summary_btn = 'disabled';
	$unlock_btn = 'disabled';
}
?>
<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:250px;">
			<input type="text" class="form-control" value="<?php echo $functions->AppBranch(); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
			<input id="date" type="text" class="form-control" value="<?php echo $functions->GetSession('branchdate'); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
			<input id="shift" type="text" class="form-control" value="<?php echo $functions->GetSession('shift'); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
			<button id="additembtn" class="btn btn-success" onclick="addItem('new','<?php echo $file_name; ?>','<?php echo $title; ?>')"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;ADD ITEM</button>
		</td>
		<td style="text-align:right">
			<button id="posttosummarybtn" class="btn btn-danger" <?php echo $summary_btn; ?> onclick="postToSummary('<?php echo $file_name; ?>')"><i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;POST TO SUMMARY</button>&nbsp;
			<button id="unlockbutton" class="btn btn-primary" <?php echo $unlock_btn; ?> onclick="unLock('<?php echo $file_name; ?>')"><i class="fa-solid fa-lock-open"></i>&nbsp;&nbsp;UNLOCK POST</button>
		</td>
	</tr>
</table>
<div class="Results"></div>
<script>
function unLock(params)
{
	var title = '<?php echo $title; ?>';
	$('#posttosummarybtn,#unlockbutton,#additembtn').attr("disabled", true);
	$('#unlockbutton').html('<i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;Unlocking ' + title + ' data &nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>');
	var mode = "unlockpost";	
	var database = params
	setTimeout(function()
	{
		$.post("./actions/actions.php", { mode: mode, database: database },
		function(data) {
			$(".Results").html(data);
			$('#' + sessionStorage.navcount).click();
		});
	},1000);

}
function postToSummary(params)
{
	$('#posttosummarybtn,#unlockbutton,#additembtn').attr("disabled", true);
	$('#posttosummarybtn').html('<i class="fa-solid fa-bring-forward"></i>&nbsp;&nbsp;Posting to Summary&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>');
	var mode = params;
	setTimeout(function()
	{
		$.post("./actions/post_summary_process.php", { mode: mode },
		function(data) {
			$(".Results").html(data);
			$('#' + sessionStorage.navcount).click();
		});
	},1000);
}
function addItem(transmode,file_name,title)
{
	$('#additem_title').html("ADD NEW " + title + " DATA");
	$.post("./apps/" + file_name + "_form.php", { file_name: file_name, transmode: transmode },
	function(data) {
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();
}
function editItem(transmode,file_name,title,rowid)
{
	$('#additem_title').html("ADD NEW " + title + " DATA");
	$.post("./apps/" + file_name + "_form.php", { file_name: file_name, transmode: transmode, rowid: rowid },
	function(data) {
		$("#additem_page").html(data);
	});
	$('#additem').fadeIn();
}
</script>