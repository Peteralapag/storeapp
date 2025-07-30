<style>
.header-downdownmenu-wrapper ul {margin:0;padding:0;}
.header-downdownmenu-wrapper  ul li {display: flex;width: 100%;border-bottom :1px solid #232323;}
.header-downdownmenu-wrapper  ul li:last-child {border:0;}
.header-downdownmenu-wrapper  i {margin-right: 1.5rem;font-size: 2rem;}
.header-downdownmenu-wrapper li div {font-size: 18px;	padding: 10px 10px 10px 10px;width: 100%;cursor: pointer;}
.header-downdownmenu-wrapper li div:hover {}
</style>
<?php
include '../init.php';
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');

if(empty($_SESSION['OFFLINE_MODE']) || $_SESSION['OFFLINE_MODE'] == '' || $_SESSION['OFFLINE_MODE'] == 0)
{
	$OFFLINE_MODE = 0;
	$om_text = 'is OFF';
}
else if($_SESSION['OFFLINE_MODE'] == 1)
{
	$OFFLINE_MODE = 1;
	$om_text = 'is ON';
}
?>
<ul class="subnavigation">
<?php 
	if($functions->GetSession('userlevel') >= 80) {
?>
	<li>
		<div id="subnav1" data-menu-data="1" onclick="submenu_function('Application Settings','application_settings','')">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-apple-whole icon-color-red"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Application Settings</td>
				</tr>
			</table>				
		</div>
	</li>
<?php } ?>	
	<li>
		<div id="subnav2" data-menu-data="2" onclick="openDatabaseSettings()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-database icon-color-gold"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Database Settings</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav3" data-menu-data="3" onclick="updateApps()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-download icon-color-green"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Application Update</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav4" data-menu-data="4" onclick="FixItemIds()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-screwdriver-wrench icon-color-silver"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Database Tools</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav5" data-menu-data="5" onclick="offLineMode()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i id="wificon" class="fa-solid fa-wifi icon-color-green"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>
						Offline Mode <?php echo $om_text; ?>
					</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav5" data-menu-data="5" onclick="changeServer()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa fa-server icon-color-blue" aria-hidden="true"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>
						Change Server
					</td>
				</tr>
			</table>				
		</div>
	</li>	
</ul>
<script>
$(function()
{
	var offline_mode = '<?php echo $OFFLINE_MODE; ?>';
	if(offline_mode == 1)
	{
		$('#wificon').removeClass('fa-solid fa-wifi icon-color-green');
		$('#wificon').addClass('fa-solid fa-wifi-slash icon-color-grey');
	}
});
function changeServer()
{
	$.post("../apps/change_server_form.php", { },
	function(data) {
		$('#changeserverapp_page').html(data);
		$('#changeserverapp').fadeIn();
	});
}
function offLineMode()
{
	$.post("../apps/offline_mode.php", { },
	function(data) {
		$('#offlinemode_page').html(data);
		$('#offlinemode').show();
	});
}
function FixItemIds()
{
	$.post("../apps/fix_pages.php", { },
	function(data) {
		$('#fixids_page').html(data);
		$('#fixids').show();
	});
}
function openDatabaseSettings()
{
	$.post("../updates/update_database.php", { },
	function(data) {
		$('#updating_page').html(data);
		$('#updating').fadeIn();
	});
}
function submenu_function(menuname,pagename)
{
	var submenu = 'submenu';
	$.post("./actions/set_session_process.php", { submenu: submenu, menuname: menuname, pagename: pagename },
	function(data) {
		console.log(data);
	});
	if(pagename == 'application_settings')
	{
		$('#contentdata').removeClass('content-data').addClass('content-data-full');
		$('#pagetitle').html(menuname);
		$('#contentform').hide();
		$.post("../pages/settings_page.php", { pagename: pagename },
		
		function(data) {
			$('#contentdata').html(data);
		});
	} 
}
</script>
