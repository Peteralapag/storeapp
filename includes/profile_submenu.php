<style>
.header-downdownmenu-wrapper ul {
	margin:0;
	padding:0;
}
.header-downdownmenu-wrapper  ul li {
	display: flex;
	width: 100%;
	border-bottom :1px solid #232323;
}
.header-downdownmenu-wrapper  ul li:last-child {
	border:0;
}
.header-downdownmenu-wrapper  i {
	margin-right: 1.5rem;
	font-size: 2rem;
}
.header-downdownmenu-wrapper li div {
	font-size: 18px;	
	padding: 10px 10px 10px 10px;
	width: 100%;
	cursor: pointer;
}
.header-downdownmenu-wrapper li div:hover {
}
</style>
<?php
include '../init.php';
$functions = new TheFunctions;
$branch = $functions->AppBranch();
$transdate = $functions->GetSession('branchdate');
$shift = $functions->GetSession('shift');
?>
<ul>
	<li>
		<div id="subnav1" data-menu-data="1" onclick="submenu_function('Application Settings','application_settings','')">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-user-gear text-primary"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Profile Settings</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav1" data-menu-data="1" onclick="openThemeSettings()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="fa-solid fa-palette icon-color-orange"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Theme Colors</td>
				</tr>
			</table>				
		</div>
	</li>
	<li>
		<div id="subnav2" data-menu-data="2" onclick="openHelpCenter()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:24px;width:50px;">
						<i class="fa-sharp fa-solid fa-circle-info icon-color-dodger"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Help Center</td>
				</tr>
			</table>				
		</div>
	</li>	
	<li>
		<div id="subnav3" data-menu-data="3" onclick="signOut()">				
			<table style="width: 100%;border-collapse:collapse" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center; font-size:18px;width:50px;">
						<i class="glyphicon glyphicon-log-out icon-color-red"></i>
					</td>
					<td style="width:10px">&nbsp;</td>
					<td>Log Out</td>
				</tr>
			</table>				
		</div>
	</li>
</ul>
<script>
function openHelpCenter()
{
	$.post("../apps/media_play.php", { },	
	function(data) {
		$('#helpcenter_page').html(data);
		$('#helpcenter').show();
	});	
}
function openThemeSettings()
{
	$.post("../includes/sub_profile_themes.php", { },	
	function(data) {
		$('#themes_page').html(data);
		$('#themes').show();
	});
}
</script>
