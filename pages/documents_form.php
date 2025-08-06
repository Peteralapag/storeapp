<?php
include '../init.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
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
		<td style="width:350px;position:relative">
			<i class="fa-solid fa-magnifying-glass searchicon"></i>
			<span class="tm" onclick="clearSearch('itemsearch')"></span>
			<input id="itemsearch" type="text" class="form-control" style="padding-left:35px;padding-right:57px" placeholder="Search Item" autocomplete="no">
		</td>
		<td style="width:0.5em" class="branch-info"></td>
		
		<td style="text-align:right">
			<button class="btn btn-success" onclick="upload('<?php echo $branch?>','<?php echo $shift?>','<?php echo $transdate?>')"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;&nbsp;UPLOAD</button>
		</td>
	</tr>
</table>
<div class="Results"></div>
<script>
function upload(branch,shift,transdate){
	var branch = '<?php echo $branch?>';
	var shift = '<?php echo $shift?>';
	var transdate = '<?php echo $transdate?>';
	$("#uploadDocuments_page").attr("src", "http://storeadmin.rosebakeshops.com/remote_form/upload_documents_form.php?branch="+branch+"&shift="+shift+"&transdate="+transdate);
	$('#uploadDocuments').fadeIn();
}
window.addEventListener('message', function(event) {
	if (event.data === 'uploadSuccess') {
		$('#uploadDocuments').fadeOut();
		
		swal({
		title: "Upload Success",
		text: "The picture was uploaded successfully!",
		icon: "success",
		buttons: { confirm: "OK", },
		}).then(function () {
			location.reload();
      });
	}
});

</script>
