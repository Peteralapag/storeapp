<style>
.header-sub-downdownmenu-wrapper ul {
	margin:0;
	padding:0;
}
.header-sub-downdownmenu-wrapper ul li {
	display: flex;
	width: 100%;
	border-bottom :1px solid #232323;
}
.header-sub-downdownmenu-wrapper ul li:last-child {
	border:0;
}
.header-sub-downdownmenu-wrapper i {
	margin-right: 1.5rem;
	font-size: 2rem;
}
.header-sub-downdownmenu-wrapper li div {
	font-size: 18px;	
	padding: 10px 10px 10px 10px;
	width: 100%;
	cursor: pointer;
}
.header-sub-downdownmenu-wrapper li:hover {
	background:#f1f1f1;
}
</style>
<script src="../scripts/sweetalert2.min.js"></script>
<?php
include '../init.php';
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
?>
<ul class="subnavigation">
	<li>
		<div id="subnav1" data-menu-data="1" onclick="ChangeSideBar('fgts')">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-utensils text-primary"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Finish Goods</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav2" data-menu-data="2" onclick="ChangeSideBar('rawmats')">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-sack text-primary"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Rawmats</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav3" data-menu-data="3" onclick="ChangeSideBar('supplies')">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-box-tissue text-primary"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Supplies</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav4" data-menu-data="4" onclick="documents('<?php echo $branch?>','<?php echo $shift?>','<?php echo $transdate?>')">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa fa-upload text-primary" aria-hidden="true"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Documents</td>
				</tr>
			</table>				
		</div>
	</li>
</ul>
<div id="res"></div>
<script>
function documents(branch,shift,transdate){
	if(sessionStorage.IS_ONLINE == 1)
	{
		$("#contentdata").load("../includes/documents_data.php");
	}
	else{	
		Swal.fire('Warning','Server is offline!','warning');
	}

	
}
function viewDocuments(branch,shift,transdate){
	
	$('#uploadDocuments').fadeIn();
}
function uploadDocuments(branch,shift,transdate){
	if(sessionStorage.IS_ONLINE == 1)
	{
		$("#uploadDocuments_page").attr("src", "http://storeadmin.applications.prj/remote_form/upload_documents_form.php?branch="+branch+"&shift="+shift+"&transdate="+transdate);
		$('#uploadDocuments').fadeIn();
	}
	else{
		swal('Warning','Server is offline!','warning');
	}
}
function ChangeSideBar(params)
{
	var mode = 'checkShifting';
	$.post("./actions/actions.php", { mode: mode },
	function(data) {
		$('#res').html(data);
		if(params=='fgts')
		{
			var val = '<?php $functions->GetShifting()?>';
			set_session(val,'appstore_shifting');
		}
		else{
			set_session('2','appstore_shifting');
		}
	});
	
	$.post("./themes/sidebar.php", { },
	function(data) {
		$('#leftwrapper').html(data);
		location_reload();
	});
	set_session(params,'session_sidebar')

}
function set_session(value,params)
{
	$.post("./actions/set_session_process.php", { params: params, value: value },
	function(data) {
		$("#"+sessionStorage.navcount).click();
	});
}
function submenu_function(menuname,pagename)
{
	var submenu = 'submenu';
	$.post("./actions/set_session_process.php", { submenu: submenu, menuname: menuname, pagename: pagename },
	function(data) {
		location.reload(true);
	});
	if(pagename == 'application_settings')
	{
		$('#pagetitle').html(menuname);
		$.post("../pages/settings_header.php", { pagename: pagename },
		function(data) {
			$('#contentform').html(data);
		});
		$('#pagetitle').html(menuname);
		$.post("../pages/settings_page.php", { pagename: pagename },
		
		function(data) {
			$('#contentdata').html(data);
		});
	} 
	
}
$(function()
{
});
</script>
