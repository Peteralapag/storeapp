<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$shift = $functions->GetSession('shift');
$report_date = $functions->GetSession('branchdate');
?>

<table class="table table-success">
	<tr>
		<td>BRANCH: </td>
		<td><input type="text" class="form-control form-control-sm" value="<?php echo $branch?>" disabled="disabled"></td>
	</tr>
	<tr>
		<td>SHIFT:</td>
		<td><input type="text" class="form-control form-control-sm" value="<?php echo $shift?>" disabled="disabled"></td>
	</tr>
	<tr>
		<td>DATE:</td>
		<td><input type="text" class="form-control form-control-sm" value="<?php echo $report_date?>" disabled="disabled"></td>
	</tr>
	<tr>
		<td>UPLOAD:</td>
		<td><input type="file" class="form-control form-control-lg"></td>
	</tr>
	<tr>
		<td></td>
		<td style="float:right">
			<button class="btn btn-primary" onclick="uploaddoc('uploaddoc')">UPLOAD</button>
			<button class="btn btn-danger" onclick="closeMe()">CLOSE</button>
		</td>
	</tr>
</table>
<script>
function closeMe(){
	$('#uploadDocuments').fadeOut();
}

</script>
<script src="scripts/form_scripts.js"></script>