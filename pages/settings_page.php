<?php
include '../init.php';
$functions = new TheFunctions;
if($functions->GetSession('userlevel') >= 80)
{
	include '../includes/settings.php';
} else {
	$cmd = '';
	$cmd .= '
		<script>
			swal("Access Denied","Please contact your system Administor","warning");
		</script>
	';
}
?>