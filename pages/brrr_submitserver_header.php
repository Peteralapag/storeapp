<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

?>



<table style="width: 100%;border-collapse:collapse;white-space:nowrap" cellpadding="0" cellspacing="0">
	<tr>
		<td style="width:250px;">
			<input type="text" class="form-control" value="<?php echo $functions->GetSession('branch'); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
			<input id="date" type="text" class="form-control" value="<?php echo $functions->GetSession('branchdate'); ?>">
		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">

		</td>
		<td style="width:0.5em"></td>
		<td style="width:150px">
		</td>
	</tr>
</table>
<div class="Results"></div>

