<?php
include '../init.php';
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
?>
<iframe id="viewdocuments" style="width:100%; height:100%"></iframe> 
<script>
$(function(){
	var branch = '<?php echo $branch?>';
	var shift = '<?php echo $shift?>';
	var transdate = '<?php echo $transdate?>';
	$("#viewdocuments").attr("src", "http://storeadmin.rosebakeshops.com/remote_form/view_documents_form.php?branch="+branch+"&shift="+shift+"&transdate="+transdate);
});
</script>