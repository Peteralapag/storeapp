<style>
.downdownmenu-wrapper ul {
	margin:0;
	padding:0;
}
.downdownmenu-wrapper  ul li {
	display: flex;
	width: 100%;
}
.downdownmenu-wrapper  i {
	margin-right: 1.5rem;
	font-size: 2rem;
	border-bottom :1px solid #aeaeae;
}
.downdownmenu-wrapper li div {
	font-size: 18px;	
	padding: 15px 15px 15px 15px;
	width: 100%;
	cursor: pointer;
	border-bottom:1px solid #aeaeae;
}
.downdownmenu-wrapper li div:hover {
	
}
.submenu-branch-info li {
	display:none;
	visibility:hidden;
}
</style>
<?php
include '../init.php';
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
$title = $_POST['title'];
$pagename = $_POST['filename'];
?>
<ul class="a">
	<li>
		<div><input id="itemsearch" type="text" class="form-control input-lg" placeholder="Search <?php echo $title; ?>"></div>
	</li>
	<div id="submenubranchinfo" style="display:none">
	<li>
		<div>
			<input type="text" class="form-control" value="<?php echo $functions->AppBranch(); ?>" disabled>
		</div>
	</li>
	<li>
		<div>
			<input id="date" type="text" class="form-control" value="<?php echo $functions->GetSession('branchdate'); ?>" disabled>
		</div>
	</li>
	<li>
		<div>
			<input id="shift" type="text" class="form-control" value="<?php echo $functions->GetSession('shift'); ?>" disabled>
		</div>
	</li>
	</div>
	<li>
		<div>View Remote Transfer</div>
	</li>
</ul>
<script>
$(function()
{
	if($(window).width() < 1366)
	{
		$('#submenubranchinfo').show();
	}
	setTimeout(function()
	{
		document.getElementById('itemsearch').focus();
	},100);
	var mode = 'searchitems';
	var branch = '<?php echo $branch; ?>';
	var transdate = '<?php echo $transdate; ?>';
	var pagename = '<?php echo $pagename; ?>';
	var shift = '<?php echo $shift; ?>';
	$('#itemsearch').keyup(function()
	{
		var search = $('#itemsearch').val();
		$.post("./includes/" + pagename + "_data.php", { pagename: pagename, branch: branch, transdate: transdate, shift: shift, search: search },
		function(data) {
			$("#contentdata").html(data);
		});
	});
});
</script>
